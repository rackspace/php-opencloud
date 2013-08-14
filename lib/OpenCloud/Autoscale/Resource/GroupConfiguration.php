<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale\Resource;

/**
 * This specifies the basic elements of the group. The Group Configuration contains:
 * 
 * - Group Name
 * - Group Cooldown (how long a group has to wait before you can scale again in seconds)
 * - Minimum and Maximum number of entities
 * 
 * @link https://github.com/rackerlabs/otter/blob/master/doc/getting_started.rst
 * @link http://docs.autoscale.apiary.io/
 */
class GroupConfiguration extends AbstractResource
{
    
    public $name;
    public $cooldown;
    public $minEntities;
    public $maxEntities;
    public $metadata;
    
    protected static $json_name = 'groupConfiguration';
    protected static $url_resource = 'config';
    
    public $createKeys = array(
        'name',
        'cooldown',
        'minEntities',
        'maxEntities'
    );
    
    /**
     * {@inheritDoc}
     */
    public function create($params = array())
    {
        return $this->noCreate();
    }
    
    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        return $this->noDelete();
    }
    
}