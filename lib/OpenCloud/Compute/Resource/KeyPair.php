<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Compute\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Compute\Exception\KeyPairException;

/**
 * Description of KeyPair
 * 
 * @link 
 */
class KeyPair extends PersistentObject
{
    
    protected $name;
    protected $fingerprint;
    protected $privateKey;
    protected $publicKey;
    protected $userId;
    
    public $aliases = array(
        'private_key' => 'privateKey',
        'public_key'  => 'publicKey',
        'user_id'     => 'userId'
    );
    
    protected static $url_resource = 'os-keypairs';
    protected static $json_name    = 'keypair';
    
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
        return $object;
    }
    
    /**
     * {@inheritDoc}
     */
    public function create($params = array())
    {
        $this->setPublicKey(null);
        return parent::create();
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
    
    /**
     * {@inheritDoc}
     */
    public function update($params = array())
    {
        return $this->noUpdate();
    }
    
    /**
     * {@inheritDoc}
     */
    public function primaryKeyField()
    {
        return 'name';
    }
    
}