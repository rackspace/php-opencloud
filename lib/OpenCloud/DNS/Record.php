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

namespace OpenCloud\DNS;

/**
 * The Record class represents a single domain record
 *
 * This is also used for PTR records.
 */
class Record extends Object 
{

    public $ttl;
    public $updated;
    public $created;
    public $name;
    public $id;
    public $type;
    public $data;
    public $priority;
    public $comment;

    protected static $json_name = false;
    protected static $json_collection_name = 'records';
    protected static $url_resource = 'records';

    protected $parent;
    
    protected $updateKeys = array(
        'name',
        'ttl',
        'data',
        'priority',
        'comment'
    );
    
    protected $createKeys = array(
        'type',
        'name',
        'ttl',
        'data',
        'priority',
        'comment'
    );

    /**
     * create a new record object
     *
     * @param mixed $parent either the domain object or the DNS object (for PTR)
     * @param mixed $info ID or array/object of data for the object
     * @return void
     */
    public function __construct($parent, $info = null) 
    {
        $this->parent = $parent;
        
        if ($parent instanceof Service) {
            parent::__construct($parent, $info);
        } else {
            parent::__construct($parent->getService(), $info);
        }
    }

    /**
     * returns the parent domain
     *
     * @return Domain
     */
    public function Parent() 
    {
        return $this->parent;
    }

}
