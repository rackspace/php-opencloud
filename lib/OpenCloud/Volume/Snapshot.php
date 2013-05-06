<?php
/**
 * Defines a block storage snapshot
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Volume;

use OpenCloud\AbstractClass\PersistentObject;
use OpenCloud\Base\Lang;
use OpenCloud\Base\Exceptions;

/**
 * The Snapshot class represents a single block storage snapshot
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Snapshot extends PersistentObject 
{

    public $id;
    public $display_name;
    public $display_description;
    public $volume_id;
    public $status;
    public $size;
    public $created_at;

    protected $force = false;

    protected static $json_name = 'snapshot';
    protected static $url_resource = 'snapshots';

    private $_create_keys = array(
        'display_name',
        'display_description',
        'volume_id',
        'force'
    );

    /**
     * updates are not permitted
     *
     * @throws OpenCloud\UpdateError always
     */
    public function Update($params = array()) 
    {
        throw new Exceptions\UpdateError(
            Lang::translate('VolumeType cannot be updated')
        );
    }

    /**
     * returns the display_name attribute
     *
     * @api
     * @return string
     */
    public function Name() 
    {
        return $this->display_name;
    }

    /**
     * returns the object for the Create() method's JSON
     *
     * @return stdClass
     */
    protected function CreateJson() 
    {
        $object = new \stdClass();

        $elem = $this->JsonName();
        $object->$elem = new \stdClass();
        
        foreach($this->_create_keys as $key) {
            $object->$elem->$key = $this->$key;
        }

        return $object;
    }

}
