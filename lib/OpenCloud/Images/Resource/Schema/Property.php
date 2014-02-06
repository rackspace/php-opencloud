<?php

namespace OpenCloud\Images\Resource\Schema;

class Property 
{
    protected $name;
    protected $description;
    protected $type;
    protected $enum;
    protected $pattern;

    public function validateValue($value)
    {
        // deal with enumerated types
        if (!empty($this->enum)) {
            return in_array($value, $this->enum);
        }

        // handle patterns
        if ($this->pattern) {
            return preg_match($this->pattern, $value);
        }

        // handle type
        if ($this->type) {
            return $this->type === gettype($value);
        }

        return true;
    }
} 