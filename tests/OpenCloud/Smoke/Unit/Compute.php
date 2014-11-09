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

use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\Compute\Constants\Network;
use OpenCloud\Smoke\Utils;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * Description of Compute
 *
 * @link
 */
class Compute extends AbstractUnit implements UnitInterface
{
    const NETWORK_NAME = 'FooNetwork';
    const VOLUME_NAME  = 'FooVolume';
    const VOLUME_SIZE  = 100;
    const SERVER_NAME  = 'FooServer';
    const SNAPSHOT_NAME = 'FooSnapshot';

    const FLAVOR = 'general1-2';
    const IMAGE  = "046832f9-4549-4b38-a903-11acecac8cb9";

    public function setupService()
    {
        return $this->getConnection()->computeService('cloudServersOpenStack', Utils::getRegion());
    }

    public function main()
    {
        // Flavors
        $this->step('List Flavors');
        $flavorList = $this->getService()->flavorList();
        $flavorList->sort('id');
        foreach ($flavorList as $flavor) {
            $this->stepInfo('%s: %sMB, ID: [%s]', $flavor->name, $flavor->ram, $flavor->id);
        }

        // Images
        $this->step('List Images');
        $imageList = $this->getService()->imageList();
        //$imageList->sort('name');
        foreach ($imageList as $image) {
            $this->stepInfo('%s; ID: [%s]; OS distro: [%s]', $image->name, $image->id, $image->metadata->os_distro);
        }
        
        // Create network
        $this->step('Create Network');
        $network = $this->getService()->network();
        try {
            $network->create(array(
                'label' => $this->prepend(self::NETWORK_NAME),
                'cidr'  => '192.168.0.0/24'
            ));
        } catch (ClientErrorResponseException $e) {
            $this->stepInfo('Failed to create network :(');
        }
        
        // List networks
        $this->step('List Networks');
        $networks = $this->getService()->networkList();
        //$networks->sort('label');
        foreach ($networks as $network) {
            $this->stepInfo('%s: %s (%s)', $network->id, $network->label, $network->cidr);
        }
        
        // Volumes
        $this->step('Connect to the VolumeService');
        $volumeService = $this->getConnection()->volumeService('cloudBlockStorage', Utils::getRegion());

        // Volume types
        $this->step('Volume Types');
        $volumeTypes = $volumeService->volumeTypeList();
        $volumeTypes->populateAll();

        foreach ($volumeTypes as $volumeType) {
            $this->stepInfo('%s - %s', $volumeType->id, $volumeType->name);
            // save the ID for later
            if (!isset($savedId)) {
                $savedId = $volumeType->id;
            }
        }

        // Create volume
        $this->step('Create a new Volume');
        $volume = $volumeService->volume();
        $volume->create(array(
            'display_name'        => $this->prepend(self::VOLUME_NAME),
            'display_description' => 'A sample volume for testing',
            'size'                => self::VOLUME_SIZE,
            'volume_type'         => $volumeService->volumeType($savedId)
        ));
        
        // List volumes
        $this->step('Listing volumes');
        $volumeList = $volumeService->volumeList();
        foreach ($volumeList as $volume1) {
            $this->stepInfo(
                'Volume: %s %s [%s] size=%d',
                $volume1->id,
                $volume1->display_name,
                $volume1->display_description,
                $volume1->size
            );
        }
        
        // Create server
        $this->step('Create Server');
        $server = $this->getService()->server();
        $server->addFile('/var/test1', 'TEST 1');
        $server->addFile('/var/test2', 'TEST 2');
        $server->create(array(
            'name'     => $this->prepend(self::SERVER_NAME . time()),
            'image'    => $this->getService()->image(self::IMAGE),
            'flavor'   => $this->getService()->flavor(self::FLAVOR),
            'networks' => array(
                $this->getService()->network(Network::RAX_PUBLIC),
                $this->getService()->network(Network::RAX_PRIVATE)
            ),
            "OS-DCF:diskConfig" => "AUTO"
        ));

        $adminPassword = $server->adminPass;
        $this->stepInfo('ADMIN PASSWORD = %s', $adminPassword);

        $this->step('Wait for Server create');
        $server->waitFor('ACTIVE', 600, $this->getWaiterCallback());

        if ($server->status() == 'ERROR') {
            $this->stepInfo("Server create failed with ERROR\n");
            return false;
        }

        // Rebuild
        $this->step('Rebuild the server');
        $server->rebuild(array(
            'adminPass' => $adminPassword,
            'image'     => $this->getService()->image(self::IMAGE)
        ));

        sleep(3);
        
        $this->step('Wait for Server rebuild');
        $server->waitFor('ACTIVE', 600, $this->getWaiterCallback());

        if ($server->status() == 'ERROR') {
            $this->stepInfo("Server rebuild failed with ERROR\n");
            return false;
        }

        sleep(3);
        
        // Attach volume
        $this->step('Attach the volume');
        $server->attachVolume($volume);
        $volume->waitFor('in-use', 300, $this->getWaiterCallback());

        // Update & reboot server
        $this->step('Update the server name');
        $server->update(array(
            'name' => $this->prepend(self::SERVER_NAME . time())
        ));
        $server->waitFor('ACTIVE', 300, $this->getWaiterCallback());

        $this->step('Reboot Server');
        $server->reboot();
        $server->waitFor('ACTIVE', 300, $this->getWaiterCallback());

        // List all servers
        $this->step('List Servers');
        $list = $this->getService()->serverList();
        //$list->sort('name');
        foreach ($list as $server1) {
            $this->stepInfo($server1->name);
        }
    }

    public function teardown()
    {
        $this->step('Teardown');
        $servers = $this->getService()->serverList();

        // Delete servers
        foreach ($servers as $server) {
            $attachments = $server->volumeAttachmentList();

            foreach ($attachments as $volumeAttachment) {
                if ($this->shouldDelete($volumeAttachment->name())) {
                    $this->stepInfo('Deleting attachment: %s', $volumeAttachment->name());
                    $volumeAttachment->delete();
                }
            }

            if ($this->shouldDelete($server->name)) {
                $this->stepInfo('Deleting %s', $server->id);
                $server->delete();
            }
        }

        // Delete networks
        $networks = $this->getService()->networkList();
        foreach ($networks as $network) {
            if (!in_array($network->id, array(Network::RAX_PRIVATE, Network::RAX_PUBLIC))) {
                $this->stepInfo('Deleting: %s %s', $network->id, $network->label);
                $network->delete();
            }
        }
    }
}
