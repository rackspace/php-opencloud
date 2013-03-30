<?php

namespace OpenCloud\LoadBalancer\Resources;

/**
 * sub-resource to manage SSL termination
 */
class SSLTermination extends SubResource {
	public
		$certificate,
		$enabled,
		$secureTrafficOnly,
		$privatekey,
		$intermediateCertificate,
		$securePort;
    protected static
    	$json_name = "sslTermination",
    	$url_resource = "ssltermination";
    protected
    	$_create_keys = array(
    		'certificate',
    		'enabled',
    		'secureTrafficOnly',
    		'privatekey',
    		'intermediateCertificate',
    		'securePort'
    	);
	public function Create($params=array()) { $this->Update($params); }
}