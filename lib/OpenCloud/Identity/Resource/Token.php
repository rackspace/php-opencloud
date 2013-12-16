<?php

namespace OpenCloud\Identity\Resource;

class Token 
{
    private $id;
    private $expires;

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