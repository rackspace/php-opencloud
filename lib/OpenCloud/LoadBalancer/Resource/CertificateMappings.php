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

use OpenCloud\Common\Resource\PersistentResource;

/**
 * Certificate Mapping uses SSL Termination to map a particular certificate
 * to a corresponding hostname, allowing multiple SSL certificates to
 * exist and be accurately utilized from a Load Balancer.
 */
class CertificateMappings extends PersistentResource
{
    /**
     * Id for the Load Balancer.
     *
     * @var string
     */
    public $id;

    /**
     * Hostname to be mapped to certificate.
     *
     * @var string
     */
    public $hostName;

    /**
     * Certificate to be mapped to hostname.
     *
     * @var string
     */
    public $certificate;

    /**
     * Private Key to the certificate.
     *
     * @var string
     */
    public $privateKey;

    /**
     * Intermediate certificate for the chain.
     *
     * @var string
     */
    public $intermediateCertificate;

    protected static $json_name = 'certificateMapping';
    protected static $json_collection_name = 'certificateMappings';
    protected static $url_resource = 'ssltermination/certificatemappings';

    protected $createKeys = array(
        'hostName',
        'certificate',
        'privateKey',
        'intermediateCertificate',
    );

    protected function updateJson($params = array())
    {
        if ($params['hostName']) {
            $updated_params['hostName'] = $params['hostName'];
        }
        if ($params['privateKey']) {
            $updated_params['privateKey'] = $params['privateKey'];
        }
        if ($params['certificate']) {
            $updated_params['certificate'] = $params['certificate'];
        }
        if ($params['intermediateCertificate']) {
            $updated_params['intermediateCertificate'] = $params['intermediateCertificate'];
        }

        return (object) array('certificateMapping' => (object) $updated_params);
    }

}
