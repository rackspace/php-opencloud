<?php
/**
 * Defines a volume attachment object
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.1
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Compute;

use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\PersistentObject;

/**
 * The VolumeAttachment class represents a volume that is attached
 * to a server.
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class VolumeAttachment extends PersistentObject 
{

    public $id;
    public $device;
    public $serverId;
    public $volumeId;

    public static $json_name = 'volumeAttachment';
    public static $url_resource = 'os-volume_attachments';

    private $_create_keys = array('volumeId', 'device');

    /**
     * updates are not permitted
     *
     * @throws OpenCloud\UpdateError always
     */
    public function Update($params = array()) 
    {
        throw new Exceptions\UpdateError(Lang::translate('Updates are not permitted'));
    }

    /**
     * returns a readable name for the attachment
     *
     * Since there is no 'name' attribute, we'll hardcode something
     *
     * @api
     * @return string
     */
    public function Name() 
    {
        $id = $this->volumeId ?: 'N/A';
        return sprintf('Attachment [%s]', $id);
    }

    /**
     * returns the JSON object for Create()
     *
     * @return stdClass
     */
    protected function CreateJson() 
    {
        $obj = new \stdClass();
        $elem = $this->JsonName();
        $obj->$elem = new \stdClass();

        // set the properties
        foreach($this->_create_keys as $key) {
            if ($this->$key) {
                $obj->$elem->$key = $this->$key;
            }
        }

        return $obj;
    }

}
