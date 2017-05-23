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
namespace OpenCloud;

use OpenCloud\OpenStack;
use OpenCloud\Common\Exceptions;
use OpenCloud\Identity\Service as IdentityService;
use OpenCloud\Common\Http\Message\Formatter;
use OpenCloud\Identity\Resource\Token;


/**** V2 to V3 modifications :
 *  - Authenticate : body contains a global token object
 *  - SetCatalog :
 *      - publicUrl or internalUrl doesn't exist anymore
 */


class OpenStackV3 extends OpenStack
{
    /**
     * Formats the credentials array (as a string) for authentication
     *
     * @return string
     * @throws Common\Exceptions\CredentialError
     */
    public function getCredentials()
    {
        $secret = $this->getSecret();
        if (!empty($secret['username']) && !empty($secret['password'])) {
            $credentials = array('auth' => array(
                'identity' => array(
                    'methods' => array("password"),
                    'password' => array(
                        'user' => array(
                            'id' => $secret['username'],
                            'password' => $secret['password']
                        )
                    )
                )

            ));

            if (!empty($secret['tenantId'])) {
                $credentials['auth']['scope'] = array("project" => array("id" => $secret['tenantId']));
            }

            $json_credentials = json_encode($credentials);

            return $json_credentials;
        } else {
            throw new Exceptions\CredentialError(
                Lang::translate('Unrecognized credential secret')
            );
        }
    }

    /**
     * Sets the X-Auth-Token header. If no value is explicitly passed in, the current token is used.
     *
     * @param  string $token Value of header.
     * @return void
     */
    private function updateTokenHeader($token)
    {
        $this->setDefaultOption('headers/X-Auth-Token', (string) $token);
    }


    /**
     * Authenticate the tenant using the supplied credentials
     *
     * @return void
     * @throws AuthenticationError
     */
    public function authenticate()
    {
        // OpenStack APIs will return a 401 if an expired X-Auth-Token is sent,
        // so we need to reset the value before authenticating for another one.
        $this->updateTokenHeader('');

        $identity = IdentityService::factory($this);
        $response = $identity->generateToken($this->getCredentials());

        $body = Formatter::decode($response);

        $this->setCatalog($body->token->catalog);

        $tokenArr = array();
        $tokenArr['id'] = $response->getHeader("X-Subject-Token");
        $expiresSeconds = time() + (5 * 60);
        $tokenArr['expires'] = date('l dS \o\f F Y h:i:s A', $expiresSeconds);
        $this->setTokenObject($identity->resource('Token', $tokenArr));

        $this->setUser($identity->resource('User', $body->token->user));

        if (isset($body->access->token->tenant)) {
            $this->setTenantObject($identity->resource('Tenant', $body->access->token->tenant));
        }

        // Set X-Auth-Token HTTP request header
        $this->updateTokenHeader($this->getToken());
    }

    /**
     * Set the service catalog.
     *
     * @param  mixed $catalog
     * @return $this
     */
    public function setCatalog($catalog)
    {
        foreach($catalog as $catalogEntryKey => $catalogEntry) {
            $newEndpoints = array();
            foreach ($catalogEntry->endpoints as $endpointIndexKey => $endpoint) {
                $newEndpoint = new stdClass;
                // We only take public url
                if (isset($endpoint->interface) && $endpoint->interface == 'public') {
                    $newEndpoint->publicURL = $endpoint->url;
                    $newEndpoint->url = $endpoint->url;
                    $newEndpoint->region = $endpoint->region;
                    $newEndpoint->region_id = $endpoint->region_id;
                    $newEndpoint->interface = $endpoint->interface;
                    $newEndpoint->id = $endpoint->id;
                    $newEndpoints[] = $newEndpoint;
                }

            }
            $catalogEntry->endpoints = $newEndpoints;
        }

        parent::setCatalog($catalog);

        return $this;
    }
}
