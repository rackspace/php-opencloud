<?php
/**
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\LoadBalancer\Resources;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Common\Lang;

/**
 * SubResource is an abstract class that handles subresources of a
 * LoadBalancer object; for example, the
 * `/loadbalancers/{id}/errorpage`. Since most of the subresources are
 * handled in a similar manner, this consolidates the functions.
 *
 * There are really four pieces of data that define a subresource:
 * * `$url_resource` - the actual name of the url component
 * * `$json_name` - the name of the JSON object holding the data
 * * `$json_collection_name` - if the collection is not simply
 *   `$json_name . 's'`, this defines the collectio name.
 * * `$json_collection_element` - if the object in a collection is not
 *   anonymous, this defines the name of the element holding the object.
 * Of these, only the `$json_name` and `$url_resource` are required.
 */
abstract class SubResource extends PersistentObject 
{
    
    public function initialRefresh()
    {
        if (isset($this->id)) {
            $this->refresh();
        } else {
            $this->refresh(null, $this->getParent()->url($this->resourceName()));
        }
    }

    /**
     * returns the URL of the SubResource
     *
     * @api
     * @param string $subresource the subresource of the parent
     * @param array $qstr an array of key/value pairs to be converted to
     *  query string parameters for the subresource
     * @return string
     */
    public function Url($subresource = null, $qstr = array()) 
    {
        return $this->getParent()->Url($this->ResourceName());
    }

    /**
     * returns the JSON document's object for creating the subresource
     *
     * The value `$_create_keys` should be an array of names of data items
     * that can be used in the creation of the object.
     *
     * @return \stdClass;
     */
    protected function CreateJson() 
    {
        $object = new \stdClass();
        $top = $this->JsonName();
        if ($top) {
            $object->$top = new \stdClass();
            foreach ($this->_create_keys as $item) {
                $object->$top->$item = $this->$item;
            }
        } else {
            foreach ($this->_create_keys as $item) {
                $object->$item = $this->$item;
            }
        }
        return $object;
    }

    /**
     * returns the JSON for the update (same as create)
     *
     * For these subresources, the update JSON is the same as the Create JSON
     * @return \stdClass
     */
    protected function UpdateJson($params = array()) 
    {
        return $this->CreateJson();
    }

    /**
     * returns a (default) name of the object
     *
     * The name is constructed by the object class and the object's ID.
     *
     * @api
     * @return string
     */
    public function Name() 
    {
        return sprintf(
            Lang::translate('%s-%s'),
            get_class($this), 
            $this->Parent()->Id()
        );
    }
}
