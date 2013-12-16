<?php

namespace OpenCloud\Identity;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Url;
use OpenCloud\Common\Service\ServiceInterface;

class Service implements ServiceInterface
{
    private $client;
    private $endpoint;

    public static function factory(ClientInterface $client)
    {
        $identity = new self();
        $identity->setClient($client);
        $identity->setEndpoint($client->getAuthUrl());

        return $identity;
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getUrl($path = null)
    {
        $url = clone $this->endpoint;
        if ($path) {
            $url->addPath($path);
        }
        return $url;
    }

    public function getUsers()
    {
        return $this->getClient()->get($this->getUrl('users'))->send();
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

        $response = $this->getClient()->get($url)->send();
    }

    public function createUser(array $params)
    {

    }

    public function getRoles()
    {

    }

    public function getRole()
    {

    }

    public function generateToken()
    {

    }

    public function revokeToken()
    {

    }

    public function getTenants()
    {

    }

}