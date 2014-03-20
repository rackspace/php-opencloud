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

namespace OpenCloud\Tests\Orchestration\Resource;

use OpenCloud\Tests\Orchestration\OrchestrationTestCase;

class StackTest extends OrchestrationTestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Stack', $this->stack);
    }

    public function testGetCapabilities()
    {
        $capabilities = $this->stack->getCapabilities();
        $this->assertTrue(is_array($capabilities));
        $this->assertEquals(0, count($capabilities));
    }

    public function testGetCreationTime()
    {
        $this->assertEquals('2014-03-20T01:32:33Z', $this->stack->getCreationTime());
    }

    public function testGetDescription()
    {
        $this->assertEquals('Dummy template description', $this->stack->getDescription());
    }

    public function testGetDisableRollback()
    {
        $this->assertEquals(true, $this->stack->getDisableRollback());
    }

    public function testGetId()
    {
        $this->assertEquals('2c50292c-912f-4bf0-9c56-bf9c38574c65', $this->stack->getId());
    }

    public function testGetLinks()
    {
        $links = $this->stack->getLinks();
        $this->assertTrue(is_array($links));
        $this->assertEquals(1, count($links));

        $onlyLink = $links[0];
        $this->assertTrue(is_object($onlyLink));
        $this->assertEquals('self', $onlyLink->rel);
    }

    public function testGetNotificationTopics()
    {
        $notificationTopics = $this->stack->getNotificationTopics();
        $this->assertTrue(is_array($notificationTopics));
        $this->assertEquals(0, count($notificationTopics));
    }

    public function testGetOutputs()
    {
        $outputs = $this->stack->getOutputs();
        $this->assertTrue(is_array($outputs));
        $this->assertEquals(0, count($outputs));
    }

    public function testGetParameters()
    {
        $parameters = $this->stack->getParameters();
        $this->assertTrue(is_array($parameters));
        $this->assertEquals(3, count($parameters));
    }

    public function testGetStackName()
    {
        $this->assertEquals('my_first_stack', $this->stack->getStackName());
    }

    public function testGetStackStatus()
    {
        $this->assertEquals('CREATE_COMPLETE', $this->stack->getStackStatus());
    }

    public function testGetStackStatusReason()
    {
        $this->assertEquals(
            'Stack CREATE completed successfully',
            $this->stack->getStackStatusReason()
        );
    }

    public function testGetTemplateDescription()
    {
        $this->assertEquals(
            'Dummy template description',
            $this->stack->getTemplateDescription()
        );
    }

    public function testGetTimeoutMins()
    {
        $this->assertEquals(60, $this->stack->getTimeoutMins());
    }

    public function testGetUpdatedTime()
    {
        $this->assertEquals(null, $this->stack->getUpdatedTime());
    }

    public function testGetEvents()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->stack->getEvents());
    }

    public function testGetResources()
    {
        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->stack->getResources());
    }

    public function testGetResource()
    {
        $this->assertInstanceOf('OpenCloud\Orchestration\Resource\Resource', $this->stack->getResource('dummy'));
    }

    public function testSetStackName()
    {
        $newStackName = 'foobar';
        $this->stack->setStackName($newStackName);
        $this->assertEquals($newStackName, $this->stack->getStackName());
    }

    public function testSetTemplateUrl()
    {
        $newTemplateUrl = 'http://dummytemplateurl.com';
        $this->stack->setTemplateUrl($newTemplateUrl);
        $this->assertEquals($newTemplateUrl, $this->stack->getTemplateUrl());
    }

    public function testSetTemplate()
    {
        $newTemplate = 'foobar';
        $this->stack->setTemplate($newTemplate);
        $this->assertEquals($newTemplate, $this->stack->getTemplate());
    }

    public function testSetEnvironment()
    {
        $newEnvironment = array(
            'APP_ENV' => 'production'
        );
        $this->stack->setEnvironment($newEnvironment);
        $this->assertEquals($newEnvironment, $this->stack->getEnvironment());
    }

    public function testSetFiles()
    {
        $newFiles = array(
            'file1' => 'contents'
        );
        $this->stack->setFiles($newFiles);
        $this->assertEquals($newFiles, $this->stack->getFiles());
    }

    public function testSetParameters()
    {
        $newParameters = array(
            'foo' => 'bar'
        );
        $this->stack->setParameters($newParameters);
        $this->assertEquals($newParameters, $this->stack->getParameters());
    }

    public function testSetParameter()
    {
        $currentParameters = $this->stack->getParameters();
        $this->stack->setParameter('paramKey', 'paramValue');
        $this->assertEquals(count($currentParameters) + 1, count($this->stack->getParameters()));
    }

    public function testSetTimeoutMins()
    {
        $newTimeoutMins = mt_rand(1,5);
        $this->stack->setTimeoutMins($newTimeoutMins);
        $this->assertEquals($newTimeoutMins, $this->stack->getTimeoutMins());
    }

    public function testSetDisableRollback()
    {
        $newDisableRollback = (boolean) mt_rand(0,1);
        $this->stack->setDisableRollback($newDisableRollback);
        $this->assertEquals($newDisableRollback, $this->stack->getDisableRollback());
    }

}
