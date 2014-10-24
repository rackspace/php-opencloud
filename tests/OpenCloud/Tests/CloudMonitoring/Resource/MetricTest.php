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

namespace OpenCloud\Tests\CloudMonitoring\Resource;

use OpenCloud\Tests\CloudMonitoring\CloudMonitoringTestCase;

class MetricTest extends CloudMonitoringTestCase
{
    const CHECK_ID = 'chAAAA';
    const METRIC_NAME = 'mzdfw.available';

    public function setupObjects()
    {
        parent::setupObjects();

        $this->addMockSubscriber($this->getTestFilePath('Check'));
        $this->check = $this->entity->getCheck(self::CHECK_ID);

        $this->addMockSubscriber($this->getTestFilePath('Metrics'));
        $this->metrics = $this->check->getMetrics();
        $this->metric = $this->metrics->first();
    }

    public function testResourceClass()
    {
        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Metric',
            $this->metric
        );

        $this->assertInstanceOf(
            'OpenCloud\\CloudMonitoring\\Resource\\Check',
            $this->metric->getParent()
        );

        $this->assertInstanceOf(self::COLLECTION_CLASS, $this->metrics);
    }

    public function testUrl()
    {
        $this->assertEquals(
            'https://monitoring.api.rackspacecloud.com/v1.0/123456/entities/' . self::ENTITY_ID . '/checks/' . self::CHECK_ID . '/metrics',
            (string)$this->metric->getUrl()
        );
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\CreateError
     */
    public function testCreateFail()
    {
        $this->metric->create();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\UpdateError
     */
    public function testUpdateFail()
    {
        $this->metric->update();
    }

    /**
     * @expectedException \OpenCloud\Common\Exceptions\DeleteError
     */
    public function testDeleteFail()
    {
        $this->metric->delete();
    }

    /**
     * @mockFile Metrics_DataPoints
     */
    public function testDataPointsClass()
    {
        $this->assertInstanceOf(
            self::COLLECTION_CLASS,
            $this->check->fetchDataPoints(self::METRIC_NAME, array(
                'resolution' => 'FULL',
                'select'     => 'average',
                'from'       => 1369756378450,
                'to'         => 1369760279018
            ))
        );
    }

    /**
     * @mockFile Metrics_DataPoints
     */
    public function testDataPointsInfo()
    {
        $data = $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'resolution' => 'FULL',
            'select'     => 'average',
            'from'       => 1369756378450,
            'to'         => 1369760279018
        ));

        foreach ($data as $info) {
            $this->assertObjectHasAttribute('numPoints', $info);
            $this->assertObjectHasAttribute('timestamp', $info);
            $this->assertObjectHasAttribute('average', $info);
        }
    }

    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithoutToFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME);
    }

    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithoutFromFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to' => 1369760279018
        ));
    }

    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithoutTheRestFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to'   => 1369760279018,
            'from' => 1369756378450
        ));
    }

    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithIncorrectSelectFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to'     => 1369760279018,
            'from'   => 1369756378450,
            'select' => 'foo'
        ));
    }

    /**
     * @expectedException \OpenCloud\CloudMonitoring\Exception\MetricException
     */
    public function testFetchWithIncorrectResolutionFails()
    {
        $this->check->fetchDataPoints(self::METRIC_NAME, array(
            'to'         => 1369760279018,
            'from'       => 1369756378450,
            'resolution' => 'bar'
        ));
    }
}
