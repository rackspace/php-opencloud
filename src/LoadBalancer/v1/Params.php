<?php

namespace Rackspace\LoadBalancer\v1;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    /**
     * Returns information about account parameter
     *
     * @return array
     */
    public function accountUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about accessList parameter
     *
     * @return array
     */
    public function accessListJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The access list management feature allows fine-grained network access controls to be applied to the load balancer virtual IP address. Refer to access-lists for information and examples.',
        ];
    }

    /**
     * Returns information about address parameter
     *
     * @return array
     */
    public function addressJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about algorithm parameter
     *
     * @return array
     */
    public function algorithmJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Algorithm that defines how traffic should be directed between back-end nodes.',
        ];
    }

    /**
     * Returns information about condition parameter
     *
     * @return array
     */
    public function conditionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about connectionLogging parameter
     *
     * @return array
     */
    public function connectionLoggingJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Current connection logging configuration. Refer to log-connections for information and examples.',
        ];
    }

    /**
     * Returns information about connectionThrottle parameter
     *
     * @return array
     */
    public function connectionThrottleJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Specifies limits on the number of connections per IP address to help mitigate malicious or abusive traffic to your applications. See throttle-connections for information and examples.',
        ];
    }

    /**
     * Returns information about halfClosed parameter
     *
     * @return array
     */
    public function halfClosedJson()
    {
        return [
            'type'        => self::BOOLEAN_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Enables or disables Half-Closed support for the load balancer. Half- Closed support provides the ability for one end of the connection to terminate its output, while still receiving data from the other end. Only available for TCP/TCP_CLIENT_FIRST protocols.',
        ];
    }

    /**
     * Returns information about healthMonitor parameter
     *
     * @return array
     */
    public function healthMonitorJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The type of health monitor check to perform to ensure that the service is performing properly.',
        ];
    }

    /**
     * Returns information about httpsRedirect parameter
     *
     * @return array
     */
    public function httpsRedirectJson()
    {
        return [
            'type'        => self::BOOLEAN_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Enables or disables HTTP to HTTPS redirection for the load balancer. When enabled, any HTTP request returns status code 301 (Moved Permanently), and the requester is redirected to the requested URL via the HTTPS protocol on port 443. For example, 57a2fea47198eaee3b3bd9185757ba21203f688b would be redirected to 86c0f613107daf72928c92cb332396b30b24a6d6. Only available for HTTPS protocol ( 920bb144ff2bb3500a9a33feb74f204f40f64803 ), or HTTP protocol with a properly configured SSL termination ( 56daeebd5eb5c887068c6e6f2feaa46ba08444a3, f5284d3e69cad3d870b24e4b55f46de752884fe5 ). Note that SSL termination for a load balancer can only be configured after the load balancer has been created.',
        ];
    }

    /**
     * Returns information about id parameter
     *
     * @return array
     */
    public function idJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about metadata parameter
     *
     * @return array
     */
    public function metadataJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Information (metadata) that can be associated with each load balancer.',
        ];
    }

    /**
     * Returns information about name parameter
     *
     * @return array
     */
    public function nameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about nodes parameter
     *
     * @return array
     */
    public function nodesJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'items'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'address'   => $this->addressJson(),
                    'port'      => $this->portJson(),
                    'condition' => $this->conditionJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Nodes to be added to the load balancer.',
        ];
    }

    /**
     * Returns information about port parameter
     *
     * @return array
     */
    public function portJson()
    {
        return [
            'type'        => self::INTEGER_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Port number for the service you are load balancing.',
        ];
    }

    /**
     * Returns information about protocol parameter
     *
     * @return array
     */
    public function protocolJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Protocol of the service that is being load balanced.',
        ];
    }

    /**
     * Returns information about sessionPersistence parameter
     *
     * @return array
     */
    public function sessionPersistenceJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Specifies whether multiple requests from clients are directed to the same node.',
        ];
    }

    /**
     * Returns information about timeout parameter
     *
     * @return array
     */
    public function timeoutJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'The timeout value for the load balancer and communications with its nodes. Defaults to 30 seconds with a maximum of 120 seconds.',
        ];
    }

    /**
     * Returns information about type parameter
     *
     * @return array
     */
    public function typeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about virtualIps parameter
     *
     * @return array
     */
    public function virtualIpsJson()
    {
        return [
            'type'        => self::ARRAY_TYPE,
            'location'    => self::JSON,
            'items'  => [
                'type'       => self::OBJECT_TYPE,
                'location'   => self::JSON,
                'properties' => [
                    'id' => $this->idJson(),
                ],
            ],
            'required'    => false,
            'description' => 'Type of virtualIp to add with the creation of a load balancer. See the virtual IP types table at virtual-ips       .',
        ];
    }

    /**
     * Returns information about loadBalancerId parameter
     *
     * @return array
     */
    public function loadBalancerIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about value parameter
     *
     * @return array
     */
    public function valueJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Value for the metadata item. Must be 256 characters or less. All UTF-8 characters are valid.',
        ];
    }

    /**
     * Returns information about content parameter
     *
     * @return array
     */
    public function contentJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about ipVersion parameter
     *
     * @return array
     */
    public function ipVersionJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about attemptsBeforeDeactivation parameter
     *
     * @return array
     */
    public function attemptsBeforeDeactivationJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about bodyRegex parameter
     *
     * @return array
     */
    public function bodyRegexJson()
    {
        return [
            'type'        => 'A regular expression that will be used to evaluate the contents of the body of the response. For example you could use the regular expression "^.*(Unauthorized|Forbidden|Not Found|Timeout|Server Error).*$" to look for any of those potentially problematic strings in the body of the response or use the regular expression "^success$" to look for the string "success".  **Note:** The system only evaluates the first 2048 bytes of the response against the bodyRegex that is specified, so you will want to test accordingly. To debug the HTTP/HTTPS health monitoring you will want to test the bodyRegex against the IP of the node(s) that are being disabled. You can use the following cURL command to see what the health monitoring analyzes: curl -s -r 0-2048 https://YOUR_IP_ADDRESS | head -c 2048 | egrep "YOUR_REGULAR_EXPRESSION"',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Yes                              ',
        ];
    }

    /**
     * Returns information about delay parameter
     *
     * @return array
     */
    public function delayJson()
    {
        return [
            'type'        => self::INTEGER_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Yes   ',
        ];
    }

    /**
     * Returns information about hostHeader parameter
     *
     * @return array
     */
    public function hostHeaderJson()
    {
        return [
            'type'        => 'The name of a host for which the health monitors will check.',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'No ',
        ];
    }

    /**
     * Returns information about path parameter
     *
     * @return array
     */
    public function pathJson()
    {
        return [
            'type'        => 'The HTTP path that will be used in the sample request.',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Yes ',
        ];
    }

    /**
     * Returns information about statusRegex parameter
     *
     * @return array
     */
    public function statusRegexJson()
    {
        return [
            'type'        => 'A regular expression that will be used to evaluate the HTTP status code returned in the response.',
            'required'    => false,
            'location'    => self::JSON,
            'description' => 'Yes   ',
        ];
    }

    /**
     * Returns information about nodeId parameter
     *
     * @return array
     */
    public function nodeIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about weight parameter
     *
     * @return array
     */
    public function weightJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about enabled parameter
     *
     * @return array
     */
    public function enabledJson()
    {
        return [
            'type'     => self::BOOLEAN_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about metaId parameter
     *
     * @return array
     */
    public function metaIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about maxConnectionRate parameter
     *
     * @return array
     */
    public function maxConnectionRateJson()
    {
        return [
            'type'     => self::INTEGER_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about maxConnections parameter
     *
     * @return array
     */
    public function maxConnectionsJson()
    {
        return [
            'type'        => self::INTEGER_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Maximum number of connections to allow for a single IP address. To enable unlimited simultaneous connections, set to 0. Set to a value from 1 to 100000.',
        ];
    }

    /**
     * Returns information about minConnections parameter
     *
     * @return array
     */
    public function minConnectionsJson()
    {
        return [
            'type'        => self::INTEGER_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Deprecated as of v1.22 and later versions. Parameter can still be set, but it has no effect on the load balancer.',
        ];
    }

    /**
     * Returns information about rateInterval parameter
     *
     * @return array
     */
    public function rateIntervalJson()
    {
        return [
            'type'        => self::INTEGER_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'Deprecated as of v1.22 and later versions. Parameter can still be set, but it has no effect on the load balancer.',
        ];
    }

    /**
     * Returns information about persistenceType parameter
     *
     * @return array
     */
    public function persistenceTypeJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about virtualIpId parameter
     *
     * @return array
     */
    public function virtualIpIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about networkItemId parameter
     *
     * @return array
     */
    public function networkItemIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }

    /**
     * Returns information about certificate parameter
     *
     * @return array
     */
    public function certificateJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'The certificate to be used for the provided host name. The certificate is validated and verified against the key and intermediate certificate(s) if provided.',
        ];
    }

    /**
     * Returns information about hostName parameter
     *
     * @return array
     */
    public function hostNameJson()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
        ];
    }

    /**
     * Returns information about intermediateCertificate parameter
     *
     * @return array
     */
    public function intermediateCertificateJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'The intermediate certificate to be used for the provided certificate and host name. The intermediate certificate is validated and verified against the private key and certificate credentials provided. A user may only provide an intermediate certificate when accompanied by a certificate, private key and host name. It may be added to an existing certificate mapping configuration as a single attribute in a future request.',
        ];
    }

    /**
     * Returns information about privateKey parameter
     *
     * @return array
     */
    public function privateKeyJson()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::JSON,
            'required'    => false,
            'description' => 'The private key to be used for the provided certificate. The private key is validated and verified against the provided certificates.',
        ];
    }

    /**
     * Returns information about certificateMappingId parameter
     *
     * @return array
     */
    public function certificateMappingIdUrl()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::URL,
        ];
    }
}