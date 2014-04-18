<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Compute\Resource;

use OpenCloud\Common\Exceptions\InvalidArgumentError;
use OpenCloud\Common\PersistentObject;
use OpenCloud\Compute\Exception\KeyPairException;

class KeyPair extends PersistentObject
{
    private $name;
    private $fingerprint;
    private $privateKey;
    private $publicKey;
    private $userId;

    public $aliases = array(
        'private_key' => 'privateKey',
        'public_key'  => 'publicKey',
        'user_id'     => 'userId'
    );

    protected static $url_resource = 'os-keypairs';
    protected static $json_name = 'keypair';
    protected static $json_collection_element = 'keypair';

    public function setName($name)
    {
        if (preg_match('#[^\w\d\s-_]#', $name) || strlen($name) > 255) {
            throw new InvalidArgumentError(sprintf(
                'The key name may not exceed 255 characters. It can contain the'
                . ' following characters: alphanumeric, spaces, dashes and'
                . ' underscores. You provided [%s].',
                $name
            ));
        }
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function createJson()
    {
        $object = (object) array(
            'name' => $this->getName()
        );
        if (null !== ($key = $this->getPublicKey())) {
            $object->public_key = $key;
        }

        return (object) array('keypair' => $object);
    }

    public function create($params = array())
    {
        $this->setPublicKey(null);

        return parent::create($params);
    }

    /**
     * Upload an existing public key to a new keypair.
     *
     * @param  array $options
     * @return type
     * @throws KeyPairException
     */
    public function upload(array $options = array())
    {
        if (isset($options['path'])) {
            if (!file_exists($options['path'])) {
                throw new KeyPairException('%s does not exist.');
            }
            $contents = file_get_contents($options['path']);
            $this->setPublicKey($contents);
        } elseif (isset($options['data'])) {
            $this->setPublicKey($options['data']);
        } elseif (!$this->getPublicKey()) {
            throw new KeyPairException(
                'In order to upload a keypair, the public key must be set.'
            );
        }

        return parent::create();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }

    public function primaryKeyField()
    {
        return 'name';
    }
}
