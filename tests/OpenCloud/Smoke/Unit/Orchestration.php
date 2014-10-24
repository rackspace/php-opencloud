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

use OpenCloud\Smoke\Utils;

/**
 * Description of Orchestration
 *
 * @link
 */
class Orchestration extends AbstractUnit implements UnitInterface
{
    protected $cleanupStackIds = array();

    public function setupService()
    {
        return $this->getConnection()->orchestrationService('cloudOrchestration', Utils::getRegion());
    }

    public function main()
    {
        $this->step('Validate template from a file');
        $this->getService()->validateTemplate(array(
            'template' => file_get_contents($this->getResourceDir() . '/lamp.yaml')
        ));
  
        $this->step('Validate template from a URL');
        $this->getService()->validateTemplate(array(
            'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml'
        ));
  
        $this->step('Preview stack from template file');
        $stack = $this->getService()->previewStack(array(
            'name'       => 'simple-lamp-setup',
            'template'   => file_get_contents($this->getResourceDir() . '/lamp.yaml'),
            'parameters' => array(
                'server_hostname' => 'web01',
                'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
            )
        ));
  
        $this->step('Preview stack from template URL');
        $stack = $this->getService()->previewStack(array(
            'name'       => 'simple-lamp-setup',
            'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
            'parameters' => array(
                'server_hostname' => 'web01',
                'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
            )
        ));
  
        $this->step('Create stack from template file');
        $stack = $this->getService()->createStack(array(
            'name'       => 'simple-lamp-setup-from-template-file',
            'template'   => file_get_contents($this->getResourceDir() . '/lamp.yaml'),
            'parameters' => array(
                'server_hostname' => 'web01',
                'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
            )
        ));
        $this->stepInfo('Stack ID: ' . $stack->getId());
        $this->stepInfo('Stack Name: ' . $stack->getName());
        $this->cleanupStackIds[] = $stack->getId();

        $this->step('Create stack from template URL');
        $stack = $this->getService()->createStack(array(
            'name'       => 'simple-lamp-setup-from-template-url',
            'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
            'parameters' => array(
                'server_hostname' => 'web01',
                'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
            )
        ));
        $this->stepInfo('Stack ID: ' . $stack->getId());
        $this->stepInfo('Stack Name: ' . $stack->getName());

        $this->step('List stacks');
        $stacks = $this->getService()->listStacks();
        $this->stepInfo('%-40s | %s', 'Stack ID', 'Stack name');
        $this->stepInfo('%-40s | %s', str_repeat('-', 40), str_repeat('-', 40));
        foreach ($stacks as $stack) {
            $this->stepInfo('%-40s | %s', $stack->getId(), $stack->getName());
        }
  
        $this->step('Get stack');
        $stack = $this->getService()->getStack('simple-lamp-setup-from-template-file');
        $this->stepInfo('Stack ID: ' . $stack->getId());
        $this->stepInfo('Stack name: ' . $stack->getName());
  
        $this->step('Get stack template');
        $stackTemplateJsonStr = $stack->getStackTemplate();
        $this->stepInfo('Stack template JSON: %s ', $stackTemplateJsonStr);
  
        $this->step('List stack resources');
        $resources = $stack->listResources();
        $this->stepInfo('Resource name');
        $this->stepInfo(str_repeat('-', 25));
        foreach ($resources as $resource) {
            $this->stepInfo($resource->getName());
        }
        
        $this->step('Get stack resource');
        $resource = $stack->getResource('linux_server');
        $this->stepInfo('Resource name: ' . $resource->getName());

        $this->step('Get stack resource metadata');
        $metadata = $resource->getMetadata();
        $this->stepInfo('%-25s | %s', 'Metadata key', 'Metadata value');
        foreach ($metadata as $key => $value) {
            $this->stepInfo('%-25s | %s', $key, $value);
        }

        $this->step('List stack events');
        $events = $stack->listEvents();
        $this->stepInfo('%-40s | %-20s | %-25s | %s', 'Event ID', 'Event time', 'Resource name', 'Resource status');
        $this->stepInfo('%-40s | %-20s | %-25s | %s', str_repeat('-', 40), str_repeat('-', 20), str_repeat('-', 25), str_repeat('-', 20));
        foreach ($events as $event) {
            $this->stepInfo('%-40s | %-20s | %-25s | %s',
                $event->getId(), $event->getTime(), $event->getResourceName(), $event->getResourceStatus());
        }

        $this->step('List stack resource events');
        $events = $resource->listEvents();
        $this->stepInfo('%-40s | %-20s | %-25s | %s', 'Event ID', 'Event time', 'Resource name', 'Resource status');
        $this->stepInfo('%-40s | %-20s | %-25s | %s', str_repeat('-', 40), str_repeat('-', 20), str_repeat('-', 25), str_repeat('-', 20));
        foreach ($events as $event) {
            $this->stepInfo('%-40s | %-20s | %-25s | %s',
                $event->getId(), $event->getTime(), $event->getResourceName(), $event->getResourceStatus());
            $lastEventId = $event->getId();
        }

        $this->step('Get stack resource event');
        $event = $resource->getEvent($lastEventId);
        $this->stepInfo('Event ID: ' . $event->getId());
        $this->stepInfo('Event time: ' . $event->getTime());
        $this->stepInfo('Resource name: ' . $event->getResourceName());
        $this->stepInfo('Resource status: ' . $event->getResourceStatus());

        $this->step('List resource types');
        $resourceTypes = $this->getService()->listResourceTypes();
        $this->stepInfo('Resource type');
        $this->stepInfo(str_repeat('-', 40));
        foreach ($resourceTypes as $resourceType) {
            $this->stepInfo($resourceType->getResourceType());
            $lastResourceType = $resourceType->getResourceType();
        }

        $this->step('Get resource type');
        $resourceType = $this->getService()->getResourceType($lastResourceType);
        $this->stepInfo('Resource type: ' . $resourceType->getResourceType());

        $this->step('Get resource type template');
        $this->stepInfo('Resource type template: ' . $resourceType->getTemplate());

        $this->step('Get build info');
        $buildInfo = $this->getService()->getBuildInfo();
        $this->stepInfo('API revision: ' . $buildInfo->getApi()->revision);
        $this->stepInfo('Engine revision: ' . $buildInfo->getEngine()->revision);

        $this->step('Update stack from template file');
        $stack->waitFor('CREATE_COMPLETE', null, function ($s) {
            $this->stepInfo('Stack is still being created. Waiting...');
        });
        $stack->update(array(
            'template'      => file_get_contents($this->getResourceDir() . '/lamp-updated.yaml'),
            'parameters'    => array(
                'server_hostname' => 'web01',
                'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
            ),
            'timeoutMins'   => 5
        ));
        $this->stepInfo('Done! Stack update requested.');

        $this->step('Abandon stack');
        $stack = $this->getService()->getStack('simple-lamp-setup-from-template-url');
        $stack->waitFor('CREATE_COMPLETE', null, function ($s) {
            $this->stepInfo('Stack is still being created. Waiting...');
        });
        $abandonedStackData = $stack->abandon();
        $this->stepInfo('Abandon stack data: %s ', $abandonedStackData);

        $this->step('Adopt stack');
        sleep(10); // For abandoned stack to get deleted.
        $stack = $this->getService()->adoptStack(array(
            'name'       => 'simple-lamp-setup-from-template-url',
            'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
            'parameters' => array(
                'server_hostname' => 'web01',
                'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
            ),
            'adoptStackData' => $abandonedStackData,
            'timeoutMins'    => 5
        ));
        $this->stepInfo('Stack ID: ' . $stack->getId());
        $this->stepInfo('Stack name: ' . $stack->getName());
        $this->cleanupStackIds[] = $stack->getId();
    }

    public function teardown()
    {
        foreach ($this->cleanupStackIds as $stackId) {
            $stack = $this->getService()->getStack($stackId);
            $stack->delete();
        }
    }
}
