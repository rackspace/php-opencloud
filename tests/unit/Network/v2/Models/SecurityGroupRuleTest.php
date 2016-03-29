<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenCloud\Test\TestCase;
use Rackspace\Network\v2\Api;
use Rackspace\Network\v2\Models\SecurityGroupRule;

class SecurityGroupRuleTest extends TestCase
{
    /** @var SecurityGroupRule */
    private $securityGroupRule;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->securityGroupRule = new SecurityGroupRule($this->client->reveal(), new Api());
        $this->securityGroupRule->id = 'id';
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'security-group-rules/id', null, [], new Response(202));

        $this->securityGroupRule->delete();
    }

    public function test_it_retrieves()
    {
        $this->setupMock('GET', 'security-group-rules/id', null, [], 'SecurityGroupRule');

        $this->securityGroupRule->retrieve();
    }
}