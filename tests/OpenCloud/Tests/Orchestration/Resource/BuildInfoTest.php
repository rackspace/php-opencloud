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

use OpenCloud\Orchestration\Resource\BuildInfo;
use OpenCloud\Tests\Orchestration\OrchestrationTestCase;

class BuildInfoTest extends OrchestrationTestCase
{
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCannotCreate()
    {
        $this->buildInfo->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testCannotUpdate()
    {
        $this->buildInfo->update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testCannotDelete()
    {
        $this->buildInfo->delete();
    }
}
