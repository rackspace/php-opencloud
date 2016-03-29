<?php declare(strict_types=1);

namespace Rackspace\Test\ObjectStore\v1;

use GuzzleHttp\Psr7\Response;
use OpenCloud\ObjectStore\v1\Api;
use OpenCloud\Test\TestCase;
use Rackspace\ObjectStore\v1\Models\BulkExtractResponse;

class BulkExtractResponseTest extends TestCase
{
    /** @var BulkExtractResponse */
    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->response = new BulkExtractResponse($this->client->reveal(), new Api());
    }

    public function test_it_populates_from_response()
    {
        $json = <<<EOT
{
  "Number Files Created": 10,
  "Response Status": "400 Bad Request",
  "Errors": [
    [
      "/v1/AUTH_test/test_cont/big_file.wav",
      "413 Request Entity Too Large"
    ]
  ],
  "Response Body": ""
}
EOT;

        $r = new Response(200, [], $json);
        $this->response->fromResponse($r);

        $this->assertEquals(10, $this->response->fileCount);
        $this->assertEquals("400 Bad Request", $this->response->responseStatus);
        $this->assertEquals("", $this->response->responseBody);

        $errors = ["/v1/AUTH_test/test_cont/big_file.wav" => "413 Request Entity Too Large"];
        $this->assertEquals($errors, $this->response->errors);
    }
}