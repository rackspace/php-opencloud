<?php


namespace OpenCloud\Identity\Resource;

class Role 
{
    private $id;
    private $name;
    private $description;

    protected static $url_resource = 'OS-KSADM/roles';
    protected static $json_name    = 'role';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

}