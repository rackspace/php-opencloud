<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

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
    const VOLUME_SIZE  = 103;
    const SERVER_NAME  = 'FooServer';
    const SNAPSHOT_NAME = 'FooSnapshot';
        
    /**
     * {@inheritDoc}
     */
    public function setupService()
    {
        return $this->getConnection()->computeService('cloudServersOpenStack', Utils::getRegion());
    }
    
    /**
     * {@inheritDoc}
     */
    public function main()
    {
        // Flavors
        $this->step('List Flavors');
        $flavorList = $this->getService()->flavorList();
        $flavorList->sort('id');
        while ($flavor = $flavorList->next()) {
            $this->stepInfo('%s: %sMB', $flavor->name, $flavor->ram);
        }

        // Images
        $this->step('List Images');
        $imageList = $this->getService()->ImageList();
        $imageList->sort('name');
        while ($image = $imageList->next()) {
            $this->stepInfo($image->name);
            // save a CentOS image for later
            if (!isset($centos) 
                && isset($image->metadata->os_distro) 
                && $image->metadata->os_distro == 'centos'
            ) {
                $centos = $image;
            }
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
        $networks->sort('label');
        while ($network = $networks->next()) {
            $this->stepInfo('%s: %s (%s)', $network->id, $network->label, $network->cidr);
        }
        
        // Volumes
        $this->step('Connect to the VolumeService');
        $volumeService = $this->getConnection()->volumeService('cloudBlockStorage', Utils::getRegion());

        // Volume types
        $this->step('Volume Types');
        $volumeTypes = $volumeService->volumeTypeList();
        while ($volumeType = $volumeTypes->next()) {
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
        while ($volume1 = $volumeList->next()) {
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
            'image'    => $centos,
            'flavor'   => $flavorList->first(),
            'networks' => array(
                $this->getService()->network(RAX_PUBLIC), 
                $this->getService()->network(RAX_PRIVATE)
            ),
            "OS-DCF:diskConfig" => "AUTO"
        ));
        $adminPassword = $server->adminPass;

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
            'image'     => $centos
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
        $list->sort('name');
        while ($server1 = $list->Next()) {
            $this->stepInfo($server1->name);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function teardown()
    {
        $this->step('Teardown');
        $servers = $this->getService()->serverList();
        while ($server = $servers->next()) {
            
            $attachments = $server->volumeAttachmentList();
            while ($volumeAttachment = $attachments->next()) {
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
        
        $networks = $this->getService()->networkList();
        while ($network = $networks->next()) {
            if (!in_array($network->id, array(RAX_PRIVATE, RAX_PUBLIC))) {
                $this->stepInfo('Deleting: %s %s', $network->id, $network->label);
                $network->delete();
            }
        }
    }
}