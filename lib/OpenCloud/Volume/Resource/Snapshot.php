<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Volume\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;

/**
 * The Snapshot class represents a single block storage snapshot
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
    public $metadata;

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
