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
use OpenCloud\Common\Exceptions\InstanceNotFound;

/**
 * Description of LoadBalancer
 * 
 * @link 
 */
class LoadBalancer extends AbstractUnit implements UnitInterface
{
    const LB_NAME = 'TEST_LB';
    
    /**
     * {@inheritDoc}
     */
    public function setupService()
    {
        return $this->getConnection()->loadBalancerService('cloudLoadBalancers', Utils::getRegion());
    }
    
    /**
     * {@inheritDoc}
     */
    public function main()
    {
        Utils::step('Connect to the Load Balancer Service');

        Utils::step('Create a Load Balancer');
        $loadBalancer = $this->getService()->loadBalancer();
        $loadBalancer->addVirtualIp('public');
        $loadBalancer->addNode('192.168.0.1', 80);
        $loadBalancer->addNode('192.168.0.2', 80);
        $loadBalancer->create(array(
            'name'     => $this->prepend(self::LB_NAME),
            'protocol' => 'HTTP',
            'port'     => 80
        ));
        $loadBalancer->waitFor('ACTIVE', 300, $this->getWaiterCallback());

        Utils::step('Add a metadata item');
        $metadata = $loadBalancer->metadata();
        $metadata->key = 'author';
        $metadata->value = 'Glen Campbell';
        $metadata->create();

        Utils::step('Add a public IPv6 address');
        $loadBalancer->addVirtualIp('PUBLIC', 6);

        // allowed domains
        $allowedDomains = $this->getService()->allowedDomainList();
        while ($allowedDomain = $allowedDomains->next()) {
            Utils::logf('Allowed domain: [%s]', $allowedDomain->name());
        }

        // protocols
        Utils::step('Protocols:');
        $protocolList = $this->getService()->protocolList();
        while($protocol = $protocolList->next()) {
            Utils::logf(
                '%s %4d', substr($protocol->name() . '..................', 0, 20), 
                $protocol->port
            );
        }

        // algorithms
        Utils::step('Algorithms:');
        $algorithmList = $this->getService()->algorithmList();
        while($algorithm = $algorithmList->next()) {
            Utils::logf('%s', $algorithm->name());
        }

        // list load balancers
        $loadBalancers = $this->getService()->loadBalancerList();
        if ($loadBalancers->count()) {
            
            Utils::step('List load balancers:');
            while ($loadBalancer = $loadBalancers->next()) {
                Utils::logf(
                    '[%s] %s in %s', 
                    $loadBalancer->id, 
                    $loadBalancer->name(), 
                    $loadBalancer->region()
                );
                Utils::logf('Status: [%s]', $loadBalancer->status());

                // Nodes
                $nodeList = $loadBalancer->nodeList();
                if (!$nodeList->count()) {
                    Utils::log('No nodes');
                } else {
                    while ($node = $nodeList->next()) {
                        Utils::log('Node: [%s] %s:%d %s/%s',
                            $node->id(), 
                            $node->address, 
                            $node->port,
                            $node->condition, 
                            $node->status
                        );
                    }
                }

                // NodeEvents
                $nodeEvents = $loadBalancer->nodeEventList();
                if (!$nodeEvents->count()) {
                    Utils::log('No node events');
                } else {
                    while ($event = $nodeEvents->next()) {
                        Utils::log('Event: %s (%s)',
                            $event->detailedMessage, 
                            $event->author
                        );
                    }
                }

                // SSL Termination
                try {
                    $loadBalancer->SSLTermination();
                    Utils::log('SSL terminated');
                } catch (InstanceNotFound $e) {
                    Utils::log('No SSL termination');
                }

                // Metadata
                $metadataList = $loadBalancer->metadataList();
                while ($metadataItem = $metadataList->Next()) {
                    Utils::log('[Metadata #%s] %s=%s',
                        $metadataItem->Id(), 
                        $metadataItem->key, 
                        $metadataItem->value
                    );
                }
            }
        } else {
            Utils::step('There are no load balancers');
        }

        // list Billable LBs
        $start = date('Y-m-d', time() - (3600 * 24 * 30));
        $end   = date('Y-m-d');
        
        Utils::step('Billable Load Balancers from %s to %s', $start, $end);
        
        $list = $this->getService()->billableLoadBalancerList(true, array(
            'startTime' => $start, 
            'endTime'   => $end
        ));
        
        if (!$list->count()) {
            while($lb = $list->Next()) {
                Utils::logf('%10s %s', $lb->Id(), $lb->name());
                Utils::logf('%10s created: %s', '', $lb->created->time);
            }
        } else {
            Utils::log('No billable load balancers');
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function teardown()
    {
        $loadBalancers = $this->getService()->loadBalancerList();
        while ($loadBalancer = $loadBalancers->next()) {
            if ($this->shouldDelete($loadBalancer->name())) {
                $loadBalancer->delete();
            }
        }
    }
}