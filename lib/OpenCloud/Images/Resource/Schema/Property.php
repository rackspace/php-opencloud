<?php

namespace OpenCloud\Images\Resource\Schema;

class Property 
{
    protected $name;
    protected $description;
    protected $type;
    protected $enum;
    protected $pattern;

    protected $items;

    protected $value;

    public static function factory($name, array $data = array())
    {
        $property = new self();

        $property->setName($name);
        $property->setDescription($data['description']);
        $property->setType($data['type']);
        $property->setEnum($data['enum']);
        $property->setPattern($data['pattern']);

        if (isset($data['items'])) {
            // handle nested arrays
            $property->setItems($data['items']);
        }

        if (isset($data['properties'])) {

        }

        return $property;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setEnum($enum)
    {
        $this->enum = $enum;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setItems($items)
    {

    }

    public function validate()
    {
        // deal with enumerated types
        if (!empty($this->enum)) {
            return in_array($this->value, $this->enum);
        }

        // handle patterns
        if ($this->pattern) {
            return preg_match($this->pattern, $this->value);
        }

        // handle type
        if ($this->type) {
            return $this->type === gettype($this->value);
        }

        return true;
    }
}