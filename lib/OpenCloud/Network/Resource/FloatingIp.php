<?php

namespace OpenCloud\Network\Resource;

use OpenCloud\Network\Service;

use OpenCloud\Common\PersistentObject;

class FloatingIp extends PersistentObject {
    protected static
        $json_name = "floatingip",
        $url_resource = "v2.0/floatingips",
        $required_properties = array();

    protected
        $id,
        $floating_network_id,
        $port_id;

    public function __construct(Service $service, $id)
    {
        parent::__construct($service, $id);
    }

    protected function createJson()
    {
        return array(
            'port_id'             => $this->port_id,
            'floating_network_id' => $this->floating_network_id
        );
    }

    public function getPort()
    {
        return $this->getService()->port($this->port_id);
    }

    public function disassociate()
    {
        $this->update(array('port_id' => null));
    }

    /**
     * @param $portOrPortId string|Port The Port instance or string port ID to associate this IP with.
     * @return $void
     */
    public function associate($portOrPortId)
    {
        $portId = is_string($portOrPortId) ? $portOrPortId : $portOrPortId->id;
        $this->update(array('port_id' => $portId));
    }

    public function updateJson($params = array())
    {
        return array(
            self::$json_name => array_merge(array(
                'port_id' => $this->port_id
            ), $params)
        );
    }

    public function name()
    {
        return 'floater';
    }
}
