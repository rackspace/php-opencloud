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

namespace OpenCloud\LoadBalancer\Resource;

/**
 * The SSL Termination feature allows a load balancer user to terminate SSL
 * traffic at the load balancer layer versus at the web server layer. A user may
 * choose to configure SSL Termination using a key and an SSL certificate or an
 * (Intermediate) SSL certificate.
 *
 * When SSL Termination is configured on a load balancer, a secure shadow server
 * is created that listens only for secure traffic on a user-specified port.
 * This shadow server is only visible to and manageable by the system. Existing
 * or updated attributes on a load balancer with SSL Termination will also apply
 * to its shadow server. For example, if Connection Logging is enabled on an SSL
 * load balancer, it will also be enabled on the shadow server and Cloud Files
 * logs will contain log files for both.
 *
 * @link http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/SSLTermination-d1e2479.html
 */
class SSLTermination extends NonIdUriResource
{
    /**
     * The certificate used for SSL termination.
     *
     * @var string
     */
    public $certificate;

    /**
     * Determines if the load balancer is enabled to terminate SSL traffic.
     * If set to FALSE, the load balancer will retain its specified SSL
     * attributes, but will not terminate SSL traffic.
     *
     * @var bool
     */
    public $enabled;

    /**
     * Determines if the load balancer may accept only secure traffic.
     * If set to TRUE, the load balancer will not accept non-secure traffic.
     *
     * @var bool
     */
    public $secureTrafficOnly;

    /**
     * The private key for the SSL certificate.
     *
     * @var string
     */
    public $privatekey;

    /**
     * The user's intermediate certificate used for SSL termination.
     *
     * @var string
     */
    public $intermediateCertificate;

    /**
     * The port on which the SSL termination load balancer will listen for secure traffic.
     *
     * @var int
     */
    public $securePort;

    protected static $json_name = "sslTermination";
    protected static $url_resource = "ssltermination";

    protected $createKeys = array(
        'certificate',
        'enabled',
        'secureTrafficOnly',
        'privatekey',
        'intermediateCertificate',
        'securePort'
    );

    public function create($params = array())
    {
        return $this->update($params);
    }
}
