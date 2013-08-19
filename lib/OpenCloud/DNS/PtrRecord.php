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

use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;

/**
 * PTR records are used for reverse DNS
 *
 * The PtrRecord object is nearly identical with the Record object. However,
 * the PtrRecord is a child of the service, and not a child of a Domain.
 */
class PtrRecord extends Record 
{

    public $server;

    protected static $json_name = false;
    protected static $json_collection_name = 'records';
    protected static $url_resource = 'rdns';

    private $link_rel;
    private $link_href;

    /**
     * constructur ensures that the record type is PTR
     */
    public function __construct($parent, $info = null) 
    {
        $this->type = 'PTR';
        parent::__construct($parent, $info);
        if ($this->type != 'PTR') {
            throw new Exceptions\RecordTypeError(sprintf(
                Lang::translate('Invalid record type [%s], must be PTR'), 
                $this->type
            ));
        }
    }

    /**
     * specialized DNS PTR URL requires server service name and href
     */
    public function url($subresource = null, $params = array()) 
    {
        $subresource = $subresource ?: self::$url_resource;
        return $this->getParent()->url($subresource, $params);
    }

    /**
     * DNS PTR Create() method requires a server
     *
     * Generally called as `Create(array('server'=>$server))`
     */
    public function create($params = array()) 
    {
        $this->populate($params, false);
        $this->link_rel = $this->server->Service()->name();
        $this->link_href = $this->server->url();
        return parent::create();
    }

    /**
     * DNS PTR Update() method requires a server
     */
    public function update($params = array()) 
    {
        $this->populate($params, false);
        $this->link_rel = $this->server->Service()->Name();
        $this->link_href = $this->server->Url();
        return parent::update();
    }

    /**
     * DNS PTR Delete() method requires a server
     *
     * Note that delete will remove ALL PTR records associated with the device
     * unless you pass in the parameter ip={ip address}
     *
     */
    public function delete() 
    {
        $this->link_rel = $this->server->Service()->Name();
        $this->link_href = $this->server->Url();
        
        $params = array('href' => $this->link_href);
        if (!empty($this->data)) {
            $params['ip'] = $this->data;
        }
        
        $url = $this->url('rdns/' . $this->link_rel, $params);

        // perform the request
        $response = $this->getService()->request($url, 'DELETE');

        // return the AsyncResponse object
        return new AsyncResponse($this->getService(), $response->HttpBody());
    }

    /**
     * Specialized JSON for DNS PTR creates and updates
     */
    protected function createJson() 
    {
        return (object) array(
            'recordsList' => parent::createJson(),
            'link' => array(
                'href' => $this->link_href,
                'rel'  => $this->link_rel
            )
        );
    }

    /**
     * The Update() JSON requires a record ID
     */
    protected function updateJson($params = array()) 
    {
        $this->populate($params, false);
        $object = $this->createJson();
        $object->recordsList->records[0]->id = $this->id;
        return $object;
    }

}
