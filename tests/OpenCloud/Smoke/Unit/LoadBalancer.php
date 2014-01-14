<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Enum;
use OpenCloud\Smoke\Utils;
use Guzzle\Http\Exception\ClientErrorResponseException;
use OpenCloud\Common\Exceptions\DeleteError;

class LoadBalancer extends AbstractUnit implements UnitInterface
{
    const LB_NAME = 'TEST_LB';

    public function setupService()
    {
        return $this->getConnection()->loadBalancerService('cloudLoadBalancers', Utils::getRegion());
    }

    public function main()
    {
        $this->step('Connect to the Load Balancer Service');

        $this->step('Create a Load Balancer');
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

        $this->step('Add a metadata item');
        $metadata = $loadBalancer->metadata();
        $metadata->key = 'author';
        $metadata->value = 'Jamie';
        $metadata->create();

        $this->step('Add a public IPv6 address');
        $loadBalancer->addVirtualIp('PUBLIC', 6);

        // allowed domains
        $this->step('List allowed domains');
        $allowedDomains = $this->getService()->allowedDomainList();
        foreach ($allowedDomains as $allowedDomain) {
            $this->stepInfo('Allowed domain: [%s]', $allowedDomain->name());
        }

        // protocols
        $this->step('List protocols:');
        $protocolList = $this->getService()->protocolList();
        foreach ($protocolList as $protocol) {
            $this->stepInfo(
                '%s %4d', 
                substr($protocol->name() . '..................', 0, 20), 
                $protocol->port
            );
        }

        // algorithms
        $this->step('List algorithms:');
        $algorithmList = $this->getService()->algorithmList();
        foreach ($algorithmList as $algorithm) {
            $this->stepInfo('%s', $algorithm->name());
        }

        // list load balancers
        $this->step('Listing load balancers');
        $loadBalancers = $this->getService()->loadBalancerList();
        $loadBalancers->setOption('limit.total', Enum::DISPLAY_ITER_LIMIT);

        if ($loadBalancers->count()) {

            $i = 1;
            $total = $loadBalancers->count() > 10 ? 10 : $loadBalancers->count();
            
            foreach ($loadBalancers as $loadBalancer) {
                
                $step = $this->stepInfo('Load balancer (%d/%d)', $i, $total);
                $step->stepInfo(
                    'ID [%s], Name [%s], Status [%s]', 
                    $loadBalancer->id,
                    $loadBalancer->name(),
                    $loadBalancer->status()
                );

                // Nodes
                $step1 = $step->subStep('Listing nodes');
                $nodeList = $loadBalancer->nodeList();
                if (!$nodeList->count()) {
                    $step1->stepInfo('No nodes');
                } else {
                    foreach ($nodeList as $node) {
                        $step1->stepInfo('Node: [%s] %s:%d %s/%s',
                            $node->id(), 
                            $node->address, 
                            $node->port,
                            $node->condition, 
                            $node->status
                        );
                    }
                }

                // NodeEvents
                $step2 = $step->subStep('Listing node events');
                $nodeEvents = $loadBalancer->nodeEventList();
                if (!$nodeEvents->count()) {
                    $step2->stepInfo('No node events');
                } else {
                    foreach ($nodeEvents as $event) {
                        $step2->stepInfo('Event: %s (%s)',
                            $event->detailedMessage, 
                            $event->author
                        );
                    }
                }

                // SSL Termination
                try {
                    $loadBalancer->SSLTermination();
                    $step->subStep('SSL terminated');
                } catch (ClientErrorResponseException $e) {
                    $step->subStep('No SSL termination');
                }

                // Metadata
                $step3 = $step->subStep('Listing metadata');
                $metadataList = $loadBalancer->metadataList();
                foreach ($metadataList as $metadataItem) {
                    $step3->stepInfo('[Metadata #%s] %s=%s',
                        $metadataItem->Id(), 
                        $metadataItem->key, 
                        $metadataItem->value
                    );
                }

                $i++;
            }
        } else {
            $this->stepInfo('There are no load balancers');
        }

        // list Billable LBs
        $start = date('Y-m-d', time() - (3600 * 24 * 30));
        $end   = date('Y-m-d');
        
        $this->step('Billable Load Balancers from %s to %s', $start, $end);
        
        $list = $this->getService()->billableLoadBalancerList(true, array(
            'startTime' => $start, 
            'endTime'   => $end
        ));
        
        if ($list->count()) {
            foreach ($list as $lb) {
                $this->stepInfo('%10s %s', $lb->Id(), $lb->name());
                $this->stepInfo('%10s created: %s', '', $lb->created->time);
            }
        } else {
            $this->stepInfo('No billable load balancers');
        }
    }

    public function teardown()
    {
        $this->step('Teardown');

        $loadBalancers = $this->getService()->loadBalancerList();
        foreach ($loadBalancers as $loadBalancer) {
            if ($this->shouldDelete($loadBalancer->name())) {
                try {
                    $this->stepInfo('Deleting %s', $loadBalancer->getId());
                    $loadBalancer->delete();
                } catch (\Exception $e) {
                    $this->stepInfo('Failed to delete %s', $loadBalancer->getId());
                }
            }
        }
    }
}