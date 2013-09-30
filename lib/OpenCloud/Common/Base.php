<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common;

use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions\JsonError;
use OpenCloud\Common\Exceptions\UrlError;

/**
 * The root class for all other objects used or defined by this SDK.
 *
 * It contains common code for error handling as well as service functions that
 * are useful. Because it is an abstract class, it cannot be called directly,
 * and it has no publicly-visible properties.
 */
abstract class Base
{

    private $http_headers = array();
    private $_errors = array();
    
    private $properties = array();

    /**
     * Debug status.
     *
     * @var    LoggerInterface
     * @access private
     */
    private $logger;

    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);

        // Get property - convert from camel case to underscore
        $property = lcfirst(substr($method, 3));

        // Do getter
        if ($prefix == 'get') {
            return $this->getProperty($property);
        }

        // Do setter
        if ($prefix == 'set') {
            return $this->setProperty($property, $args[0]);
        }
    }
        
    /**
     * Set value in data array.
     * 
     * @access public
     * @param mixed $name
     * @param mixed $value
     * @return void
     */
    protected function setProperty($property, $value)
    { 
        // We can set a property under three conditions:
        // 1. If it has a concret setter: setProperty()
        // 2. If has already been defined
        // 3. If the property name's prefix is in an approved list

        $setter = 'set' . $this->toCamel($property);
        if (method_exists($this, $setter)) {
            
            return call_user_func(array($this, $setter), $value);
            
        } elseif (false !== ($propertyVal = $this->propertyExists($property))) { 
            
            // Are we setting a public or private property?
            if ($this->isAccessible($propertyVal)) {
                $this->$propertyVal = $value;
            } else {
                $this->properties[$propertyVal] = $value;
            }
            return $this;

        } else {
            $this->getLogger()->warning(
                'Attempted to set {property} with value {value}, but the'
                . ' property has not been defined. Please define first.',
                array(
                    'property' => $property,
                    'value'    => print_r($value, true)
                )
            );
        }
    }
    
    private function propertyExists($property, $allowRetry = true)
    {
        if (!property_exists($this, $property) && !$this->checkAttributePrefix($property)) {
            // Convert to under_score and retry
            if ($allowRetry) {
                return $this->propertyExists($this->toUnderscores($property), false);
            } else {
                $property = false;
            }
        }

        return $property;
    }
    
    function toCamel($string, $capitalise = true) 
    {
        if ($capitalise) {
          $string[0] = strtoupper($string[0]);
        }
        return preg_replace_callback('/_([a-z])/', function($char) {
            return strtoupper($char[1]);
        }, $string);
    }
    
    function toUnderscores($string) 
    {
        $string[0] = strtolower($string[0]);
        return preg_replace_callback('/([A-Z])/', function($char) {
            return "_" . strtolower($char[1]);
        }, $string);
    }
    
    private function isAccessible($property)
    {
        return array_key_exists($property, get_object_vars($this));
    }
    
    /**
     * Checks the attribute $property and only permits it if the prefix is
     * in the specified $prefixes array
     *
     * This is to support extension namespaces in some services.
     *
     * @param string $property the name of the attribute
     * @param array $prefixes a list of prefixes
     * @return boolean TRUE if valid; FALSE if not
     */
    private function checkAttributePrefix($property)
    {
        if (!method_exists($this, 'getService')) {
            return false;
        }
        $prefix = strstr($property, ':', true);
        return in_array($prefix, $this->getService()->namespaces());
    }
    
    /**
     * Grab value out of data array.
     * 
     * @access public
     * @param mixed $name
     * @return void
     */
    protected function getProperty($property)
    {
        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        } elseif (array_key_exists($this->toUnderscores($property), $this->properties)) {
            return $this->properties[$this->toUnderscores($property)];  
        } elseif (false !== ($propertyVal = $this->propertyExists($property))) {
            $method = 'get' . ucfirst($property);
            if ($this->isAccessible($propertyVal)) {
                return $this->$propertyVal;
            } elseif (method_exists($this, $method)) {
                return call_user_func(array($this, $method));
            }
        }
        
        return null;
    }
    
    /**
     * Sets the Logger object.
     * 
     * @param \OpenCloud\Common\Log\LoggerInterface $logger
     */
    public function setLogger(Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Returns the Logger object.
     * 
     * @return \OpenCloud\Common\Log\AbstractLogger
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->setLogger(new Log\Logger);
        }
        return $this->logger;
    }

    /**
     * Returns the URL of the service/object
     *
     * The assumption is that nearly all objects will have a URL; at this
     * base level, it simply throws an exception to enforce the idea that
     * subclasses need to define this method.
     *
     * @throws UrlError
     */
    public function url($subresource = '')
    {
        throw new UrlError(Lang::translate(
            'URL method must be overridden in class definition'
        ));
    }

/**
     * Populates the current object based on an unknown data type.
     * 
     * @param  array|object|string|integer $info
     * @throws Exceptions\InvalidArgumentError
     */
    public function populate($info, $setObjects = true)
    {
        if (is_string($info) || is_integer($info)) {
            
            $this->setProperty($this->primaryKeyField(), $info);
            $this->refresh($info);
            
        } elseif (is_object($info) || is_array($info)) {

            foreach ($info as $key => $value) {
                
                if ($key == 'metadata' || $key == 'meta') {
                    
                    // Try retrieving existing value
                    if (null === ($metadata = $this->getProperty($key))) {
                        // If none exists, create new object
                        $metadata = new Metadata;
                    }
                    
                    // Set values for metadata
                    $metadata->setArray($value);
                    
                    // Set object property
                    $this->setProperty($key, $metadata);
                    
                } elseif (!empty($this->associatedResources[$key]) && $setObjects === true) {
                    
                    // Associated resource
                    try {
                        $resource = $this->getService()->resource($this->associatedResources[$key], $value);
                        $resource->setParent($this);
                        $this->setProperty($key, $resource);
                    } catch (Exception\ServiceException $e) {}
   
                } elseif (!empty($this->associatedCollections[$key]) && $setObjects === true) {
                    
                    // Associated collection
                    try {
                        //$collection = $this->getService()->resourceList(
                        //    $this->associatedCollections[$key], null, $this
                        //);
                        $collection = new Collection(
                            $this->getService(), 
                            $this->associatedCollections[$key], 
                            $value
                        );
                        $this->setProperty($key, $collection); 
                    } catch (Exception\ServiceException $e) {}
                    
                } elseif (!empty($this->aliases[$key])) {

                    // Sometimes we might want to preserve camelCase
                    // or covert `rax-bandwidth:bandwidth` to `raxBandwidth`
                    $this->setProperty($this->aliases[$key], $value);
                    
                } else {
                    
                    // Normal key/value pair
                    $this->setProperty($key, $value);
                } 
            }
        } elseif (null !== $info) {
            throw new Exceptions\InvalidArgumentError(sprintf(
                Lang::translate('Argument for [%s] must be string or object'), 
                get_class()
            ));
        }
    }
        
    /**
     * Converts an array of key/value pairs into a single query string
     *
     * For example, array('A'=>1,'B'=>2) would become 'A=1&B=2'.
     *
     * @param array $arr array of key/value pairs
     * @return string
     */
    public function makeQueryString($array)
    {
        $queryString = '';

        foreach($array as $key => $value) {
            if ($queryString) {
                $queryString .= '&';
            }
            $queryString .= urlencode($key) . '=' . urlencode($this->to_string($value));
        }

        return $queryString;
    }

    /**
     * Checks the most recent JSON operation for errors
     *
     * This function should be called after any `json_*()` function call.
     * This ensures that nasty JSON errors are detected and the proper
     * exception thrown.
     *
     * Example:
     *   `$obj = json_decode($string);`
     *   `if (check_json_error()) do something ...`
     *
     * @return boolean TRUE if an error occurred, FALSE if none
     * @throws JsonError
     * 
     * @codeCoverageIgnore
     */
    public static function checkJsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return;
            case JSON_ERROR_DEPTH:
                $jsonError = 'JSON error: The maximum stack depth has been exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $jsonError = 'JSON error: Invalid or malformed JSON';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $jsonError = 'JSON error: Control character error, possibly incorrectly encoded';
                break;
            case JSON_ERROR_SYNTAX:
                $jsonError = 'JSON error: Syntax error';
                break;
            case JSON_ERROR_UTF8:
                $jsonError = 'JSON error: Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $jsonError = 'Unexpected JSON error';
                break;
        }
        
        if (isset($jsonError)) {
            throw new JsonError(Lang::translate($jsonError));
        }
    }

    /**
     * Converts a value to an HTTP-displayable string form
     *
     * @param mixed $x a value to convert
     * @return string
     */
    private function to_string($x)
    {
        if (is_bool($x) && $x) {
            return 'True';
        } elseif (is_bool($x)) {
            return 'False';
        } else {
            return (string) $x;
        }
    }
    
}
