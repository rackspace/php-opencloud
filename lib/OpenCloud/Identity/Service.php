<?php

namespace OpenCloud\Identity;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Url;
use OpenCloud\Common\Collection\ResourceIterator;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Service\AbstractService;

class Service extends AbstractService
{

    public static function factory(ClientInterface $client)
    {
        $identity = new self();
        $identity->setClient($client);
        $identity->setEndpoint($client->getAuthUrl());

        return $identity;
    }

    public function getUrl($path = null)
    {
        $url = $this->getEndpoint();

        if ($path) {
            $url->addPath($path);
        }
        return $url;
    }

    public function getUsers()
    {
        $response = $this->getClient()->get($this->getUrl('users'))->send();

        if ($body = Formatter::decode($response)) {
            return ResourceIterator::factory($this, array(), $response->users);
        }
    }

    public function getUser($search, $mode = 'name')
    {
        $url = $this->getUrl();

        switch ($mode) {
            default:
            case 'name':
                $url->setQuery(array('name' => $search));
                break;
            case 'userId':
                $url->addPath($search);
                break;
            case 'email':
                $url->setQuery(array('email' => $search));
                break;
        }

        return $this->resource('User')->refreshFromLocationUrl($url);
    }

    public function populateUserFromCatalog($data)
    {
        return $this->resource('User', $data);
    }

    public function createUser(array $params)
    {
        return $this->resource('User')->create($params);
    }

    public function getRoles()
    {
        // No API documentation
    }

    public function getRole($roleId)
    {
        return $this->resource('Role', $roleId);
    }

    public function generateToken($json, array $headers = array())
    {
        $url = $this->getUrl();
        $url->addPath('tokens');

        $headers += self::getJsonHeader();

        return $this->getClient()->post($url, $headers, $json)->send();
    }

    public function revokeToken($tokenId)
    {
        return $this->resource('Token')->setId($tokenId)->delete();
    }

    public function getTenants()
    {
        $url = $this->getUrl();
        $url->addPath('tenants');

        $response = $this->getClient()->get($url)->send();

        if ($body = Formatter::decode($response)) {
            return ResourceIterator::factory($this, array(), $body->tenants);
        }
    }

}