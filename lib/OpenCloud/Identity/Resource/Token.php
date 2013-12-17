<?php

namespace OpenCloud\Identity\Resource;

use OpenCloud\Common\PersistentObject;

class Token extends PersistentObject
{
    private $id;
    private $expires;

    protected static $url_resource = 'tokens';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function hasExpired()
    {
        return time() >= $this->expires;
    }

}