<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Utils;
use OpenCloud\Smoke\Enum;
use OpenCloud\Common\Exceptions\CdnNotAvailableError;
use OpenCloud\Common\Constants\Size;
use OpenCloud\ObjectStore\Constants\UrlType;

/**
 * Description of ObjectStore
 * 
 * @link 
 */
class ObjectStore extends AbstractUnit implements UnitInterface
{
    
    const OBJECT_NAME  = 'TestObject.txt';
    const UPLOAD_COUNT = 50;
    const MASSIVE_FILE_PATH = '/tmp/massive.txt';
    
    /**
     * {@inheritDoc}
     */
    public function setupService()
    {
        return $this->getConnection()->objectStoreService('cloudFiles', Utils::getRegion());
    }

    private function createFiles($dir)
    {
        $content = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1000);
        for ($i = 1; $i <= 50; $i++) {
            $fh = fopen($dir . self::OBJECT_NAME . "_$i", 'c+');
            fwrite($fh, $content);
            fclose($fh);
        }
    }

    private function createMassiveFile()
    {
        $fh = fopen(self::MASSIVE_FILE_PATH, 'c+');
        $content = str_repeat('A', 1000);
        for ($i = 0; $i < (1024 * 1024 * 5) / 1000; $i++) {
            fwrite($fh, $content);
        }
        fclose($fh);
    }

    /**
     * {@inheritDoc}
     */
    public function main()
    {
        // Container
        $this->step('Create Container');
        $container = $this->getService()->createContainer($this->prepend(rand(1,99999)));

        // Upload normal file
        $this->step('Upload 1 file');
        $object = $container->uploadObject(self::OBJECT_NAME, str_repeat("never gonna give you up...\n", 1000), array(
            'Author' => 'Best singer ever!111'
        ));

        // Upload 50 objects
        $this->step('Upload ' . self::UPLOAD_COUNT . ' files');
        $dir = __DIR__ . '/../Resource/ObjectStore/';
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        if (count(scandir($dir)) == 2) {
            $this->createFiles($dir);
        }

        $files = array();
        for ($i = 1; $i <= 50; $i++) {
            $file = self::OBJECT_NAME . "_$i";
            $files[] = array('name' => $file, 'path' => __DIR__ . '/../Resource/ObjectStore/' . $file);
        }
        $container->uploadObjects($files);

        // Upload mahoosive file
        $this->step('Upload 5GB file using multipart');

        if (!file_exists(self::MASSIVE_FILE_PATH)) {
            $this->createMassiveFile();
        }

        $transfer = $container->setupObjectTransfer(array(
            'name' => self::OBJECT_NAME . '_massive',
            'path' => self::MASSIVE_FILE_PATH,
            'metadata' => array(
                'Subject' => 'Something uninteresting',
                'Author'  => 'John Doe'
            ),
            'partSize' => 1 * Size::GB,
            'concurrency' => 4
        ));
        // thunderbirds are go
        $transfer->upload();

        
        // CDN info
        $this->step('Publish Container to CDN');
        $container->enableCdn(600); // 600-second TTL
        
        $this->step('CDN info');
        $this->stepInfo('CDN URL:              %s', $container->getCdn()->getCdnUri());
        $this->stepInfo('Public URL:           %s', $container->getUrl());
        $this->stepInfo('Object Public URL:    %s', $object->getPublicUrl());
        $this->stepInfo('Object SSL URL:       %s', $object->getPublicUrl(UrlType::SSL));
        $this->stepInfo('Object Streaming URL: %s', $object->getPublicUrl(UrlType::STREAMING));
        
        // Can we access it?
        $this->step('Verify Object PublicURL (CDN)');
        $response = $this->getConnection()->get($object->getPublicUrl())->send();
        $this->stepInfo((string) $response);
        
        // Copy
        $this->step('Copy Object');
        $destination = sprintf('/%s/%s', $container->getName(), $this->prepend(self::OBJECT_NAME . '_COPY'));
        $object->copy($destination);
        
        // List containers
        $this->step('List all containers');
        $containers = $this->getService()->listContainers();
        $i = 0;
        while (($container = $containers->next()) && $i <= Enum::DISPLAY_ITER_LIMIT) {

            $step = $this->stepInfo('Container: %s', $container->getName());
            
            // List this container's objects
            $objects = $container->objectList();
            while ($object = $objects->next()) {
                $step->stepInfo('Object: %s', $object->getName());
            }
            
            $i++;
        }        
    }
    
    /**
     * {@inheritDoc}
     */
    public function teardown()
    {
        $containers = $this->getService()->listContainers(array(
            'prefix' => Enum::GLOBAL_PREFIX
        ));
        
        $this->step('Teardown');
        
        while ($container = $containers->next()) {
            // Disable CDN and delete object
            $this->stepInfo('Disable Container CDN');
            try {
                $container->disableCDN();
            } catch (CdnNotAvailableError $e) {}
            
            $step = $this->stepInfo('Delete objects');
            $objects = $container->objectList();
            if ($objects->count()) {
                while ($object = $objects->next()) {
                    $step->stepInfo('Deleting: %s', $object->getName());
                    $object->delete();
                }
            }

            $this->stepInfo('Delete Container: %s', $container->getName());
            $container->delete();
        }
    }
}