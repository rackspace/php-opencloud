<?php

/**
 * The Rackspace Cloud Monitoring service.
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author  Jamie Hannaford <jamie@limetree.org>
 */

namespace OpenCloud\CloudMonitoring;

use OpenCloud\OpenStack;
use OpenCloud\Common\Service as AbstractService;

class Service extends AbstractService
{

    private $resources = array(
        'Account',
        'Agent',
        'Alarm',
        'Check',
        'CheckType',
        'Entity',
        'Metric',
        'Notification',
        'NotificationHistory',
        'NotificationPlan',
        'Zone'
    );

	public function __construct(
		OpenStack $connection,
        $serviceName,
        $serviceRegion,
        $urlType
    ) {
		parent::__construct(
            $connection,
            'rax:monitor',
            $serviceName,
            $serviceRegion,
            $urlType
        );
	}

    public function getResources()
    {
        return $this->resources;
    }

    public function resource($resourceName, $info = null)
    {
        $className = __NAMESPACE__ . '\\Resource\\' . ucfirst($resourceName);

        if (!class_exists($className)) {
            throw new Exception\ServiceException(sprintf(
                '%s resource does not exist, please try one of the following: %s',
                $resourceName,
                implode(', ', $this->getResources())
            ));
        }
        
        return new $className($this, $info);
    }

    public function Request(
        $url,
        $method = 'GET',
        array $headers = array(),
        $body = null
    ) {
        $headers['X-Auth-Token'] = $this->conn->Token();

        if ($tenant = $this->conn->Tenant()) {
            $headers['X-Auth-Project-Id'] = $tenant;
        }

        if (is_string($body)) {
            $headers['Content-type'] = 'application/json';
        }

        return $this->conn->Request($url, $method, $headers, $body);
    }

    public function CheckType()
    {
        return new Resource\CheckType($this);
    }

}