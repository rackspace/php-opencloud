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
use OpenCloud\Smoke\Enum;
use OpenCloud\Common\Exceptions\CdnNotAvailableError;

/**
 * Description of ObjectStore
 * 
 * @link 
 */
class ObjectStore extends AbstractUnit implements UnitInterface
{
    
    const OBJECT_NAME = 'TestObject';
    
    /**
     * {@inheritDoc}
     */
    public function setupService()
    {
        return $this->getConnection()->objectStore('cloudFiles', Utils::getRegion());
    }
    
    /**
     * {@inheritDoc}
     */
    public function main()
    {
        // Container
        $this->step('Create Container');
        $container = $this->getService()->container();
        $container->create(array(
            'name' => $this->prepend('0')
        ));
        
        // Objects
        $this->step('Create Object from this file');
        $object = $container->dataObject();
        $object->create(array(
                'name'         => $this->prepend(self::OBJECT_NAME),
                'content_type' => 'text/plain'
            ), 
            __FILE__
        );
        
        // CDN info
        $this->step('Publish Container to CDN');
        $container->publishToCDN(600); // 600-second TTL
        
        $this->step('CDN info');
        $this->stepInfo('CDN URL:              %s', $container->CDNUrl());
        $this->stepInfo('Public URL:           %s', $container->publicURL());
        $this->stepInfo('Object Public URL:    %s', $object->publicURL());
        $this->stepInfo('Object SSL URL:       %s', $object->publicURL('SSL'));
        $this->stepInfo('Object Streaming URL: %s', $object->publicURL('Streaming'));
        
        // Can we access it?
        $this->step('Verify Object PublicURL (CDN)');
        $url = $object->publicURL();
        $exec = exec("curl -s -I $url | grep HTTP");
        $this->stepInfo($exec);
        
        // Copy
        $this->step('Copy Object');
        $target = $container->dataObject();
        $target->name = $this->prepend(self::OBJECT_NAME . '_COPY');
        $object->copy($target);
        
        // List containers
        $this->step('List all containers');
        $containers = $this->getService()->containerList();
        $i = 0;
        while (($container = $containers->next()) && $i <= Enum::DISPLAY_ITER_LIMIT) {
            
            $step = $this->stepInfo('Container: %s', $container->name);
            
            // List this container's objects
            $objects = $container->objectList();
            while ($object = $objects->Next()) {
                $step->stepInfo('Object: %s', $object->name);
            }
            
            $i++;
        }        
    }
    
    /**
     * {@inheritDoc}
     */
    public function teardown()
    {
        $containers = $this->getService()->containerList(array(
            'prefix' => Enum::GLOBAL_PREFIX
        ));
        
        $this->step('Teardown');
        
        while ($container = $containers->next()) {
            // Disable CDN and delete object
            $this->stepInfo('Disable Container CDN');
            try {
                $container->disableCDN();
            } catch (CdnNotAvailableError $e) {}
            
            $step = $this->stepInfo('Delete objects');
            $objects = $container->objectList();
            if ($objects->count()) {
                while ($object = $objects->next()) {
                    $step->stepInfo('Deleting: %s', $object->name);
                    $object->delete();
                }
            }

            $this->stepInfo('Delete Container: %s', $container->name);
            $container->delete();
        }
    }
}