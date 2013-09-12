<?php

namespace OpenCloud\Tests;

use OpenCloud\OpenStack;
use OpenCloud\Common\Request\Response\Blank;

define('TESTDIR', __DIR__);

/**
 * This is a stub Connection class that bypasses the actual connections
 *
 * NOTE THAT EVERYTHING IN THIS FILE IS IN A STRICT SEQUENCE (usually)
 * Many items (e.g., /changes) must come before other patterns that also
 * match the same URL (e.g., /domains, because the full URL is
 * /domains/{id}/changes).
 *
 * Be careful where you put things.
 */
class StubConnection extends OpenStack
{

    private $testDir;

    public $async_response = <<<ENDRESPONSE
{"status":"RUNNING","verb":"GET","jobId":"852a1e4a-45b4-409b-9d46-2d6d641b27cf","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/status/852a1e4a-45b4-409b-9d46-2d6d641b27cf","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/domains/3612932/export"}
ENDRESPONSE;

    public function __construct($url, $secret, $options = array()) 
    {
        $this->testDir = __DIR__;

        if (is_array($secret)) {
            return parent::__construct($url, $secret, $options);
        } else {
            return parent::__construct(
                $url,
                array(
                    'username' => 'X', 
                    'password' => 'Y'
                ), 
                $options
            );
        }
    }

    public function request($url, $method = "GET", $headers = array(), $body = null) 
    {
        $resp = new Blank;
        $resp->headers = array(
            'Content-Length' => '999'
        );

        // POST
        
		if ($method == 'POST') {
            
			$resp->status = 200;
			
            if (strpos($url, '/action')) {
			    if ('{"rescue' == substr($body, 0, 8)) {
			        $resp->body = '{"adminPass": "m7UKdGiKFpqM"}';
			    } elseif(preg_match('#EPIC-IMAGE#', $body)) {
                    $resp->body = '';
                    $resp->status = 202;
                    $resp->headers['Location'] = 'fooBar';
                } else {
    				$resp->body = '';
    			}
			} elseif (strpos($url, '/token')) {
                // Bad auth
                if (preg_match('/badPassword/', $body)) {
                    $resp->status = 400;
                } else {
                    // Good auth
                    $resp->body = file_get_contents($this->testDir . '/connection.json');
                }
			} elseif (preg_match('/root$/', $url)) {
				$resp->body = '{"user":{"name":"root","password":"foo"}}';
			} elseif (strpos($url, '/databases')) {
				$resp->body = '{to be filled in}';
				$resp->status = 202;
			} elseif (strpos($url, '/loadbalancers')) {
				$resp->body = '{"loadBalancer":{"id":"123","name":"NONAME"}}';
                $resp->status = 202;
            } elseif (strpos($url, 'network')) {
                $resp->body = '{"network":{"id":"1","cidr":"192.168.0.0/24","label":"foo"}}';
            } elseif (strpos($url, '/instances')) {
                $resp->body = file_get_contents($this->testDir.'/dbinstance-create.json');
            } elseif (strpos($url, '/import')) { // domain import
                $resp->body = $this->async_response;
                $resp->status = 202;
            } elseif (strpos($url, '/domains')) { // domain create
                $resp->body = $this->async_response;
                $resp->status = 202;
            } elseif (strpos($url, '/rdns')) {
                $resp->body = $this->async_response;
                $resp->status = 202;
            } elseif (preg_match('/servers\/[0-9a-z\-]+\/rax-si-image-schedule/',$url)) {
                $resp->body = file_get_contents($this->testDir.'/imageschedule-create.json');
                $resp->status = 204;
            } elseif (strpos($url, '/servers')) {
                $resp->body = file_get_contents($this->testDir.'/server-create.json');
            } elseif (strpos($url, '/queues')) {
            
               if (preg_match('#/queues/(\w|\-)+/messages$#', $url)) {
                   // post message
                   $resp->status = 201;
                   $resp->body = file_get_contents(__DIR__ . '/Queues/Response/POST/post_message.json');
               } elseif (preg_match('#/queues/foobar/claims(\?(\w|\&|\=)+)?$#', $url)) {
                   $resp->status = 204;
                   $resp->body = '{}';
               } elseif (preg_match('#/queues/(\w|\-)+/claims(\?(\w|\&|\=)+)?$#', $url)) {
                   // claim messages
                   $resp->status = 201;
                   $resp->body = file_get_contents(__DIR__ . '/Queues/Response/POST/claim_messages.json');
               } else {
                   $resp->status = 404;
                   $resp->body = '{}';
               }
                
            } else {
                die("No stub data for URL $url\n");
            }
		}
        
        // DELETE
        
		elseif ($method == 'DELETE') {
			$resp->status = 202;
            
            if (strpos($url, '/queues')) {
                if (strpos($url, 'foo!!')) {
                    $resp->status = 404;
                } else {
                    $resp->status = 204;
                }
            }
		}
        
        // PUT
        
		elseif ($method == 'PUT') {
            
            if (strpos($url, '/domains')) {
                $resp->body = $this->async_response;
                $resp->status = 202;
            } elseif (preg_match('/NON-CDN/', $url)) {  
                // something else
                $resp->body = '{}';
                $resp->status = 201;
                $resp->headers = array(
                    'ETag' => 'd9f5eb4bba4e2f2f046e54611bc8196b',
                    'Content-Length' => 0,
                    'Content-Type' => 'text/plain; charset=UTF-8'
                );
            } elseif (strpos($url, '/queues')) {
            
               if (preg_match('#/queues/(\w|\-)+$#', $url)) {
                   // create queue
                   $resp->status = 201;
                   $resp->body = '{}';
                   $resp->headers = array(
                       'Location' => 'foo'
                   );
                   
               } elseif (preg_match('#/queues/(\w|\-)+/metadata$#', $url)) {
                   // Sets queue metadata
                   $resp->status = 204;
                   $resp->body = '{}';
               } else {
                   $resp->status = 404;
                   $resp->body = '{}';
               }
                
            } else {
                
                if (!empty($headers['X-CDN-Enabled'])) {
                    
                    // Disable CDN
                    if ($headers['X-CDN-Enabled'] == 'False') {
                        $resp->body = '{}';
                        $resp->status = 201;
                    }
                  
                }
            }
            
		}
        
        // HEAD
        
        elseif ($method == 'HEAD') {
            
            if (preg_match('/TEST$/', $url)) {
                $resp->body = '{}';
                $resp->status = 204;
                $resp->headers = array(
                    'X-Cdn-Ssl-Uri' => 'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
                    'X-Ttl' => 259200,
                    'X-Cdn-Uri' => 'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
                    'X-Cdn-Enabled' => 'True',
                    'X-Log-Retention' => 'False',
                    'X-Cdn-Streaming-Uri' => 'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
                    'X-Cdn-Ios-Uri' => 'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.ios.cf0.rackcdn.com',
                    'X-Trans-Id' => 'tx82a6752e00424edb9c46fa2573132e2c',
                    'Content-Length' => 0
                );
            } elseif (preg_match('#/queues/foobar$#', $url)) {  
                $resp->status = 404;
                $resp->body = '{}';
            } elseif (preg_match('#/queues/(\w|\-)+$#', $url)) {
                // Queue exists
                $resp->body = '{}';
                $resp->status = 204;
            } 
            
        }
        
        // PATCH
        elseif ($method == 'PATCH') {
            $resp->status = 204;
            if (strpos($url, 'claims/foobar')) {
                $resp->status = 404;
            }
        }
        
        // GET
        
        else {
        
            if (strpos($url, '/os-volume_attachments/')) {
                $resp->body = '{"volumeAttachment":{"volumeId":"FOO"}}';
                $resp->status = 200;
            } elseif (strpos($url, '/os-volume_attachments')) {
                $resp->body = '{"volumeAttachments": []}';
                $resp->status = 200;
            } elseif (strpos($url, 'os-networksv2')) {
                $resp->body = NULL;
                $resp->status = 200;
            } elseif (preg_match('/loadbalancers\/.*\/stats$/', $url)) {
                $resp->body = '{"connectTimeOut":10,"connectError":20,"connectFailure":30,"dataTimedOut":40,"keepAliveTimedOut":50,"maxConn":60}';
            } elseif (strpos($url, 'ignore')) {
                $resp->status = 200;
                $resp->body = '{"ignore":{}}';
            } elseif (strpos($url, '/loadbalancers/')) {
                $resp->status = 200;
                if (strpos($url, '/virtualips'))
                    $resp->body = '{}';
                elseif (strpos($url, '/nodes'))
                    $resp->body = '{}';
                elseif (strpos($url, '/billable'))
                    $resp->body = '{}';
                elseif (strpos($url, '/algorithms'))
                    $resp->body = '{}';
                elseif (strpos($url, '/sessionpersistence'))
                    $resp->body = '{}';
                elseif (strpos($url, '/errorpage'))
                    $resp->body = '{}';
                elseif (strpos($url, '/healthmonitor'))
                    $resp->body = '{}';
                elseif (strpos($url, '/usage'))
                    $resp->body = '{}';
                elseif (strpos($url, '/accesslist'))
                    $resp->body = '{}';
                elseif (strpos($url, '/connectionthrottle'))
                    $resp->body = '{}';
                elseif (strpos($url, '/connectionlogging'))
                    $resp->body = '{}';
                elseif (strpos($url, '/contentcaching'))
                    $resp->body = '{}';
                elseif (strpos($url, '/alloweddomains'))
                    $resp->body = '{}';
                elseif (strpos($url, '/protocols'))
                    $resp->body = '{}';
                elseif (strpos($url, '/ssltermination'))
                    $resp->body = '{}';
                elseif (strpos($url, '/metadata'))
                    $resp->body = '{}';
                elseif (strpos($url, '/2000'))
                    $resp->body = <<<EOT
{"loadBalancer":{"id":2000,"name":"sample-loadbalancer","protocol":"HTTP","port":80,"algorithm":"RANDOM","status":"ACTIVE","timeout":30,"connectionLogging":{"enabled":true},"virtualIps":[{"id":1000,"address":"206.10.10.210","type":"PUBLIC","ipVersion":"IPV4"}],"nodes":[{"id":1041,"address":"10.1.1.1","port":80,"condition":"ENABLED","status":"ONLINE"},{"id":1411,"address":"10.1.1.2","port":80,"condition":"ENABLED","status":"ONLINE"}],"sessionPersistence":{"persistenceType":"HTTP_COOKIE"},"connectionThrottle":{"minConnections":10,"maxConnections":100,"maxConnectionRate":50,"rateInterval":60},"cluster":{"name":"c1.dfw1"},"created":{"time":"2010-11-30T03:23:42Z"},"updated":{"time":"2010-11-30T03:23:44Z"},"sourceAddresses":{"ipv6Public":"2001:4801:79f1:1::1/64","ipv4Servicenet":"10.0.0.0","ipv4Public":"10.12.99.28"}}}
EOT;
                else {
                    die("NEED TO DEFINE RESPONSE FOR $url\n");
                }
                
            } elseif (strpos($url, '/loadbalancers')) {
                $resp->body = <<<ENDLB
{"loadBalancers":[{"name":"one","id":1,"protocol":"HTTP","port":80}]}
ENDLB;
                $resp->status = 200;
            } elseif (preg_match('#servers/(\w|\-)+/metadata$#', $url)) {
                $resp->body = '{"metadata":{"foo":"bar","a":"1"}}';
                $resp->status = 200;
            } elseif (strpos($url, '/export')) { // domain export
                $resp->body = $this->async_response;
                $resp->status = 202;
            } elseif (strpos($url, 'limits/types')) {
                $resp->body = <<<ENDTYPES
{"limitTypes":["RATE_LIMIT","DOMAIN_LIMIT","DOMAIN_RECORD_LIMIT"]}
ENDTYPES;
                $resp->status = 202;
            } elseif (strpos($url, '/limits/DOMAIN_LIMIT')) { // individual limit
                $resp->status = 202;
                $resp->body = <<<ENDDOMLIMIT
{"absolute":{"limits":[{"name":"domains","value":500}]}}
ENDDOMLIMIT;
            } elseif (preg_match('/dns.*\/limits/', $url)) { // all limits
                $resp->status = 202;
                $resp->body = file_get_contents(__DIR__.'/dnslimits.json');
            } elseif (strpos($url, '/changes')) {
                $resp->body = <<<ENDCHANGES
{"changes":[],"from":"2013-02-20T00:00:00.000+0000","to":"2013-02-20T16:12:08.000+0000","totalEntries":0}
ENDCHANGES;
                $resp->status = 202;
            } elseif (strpos($url, '/domain/')) {
                $resp->body = $this->async_response;
                $resp->status = 202;
            } elseif (strpos($url, '/domains')) {
                $resp->body = <<<ENDDOM
{"domains":[{"name":"raxdrg.info","id":999919,"accountId":"TENANT-ID","emailAddress":"noname@dontuseemail.com","updated":"2013-02-15T16:30:28.000+0000","created":"2013-02-15T16:30:27.000+0000"}]}
ENDDOM;
                $resp->status = 200;
            } elseif (strpos($url, '/rdns/')) {
                $resp->body = <<<ENDRDNS
{"records":[{"name":"foobar.raxdrg.info","id":"PTR-548486","type":"PTR","data":"2001:4800:7811:513:199e:7e1e:ff04:be3f","ttl":900,"updated":"2013-02-18T20:24:50.000+0000","created":"2013-02-18T20:24:50.000+0000"},{"name":"foobar.raxdrg.info","id":"PTR-548485","type":"PTR","data":"166.78.48.90","ttl":900,"updated":"2013-02-18T20:24:34.000+0000","created":"2013-02-18T20:24:34.000+0000"}]}
ENDRDNS;
                $resp->status = 200;
            } elseif (strpos($url, '/extensions')) {
                $resp->body = file_get_contents($this->testDir.'/extensions.json');
                $resp->status = 200;
            } elseif (preg_match('/flavors\/[0-9a-f-]+$/', $url)) {
                $resp->body = file_get_contents($this->testDir.'/flavor.json');
                $resp->status = 200;
            } elseif (strpos($url, '/flavors')) {
                $resp->body = file_get_contents($this->testDir.'/flavors.json');
                $resp->status = 200;
            } elseif (strpos($url, '/instances/')) {
                $resp->body = file_get_contents($this->testDir.'/dbinstance.json');
                $resp->status = 200;
            } elseif (strpos($url, '/instances')) {
                $resp->body = '{"instances":[]}';
                $resp->status = 200;
            } elseif (strpos($url, '/volumes/')) {
                $resp->body = '{"volume":[]}';
                $resp->status = 200;
            } elseif (preg_match('/servers\/[0-9a-z\-]+\/rax-si-image-schedule/',$url)){
                $resp->body = file_get_contents($this->testDir.'/imageschedule.json');
                $resp->status = 204;
            } elseif (strpos($url, '/servers/')) {
                $resp->body = file_get_contents($this->testDir.'/server.json');
                $resp->status = 200;
            } elseif (strpos($url, 'EMPTY')) {
                $resp->body = NULL;
                $resp->status = 200;
            } elseif (strpos($url, 'BADJSON')) {
                $resp->body = '{"bad jjson';
                $resp->status = 200;
            } elseif (strpos($url, '/rdns')) {
                $resp->body = $this->async_response;
                $resp->status = 200;
            } elseif (strpos($url, '/images/detail')) {
                    $resp->body = <<<EOT
{"images":[{"OS-DCF:diskConfig":"AUTO","created":"2012-10-13T16:53:56Z","id":"a3a2c42f-575f-4381-9c6d-fcd3b7d07d17","links":[{"href":"https://dfw.servers.api.rackspacecloud.com/v2/658405/images/a3a2c42f-575f-4381-9c6d-fcd3b7d07d17","rel":"self"},{"href":"https://dfw.servers.api.rackspacecloud.com/658405/images/a3a2c42f-575f-4381-9c6d-fcd3b7d07d17","rel":"bookmark"},{"href":"https://dfw.servers.api.rackspacecloud.com/658405/images/a3a2c42f-575f-4381-9c6d-fcd3b7d07d17","rel":"alternate","type":"application/vnd.openstack.image"}],"metadata":{"arch":"x86-64","auto_disk_config":"True","com.rackspace__1__build_core":"1","com.rackspace__1__build_managed":"0","com.rackspace__1__build_rackconnect":"1","com.rackspace__1__options":"0","com.rackspace__1__visible_core":"1","com.rackspace__1__visible_managed":"0","com.rackspace__1__visible_rackconnect":"1","image_type":"base","org.openstack__1__architecture":"x64","org.openstack__1__os_distro":"org.centos","org.openstack__1__os_version":"6.0","os_distro":"centos","os_type":"linux","os_version":"6.0","rax_managed":"false","rax_options":"0"},"minDisk":10,"minRam":256,"name":"CentOS 6.0","progress":100,"status":"ACTIVE","updated":"2012-10-13T16:54:55Z"}]}
EOT;
            } elseif (preg_match('/TEST$/', $url)) {
                $resp->body = '{}';
                $resp->status = 200;
            } elseif (preg_match('/(TEST\?format=json)|(NON-CDN)/', $url)) {
                $resp->body = <<<EOT
[{"name":"test_obj_1","hash":"4281c348eaf83e70ddce0e07221c3d28","bytes":14,"content_type":"application\/octet-stream","last_modified":"2009-02-03T05:26:32.612278"},{"name":"test_obj_2","hash":"b039efe731ad111bc1b0ef221c3849d0","bytes":64,"content_type":"application\/octet-stream","last_modified":"2009-02-03T05:26:32.612278"}]
EOT;
                $resp->status = 200;
            } elseif (strpos($url, 'delimiter')) {
                $resp->body = '[{"subdir": "files/Pseudo1/"},{"subdir": "files/Pseudo2/"}]';
                $resp->status = 200;
            
            } elseif (strpos($url, '/queues')) { 
                
                /*** CLOUD QUEUES ***/
                
                $resp->status = 200;
                $file = null;
                $status = null;

                if (preg_match('#/queues(\?.+)?$#', $url)) {
                    // List queues
                    $file = 'list_queues';
                } elseif (preg_match('#/queues/(\w|\-)+/metadata$#', $url)) {
                    // Queue metadata
                    $file = 'queue_metadata';
                } elseif (preg_match('#/queues/(\w|\-)+/stats$#', $url)) {
                    // Queue stats
                    $file = 'queue_stats';
                } elseif (preg_match('#/queues/(\w|\-)+/messages\?marker\=1\&limit\=2?$#', $url)) {
                    $file = 'queue_exists';
                    $status = 204;
                } elseif (preg_match('#/queues/(\w|\-)+/messages(\?.+)?$#', $url)) {
                    // List messages
                    $file = 'list_messages';
                } elseif (preg_match('#/queues/(\w|\-)+/messages/(\w|\-)+(\?.+)?$#', $url)) {
                    // Get message
                    $file = 'get_message';
                } elseif (preg_match('#/queues/(\w|\-)+/claims/(\w|\-)+$#', $url)) {
                    // Get a claim
                   $file = 'get_claim';
                } 
                
                if (null !== $file) {
                    $resp->status = $status ?: 200;
                    $resp->body = file_get_contents(__DIR__ . '/Queues/Response/GET/' . $file . '.json');
                } else {
                    $resp->status = 404;
                    $resp->body = '{}';
                }
                
            } else {
                $resp->status = 404;
            }
        } 

        return $resp;
    }
}
