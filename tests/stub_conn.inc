<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

require_once('openstack.inc');

if (!defined('TESTDIR')) define('TESTDIR','.');

/**
 * This is a stub Connection class that bypasses the actual connections
 */
class StubConnection extends OpenCloud\OpenStack
{
	public function Request($url, $method="GET", $headers=array(), $body=NULL) {
		$resp = new OpenCloud\BlankResponse();
		if ($method == 'POST') {
			$resp->status = 200;
			if (strpos($url, '/action')) {
			    if ('{"rescue' == substr($body, 0, 8))
			        $resp->body =
			            file_get_contents(TESTDIR.'/server-create.json');
			    else
    				$resp->body = '';
			}
			elseif (strpos($url, '/token'))
				$resp->body = file_get_contents(TESTDIR.'/connection.json');
			elseif (preg_match('/root$/', $url))
				$resp->body = '{"user":{"name":"root","password":"foo"}}';
			elseif (strpos($url, '/databases')) {
				$resp->body = '{to be filled in}';
				$resp->status = 202;
			}
			elseif (strpos($url, '/loadbalancers')) {
				$resp->body = <<<ENDLB
{"loadBalancer":{
  "id":"123",
  "name":"NONAME"
}}
ENDLB;
				$resp->status = 202;
			}
			elseif (strpos($url, 'network'))
				$resp->body = <<<ENDNW
{"network":{"id":"1","cidr":"192.168.0.0/24","label":"foo"}}
ENDNW;
			elseif (strpos($url, '/instances'))
				$resp->body = file_get_contents(TESTDIR.'/dbinstance-create.json');
			else
				$resp->body = file_get_contents(TESTDIR.'/server-create.json');
		}
		elseif ($method == 'DELETE') {
			$resp->status = 202;
		}
		elseif (strpos($url, '/os-volume_attachments/')) {
			$resp->body = <<<ENDATT
{"volumeAttachment":{"volumeId":"FOO"}}
ENDATT;
			$resp->status = 200;
		}
		elseif (strpos($url, '/os-volume_attachments')) {
		    $resp->body = <<<ENDATTLIST
{"volumeAttachments": []}
ENDATTLIST;
            $resp->status = 200;
		}
		elseif (strpos($url, 'os-networksv2')) {
			$resp->body = NULL;
			$resp->status = 200;
		}
		elseif (preg_match('/loadbalancers\/.*\/stats$/', $url)) {
			$resp->body = <<<ENDLBSTATS
{"connectTimeOut":10,"connectError":20,"connectFailure":30,"dataTimedOut":40,
"keepAliveTimedOut":50,"maxConn":60}
ENDLBSTATS;
		}
		elseif (strpos($url, '/loadbalancers/')) {
			$resp->status = 200;
			if (strpos($url, '/virtualips'))
				$resp->body = '{}';
			elseif (strpos($url, '/nodes'))
				$resp->body = '{}';
			elseif (strpos($url, '/sessionpersistence'))
				$resp->body = '{}';
			elseif (strpos($url, '/errorpage'))
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
			else
				die("NEED TO DEFINE RESPONSE FOR $url\n");
		}
		elseif (strpos($url, '/loadbalancers')) {
			$resp->body = <<<ENDLB
{"loadBalancers":[{"name":"one","id":1,"protocol":"HTTP","port":80}]}
ENDLB;
			$resp->status = 200;
		}
		elseif (strpos($url, '/metadata')) {
			$resp->body = NULL;
			$resp->status = 200;
		}
		elseif (strpos($url, '/extensions')) {
		    $resp->body = file_get_contents(TESTDIR.'/extensions.json');
		    $resp->status = 200;
		}
		elseif (preg_match('/flavors\/[0-9a-f-]+$/', $url)) {
			$resp->body = file_get_contents(TESTDIR.'/flavor.json');
			$resp->status = 200;
		}
		elseif (strpos($url, '/flavors')) {
			$resp->body = file_get_contents(TESTDIR.'/flavors.json');
			$resp->status = 200;
		}
		elseif (strpos($url, '/instances/')) {
			$resp->body = file_get_contents(TESTDIR.'/dbinstance.json');
			$resp->status = 200;
		}
		elseif (strpos($url, '/instances')) {
			$resp->body = '{"instances":[]}';
			$resp->status = 200;
		}
		elseif (strpos($url, '/volumes/')) {
			$resp->body = <<<ENDVOL
{"volume":[]}
ENDVOL;
			$resp->status = 200;
		}
		elseif (strpos($url, '/servers/')) {
			$resp->body = file_get_contents(TESTDIR.'/server.json');
			$resp->status = 200;
		}
		elseif (strpos($url, 'EMPTY')) {
			$resp->body = NULL;
			$resp->status = 200;
		}
		elseif (strpos($url, 'BADJSON')) {
			$resp->body = '{"bad jjson';
			$resp->status = 200;
		}
		else
			$resp->status = 404;

		return $resp;
	}
}
/**
 * stub classes for testing the request() method (which is overridden in the
 * StubConnection class used for testing everything else).
 */
class StubRequest extends OpenCloud\CurlRequest {
    public
        $url;
    public function __construct($url, $method='GET') {
        $this->url = $url;
        parent::__construct($url, $method);
    }
    public function Execute() {
        switch($this->url) {
        case '401':
        case '403':
        case '413':
            return new OpenCloud\BlankResponse(array(
                'status' => $this->url+0
           ));
        default:
            return new OpenCloud\BlankResponse(array(
                'status' => 200
           ));
        }
    }
}
