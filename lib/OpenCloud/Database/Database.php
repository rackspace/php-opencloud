<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Database;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;

/**
 * This class represents a Database in the Rackspace "Red Dwarf"
 * database-as-a-service product.
 */
class Database extends PersistentObject
{

    public $name;

    /**
     * Creates a new database object
     *
     * Unlike other objects (Servers, DataObjects, etc.), passing a database
     * name to the constructor does *not* pull information from the database.
     * For example, if you pass an ID to the `Server()` constructor, it will
     * attempt to retrieve the information on that server from the service,
     * and will return an error if it is not found. However, the Cloud
     * Databases service does not permit retrieval of information on
     * individual databases (only via Collection), and thus passing in a
     * name via the `$info` parameter only creates an in-memory object that
     * is not necessarily tied to an actual database.
     *
     * @param Instance $instance the parent DbService\Instance of the database
     * @param mixed $info if an array or object, treated as properties to set;
     *      if a string, treated as the database name
     * @return void
     * @throws DatabaseNameError if `$info` is not a string, object, or array
     */
    public function __construct(Instance $instance, $info = null)
    {
        $this->setParent($instance);
        // Catering for laziness
        if (is_string($info)) {
            $info = array('name' => $info);
        }
        return parent::__construct($instance->getService(), $info);
    }
    
    /**
     * Returns name of this database. Because it's so important (i.e. as an
     * identifier), it will throw an error if not set/empty.
     * 
     * @return type
     * @throws Exceptions\DatabaseNameError
     */
    public function getName()
    {
        if (empty($this->name)) {
            throw new Exceptions\DatabaseNameError(
                Lang::translate('The database does not have a Url yet')
            );
        }
        return $this->name;
    }
    
    /**
     * Returns the Url of the Database
     *
     * @api
     * @param string $subresource Not used
     * @return string
     */
    public function url($subresource = '', $params = array())
    {
        return stripslashes($this->getParent()->url('databases')) . '/' . $this->getName();
    }

    /**
     * Returns the Instance of the database
     *
     * @return Instance
     */
    public function instance()
    {
        return $this->getParent();
    }

    /**
     * Creates a new database
     *
     * @api
     * @param array $params array of attributes to set prior to Create
     * @return \OpenCloud\HttpResponse
     */
    public function create($params = array())
    {
        // target the /databases subresource
        $url = $this->getParent()->url('databases');

        if (isset($params['name'])) {
        	$this->name = $params['name'];
        }

        $json = json_encode($this->createJson($params));

        $this->checkJsonError();

        // POST it off
        $response = $this->getParent()->getService()->request($url, 'POST', array(), $json);

        // check the response code
        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() != 202) {
        	throw new Exceptions\DatabaseCreateError(sprintf(
                Lang::translate('Error creating database [%s], status [%d] response [%s]'),
        		$this->name,
                $response->HttpStatus(),
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd

        // refresh and return
        return $response;
    }

    /**
     * Updates an existing database
     *
     * @param array $params ignored
     * @throws DatabaseUpdateError always; updates are not permitted
     * @return void
     */
    public function update($params = array())
    {
    	return $this->noUpdate();
    }

    /**
     * Deletes a database
     *
     * @api
     * @return \OpenCloud\HttpResponseb
     */
    public function delete()
    {
    	$response = $this->getParent()->getService()->request($this->url(), 'DELETE');
        
        // @codeCoverageIgnoreStart
    	if ($response->HttpStatus() != 202) {
    		throw new Exceptions\DatabaseDeleteError(sprintf(
                Lang::translate('Error deleting database [%s], status [%d] response [%s]'),
    			$this->name,
    			$response->HttpStatus(),
    			$response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
        
    	return $response;
    }

    /**
     * Returns the JSON object for creating the database
     */
    protected function createJson(array $params = array())
    {
        $database = (object) array_merge(array('name' => $this->getName(), $params));

        return (object) array(
            'databases' => array($database)
        );
    }

}
