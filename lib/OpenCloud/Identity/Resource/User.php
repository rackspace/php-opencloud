<?php

namespace OpenCloud\Identity\Resource;

use OpenCloud\Common\Collection\ResourceIterator;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\PersistentObject;

class User extends PersistentObject
{
    private $defaultRegion;
    private $domainId;
    private $id;
    private $username;
    private $email;
    private $enabled;

    protected $createKeys = array('username', 'email', 'enabled');
    protected $updateKeys = array('username', 'email', 'enabled', 'RAX-AUTH:defaultRegion', 'RAX-AUTH:domainId', 'id');

    protected $aliases = array(
        'RAX-AUTH:defaultRegion' => 'defaultRegion',
        'RAX-AUTH:domainId'      => 'domainId'
    );

    protected static $url_resource = 'users';
    protected static $json_name    = 'user';

    public function setDefaultRegion($region)
    {
        $this->defaultRegion = $region;
    }

    public function getDefaultRegion()
    {
        return $this->defaultRegion;
    }

    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
    }

    public function getDomainId()
    {
        return $this->domainId;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function isEnabled()
    {
        return $this->enabled === true;
    }

    public function primaryKeyField()
    {
        return 'id';
    }

    public function updateJson($params = array())
    {
        $array = array();
        foreach ($this->updateKeys as $key) {
            if (isset($this->$key)) {
                $array[$key] = $this->$key;
            }
        }
        return json_encode((object) array('user' => $array));
    }

    public function updatePassword($newPassword)
    {
        $array = array(
            'username' => $this->username,
            'OS-KSADM:password' => $newPassword
        );

        $json = json_encode((object) array('user' => $array));

        return $this->getClient()->post($this->getUrl(), self::getJsonHeader(), $json);
    }

    public function getOtherCredentials()
    {
        $url = $this->getUrl();
        $url->addPath('OS-KSADM')->addPath('credentials');

        $response = $this->getClient()->get($url)->send();

        if ($body = Formatter::decode($response)) {
            return isset($body->credentials) ? $body->credentials : $body;
        }
    }

    public function getApiKey()
    {
        $url = $this->getUrl();
        $url->addPath('OS-KSADM')->addPath('credentials')->addPath('RAX-KSKEY:apiKeyCredentials');

        $response = $this->getClient()->get($url)->send();

        if ($body = Formatter::decode($response)) {
            return isset($body->{'RAX-KSKEY:apiKeyCredentials'}->apiKey)
                ? $body->{'RAX-KSKEY:apiKeyCredentials'}->apiKey
                : $body;
        }
    }

    public function resetApiKey($newApiKey)
    {
        $url = $this->getUrl();
        $url->addPath('OS-KSADM')
            ->addPath('credentials')
            ->addPath('RAX-KSKEY:apiKeyCredentials')
            ->addPath('RAX-AUTH')
            ->addPath('reset');

        $json = json_encode((object) array(
            "RAX-KSKEY:apiKeyCredentials" => (object) array(
                "username" => $this->username,
                "apiKey"   => $newApiKey
            )
        ));

        return $this->getClient()->post($url, self::getJsonHeader(), $json)->send();
    }

    public function addRole($roleId)
    {
        $url = $this->getUrl();
        $url->addPath('roles')->addPath('OS-KSADM')->addPath($roleId);

        return $this->getClient()->put($url)->send();
    }

    public function removeRole($roleId)
    {
        $url = $this->getUrl();
        $url->addPath('roles')->addPath('OS-KSADM')->addPath($roleId);

        return $this->getClient()->delete($url)->send();
    }

    public function getRoles()
    {
        $url = $this->getUrl();
        $url->addPath('roles');

        $response = $this->getClient()->get($url)->send();

        if ($body = Formatter::decode($response)) {
            return ResourceIterator::factory($this, array(), $body->roles);
        }
    }

}