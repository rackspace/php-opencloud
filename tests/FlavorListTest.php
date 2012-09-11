<?php
/**
 * Unit Tests
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

require_once('flavorlist.inc');
require_once('compute.inc');
require_once('stub_conn.inc');
require_once('stub_service.inc');

class FlavorListTest extends PHPUnit_Framework_TestCase
{
    private
        $flist,
        $compute;
    public function __construct() {
        $conn = new StubConnection('http://example.com', 'SECRET');
        $this->compute = new OpenCloud\Compute(
            $conn,
            'cloudServersOpenStack',
            'DFW',
            'publicURL'
        );
        $this->flist = $this->compute->FlavorList();
    }
	/**
	 * Tests
	 */
	public function testNext() {
        $this->assertEquals('OpenCloud\Compute\FlavorList', get_class($this->flist));
        $fl = new OpenCloud\Compute\FlavorList($this->compute,
            array(
            	new OpenCloud\Compute\Flavor($this->compute),
            	new OpenCloud\Compute\Flavor($this->compute)));
        $this->assertEquals(2, $fl->Size());
        $this->assertEquals('OpenCloud\Compute\Flavor', get_class($fl->Next()));
        $this->assertEquals('OpenCloud\Compute\Flavor', get_class($fl->Next()));
        $this->assertEquals(FALSE, $fl->Next());
	}
}
