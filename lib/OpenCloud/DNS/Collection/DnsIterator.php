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

namespace OpenCloud\DNS\Collection;

use OpenCloud\Common\Collection\PaginatedIterator;

class DnsIterator extends PaginatedIterator
{
    const MARKER = 'offset';

    public function updateMarkerToCurrent()
    {
        $this->currentMarker = $this->key();
    }

    protected function shouldAppend()
    {
        if (!$this->currentMarker) {
            return false;
        }

        if ($this->position > $this->getOption('limit.page') && $this->getOption('limit.page') % $this->position == 0) {
            return true;
        }

        if ($this->currentMarker % $this->getOption('limit.page') == 0) {
            return true;
        }

        return false;
    }
}
