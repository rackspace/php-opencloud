<?php

namespace OpenCloud\Network;

use OpenCloud\Common\PersistentObject;

use OpenCloud\Network\Service;

class Port extends PersistentObject {
    protected static
        $json_name = "port",
        $url_resource = "ports",
        $required_properties = array("network_id");

    protected
        $admin_state_up,
        $device_id,
        $device_owner,
        $fixed_ips,
        $id,
        $mac_address,
        $name,
        $network_id,
        $security_groups,
        $status;

    public function __construct(Service $service, $id) {
        parent::__construct($service, $id);
    }

}
