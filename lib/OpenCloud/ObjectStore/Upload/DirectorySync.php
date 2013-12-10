<?php

namespace OpenCloud\ObjectStore\Upload;

use DirectoryIterator;
use Guzzle\Http\EntityBody;
use OpenCloud\Common\Collection\ResourceIterator;
use OpenCloud\Common\Exceptions\InvalidArgumentError;
use OpenCloud\ObjectStore\Resource\Container;

class DirectorySync
{
    private $basePath;
    private $remoteFiles;
    private $container;

    public static function factory($path, Container $container)
    {
        $transfer = new self();
        $transfer->setBasePath($path);
        $transfer->setContainer($container);
        $transfer->setRemoteFiles($container->objectList());

        return $transfer;
    }

    public function setBasePath($path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentError(sprintf('%s does not exist', $path));
        }

        $this->basePath = $path;
    }

    public function setRemoteFiles(ResourceIterator $remoteFiles)
    {
        $this->remoteFiles = $remoteFiles;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function execute()
    {
        $localFiles = $this->traversePath($this->basePath);

        $this->remoteFiles->rewind();
        $this->remoteFiles->populateAll();

        $requests = array();
        $deletePaths = array();

        // Handle PUT requests (create/update files)
        foreach ($localFiles as $filename) {

            $callback = $this->getCallback($filename);

            $filePath   = rtrim($this->basePath, '/') . '/' . $filename;
            $entityBody = EntityBody::factory(fopen($filePath, 'r+'));

            if (false !== ($remoteFile = $this->remoteFiles->search($callback))) {
                // if different, upload updated version
                if ($remoteFile->getEtag() != $entityBody->getContentMd5()) {
                    $requests[] = $this->container->getClient()->put(
                        $remoteFile->getUrl(),
                        $remoteFile->getMetadata()->toArray(),
                        $entityBody
                    );
                }

            } else {
                // upload new file
                $url = clone $this->container->getUrl();
                $url->addPath($filename);

                $requests[] = $this->container->getClient()->put($url, array(), $entityBody);
            }
        }

        // Handle DELETE requests
        foreach ($this->remoteFiles as $remoteFile) {
            $remoteName = $remoteFile->getName();
            if (!in_array($remoteName, $localFiles)) {
                $deletePaths[] = sprintf('/%s/%s', $this->container->getName(), $remoteName);
            }
        }

        // send update/create requests
        $this->container->getClient()->send($requests);

        // bulk delete
        $this->container->getService()->bulkDelete($deletePaths);
    }

    private function traversePath($path)
    {
        $filenames = array();

        $directory = new DirectoryIterator($path);

        foreach ($directory as $file) {
            if ($file->isDot()) {
                continue;
            }
            if ($file->isDir()) {
                $filenames = array_merge($filenames, $this->traversePath($file->getPathname()));
            } else {
                $filenames[] = $this->trimFilename($file);
            }
        }

        return $filenames;
    }

    private function trimFilename($file)
    {
        return ltrim(str_replace($this->basePath, '', $file->getPathname()), '/');
    }

    private function getCallback($name)
    {
        $name = trim($name, '/');
        return function($remoteFile) use ($name) {
            if ($remoteFile->getName() == $name) {
                return true;
            }
            return false;
        };
    }

}