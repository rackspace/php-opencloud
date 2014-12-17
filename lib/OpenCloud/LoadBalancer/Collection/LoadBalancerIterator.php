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

namespace OpenCloud\LoadBalancer\Collection;

use OpenCloud\Common\Collection\PaginatedIterator;

/**
 * This class sets custom defaults for LoadBalancers. Notably, it sets the marker to 'id' instead of 'name'.
 *
 * @package OpenCloud\LoadBalancer\Collection
 */
class LoadBalancerIterator extends PaginatedIterator
{

    /**
     * Defaults for LoadBalancer request.
     *
     * @var array
     */
    protected $defaults = array(
        // Collection limits
        'limit.total'           => 10000,
        'limit.page'            => 100,

        // The "links" element key in response
        'key.links'             => 'links',

        // JSON structure
        'key.collection'        => null,
        'key.collectionElement' => null,

        // The property used as the marker
        'key.marker'            => 'id',

        // Options for "next page" request
        'request.method'        => 'GET',
        'request.headers'       => array(),
        'request.body'          => null,
        'request.curlOptions'   => array()
    );
}
