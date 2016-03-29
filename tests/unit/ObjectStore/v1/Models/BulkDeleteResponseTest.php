<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStore\v1;

use GuzzleHttp\Psr7\Response;
use OpenCloud\ObjectStore\v1\Api;
use OpenCloud\Test\TestCase;
use Rackspace\ObjectStore\v1\Models\BulkDeleteResponse;

class BulkDeleteResponseTest extends TestCase
{
    /** @var BulkDeleteResponse */
    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->response = new BulkDeleteResponse($this->client->reveal(), new Api());
    }

    public function test_it_populates_from_response()
    {
        $json = <<<EOT
{
  "Number Not Found": 0,
  "Response Status": "400 Bad Request",
  "Errors": [
    [
      "/v1/AUTH_test/non_empty_container",
      "409 Conflict"
    ]
  ],
  "Number Deleted": 0,
  "Response Body": ""
}
EOT;

        $r = new Response(200, [], $json);
        $this->response->fromResponse($r);

        $this->assertEquals(0, $this->response->notFoundCount);
        $this->assertEquals(0, $this->response->deletedCount);
        $this->assertEquals("400 Bad Request", $this->response->responseStatus);
        $this->assertEquals("", $this->response->responseBody);

        $errors = ["/v1/AUTH_test/non_empty_container" => "409 Conflict"];
        $this->assertEquals($errors, $this->response->errors);
    }
}