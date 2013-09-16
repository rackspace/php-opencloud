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
        Utils::log('Connect to Cloud Files');
        
        // Container
        Utils::log('Create Container');
        $container = $this->getService()->container();
        $container->create(array(
            'name' => $this->prepend('0')
        ));
        
        // Objects
        Utils::log('Create Object from this file');
        $object = $container->dataObject();
        $object->create(array(
                'name'         => $this->prepend(self::OBJECT_NAME),
                'content_type' => 'text/plain'
            ), 
            __FILE__
        );
        
        // CDN info
        Utils::log('Publish Container to CDN');
        $container->publishToCDN(600); // 600-second TTL
        
        Utils::logf('CDN URL:              %s', $container->CDNUrl());
        Utils::logf('Public URL:           %s', $container->publicURL());
        Utils::logf('Object Public URL:    %s', $object->publicURL());
        Utils::logf('Object SSL URL:       %s', $object->publicURL('SSL'));
        Utils::logf('Object Streaming URL: %s', $object->publicURL('Streaming'));
        
        // Can we access it?
        Utils::log('Verify Object PublicURL (CDN)');
        $url = $object->publicURL();
        system("curl -s -I $url | grep HTTP");
        
        // Copy
        Utils::log('Copy Object');
        $target = $container->dataObject();
        $target->name = $this->prepend(self::OBJECT_NAME . '_COPY');
        $object->copy($target);
        
        // List containers
        Utils::log('List all containers');
        $containers = $this->getService()->containerList();
        $i = 0;
        while (($container = $containers->next()) && $i <= Enum::DISPLAY_ITER_LIMIT) {
            Utils::logf('Container: %s', $container->name);
            
            // List this container's objects
            $objects = $container->objectList();
            while ($object = $objects->Next()) {
                Utils::logf('Object: %s', $object->name);
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
        while ($container = $containers->next()) {
            // Disable CDN and delete object
            Utils::log('Disable Container CDN');
            try {
                $container->disableCDN();
            } catch (CdnNotAvailableError $e) {}
            
            Utils::log('Delete Object');
            $objects = $container->objectList();
            if ($objects->count()) {
                while ($object = $objects->next()) {
                    Utils::logf('Deleting: %s', $object->name);
                    $object->delete();
                }
            }

            Utils::logf('Delete Container: %s', $container->name);
            $container->delete();
        }
    }
}