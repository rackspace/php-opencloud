<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Smoke\Unit;
use Guzzle\Http\Exception\ClientErrorResponseException;

class CDN extends AbstractUnit implements UnitInterface
{
    public function setupService()
    {
        $service = $this->getConnection()->cdnService();
        // TODO: Remove shim below to replace prod with preview endpoint
        $service->getEndpoint()->getPublicUrl()->setHost('preview.cdn.api.rackspacecloud.com');
        return $service;
        
    }

    public function main()
    {
        $this->step('List flavors');
        $flavors = $this->getService()->listFlavors();
        $this->stepInfo('%-40s | %s', 'Flavor ID', 'Number of providers');
        $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        foreach ($flavors as $flavor) {
            $this->stepInfo('%-40s | %d', $flavor->getId(), count($flavor->getProviders()));
        }

        $this->step('Create service');
        $createdService = $this->getService()->createService(array(
            'name'     => 'php-opencloud.com',
            'domains'  => array(
                array( 'domain' => 'php-opencloud.com' ),
                array( 'domain' => 'www.php-opencloud.com' )
            ),
            'origins'  => array(
                array( 'origin' => 'origin.php-opencloud.com' )
            ),
            'flavorId' => 'cdn'
        ));
        $this->stepInfo('Service name: ' . $createdService->getName());

        $this->step('List services');
        $services = $this->getService()->listServices();
        $this->stepInfo('%-40s | %s', 'Service Name', 'Number of domains');
        $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        foreach ($services as $service) {
            $this->stepInfo('%-40s | %d', $service->getName(), count($service->getDomains()));
        }

        $this->step('Get service');
        $service = $this->getService()->getService('php-opencloud.com');
        $this->stepInfo('Service name: ' . $service->getName());
        $this->stepInfo('Status: ' . $service->getStatus());
        $this->stepInfo('Origin: ' . $service->getOrigins()[0]->origin);

        $this->step('Update service');
        $service->update(array(
            'origins' => array(
                array( 'origin' => 'updated-origin.php-opencloud.com' )
            )
        ));

        $this->step('Purge ALL cached service assets');
        $service->purgeAssets();

        $this->step('Delete service');
        $createdService->delete();
    }

    public function teardown()
    {
    }
}