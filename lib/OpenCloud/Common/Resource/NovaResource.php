<?php

namespace OpenCloud\Common\Resource;

abstract class NovaResource extends PersistentResource
{
    /**
     * This method is used for many purposes, such as rebooting server, etc.
     *
     * @param $object
     * @return \Guzzle\Http\Message\Response
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function action($object)
    {
        if (!$this->getProperty($this->primaryKeyField())) {
            throw new \RuntimeException('A primary key is required');
        }

        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf('This method expects an object as its parameter'));
        }

        // convert the object to json
        $json = json_encode($object);
        $this->checkJsonError();

        // get the URL for the POST message
        $url = clone $this->getUrl();
        $url->addPath('action');

        // POST the message
        return $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
    }
} 