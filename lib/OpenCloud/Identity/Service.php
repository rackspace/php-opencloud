<?php

namespace OpenCloud\Identity;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Url;
use OpenCloud\Common\Collection\PaginatedIterator;
use OpenCloud\Common\Collection\ResourceIterator;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Common\Service\AbstractService;

class Service extends AbstractService
{

    public static function factory(ClientInterface $client)
    {
        $identity = new self();
        $identity->setClient($client);
        $identity->setEndpoint(clone $client->getAuthUrl());

        return $identity;
    }

    public function getUrl($path = null)
    {
        $url = clone $this->getEndpoint();

        if ($path) {
            $url->addPath($path);
        }

        return $url;
    }

    public function getUsers()
    {
        $response = $this->getClient()->get($this->getUrl('users'))->send();

        if ($body = Formatter::decode($response)) {
            return ResourceIterator::factory($this, array(
                'resourceClass'  => 'User',
                'key.collection' => 'users'
            ), $body->users);
        }
    }

    public function user($info = null)
    {
        return $this->resource('User', $info);
    }

    public function getUser($search, $mode = 'name')
    {
        $url = $this->getUrl('users');

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

        $user = $this->resource('User');
        $user->refreshFromLocationUrl($url);

        return $user;
    }

    public function createUser(array $params)
    {
        $user = $this->resource('User');
        $user->create($params);
        return $user;
    }

    public function getRoles()
    {
        return PaginatedIterator::factory($this, array(
            'resourceClass'  => 'Role',
            'baseUrl'        => $this->getUrl()->addPath('OS-KSADM')->addPath('roles'),
            'key.marker'     => 'id',
            'key.collection' => 'roles'
        ));
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
        $token = $this->resource('Token');
        $token->setId($tokenId);
        return $token->delete();
    }

    public function getTenants()
    {
        $url = $this->getUrl();
        $url->addPath('tenants');

        $response = $this->getClient()->get($url)->send();

        if ($body = Formatter::decode($response)) {
            return ResourceIterator::factory($this, array(
                'resourceClass'  => 'Tenant',
                'key.collection' => 'tenants'
            ), $body->tenants);
        }
    }

}