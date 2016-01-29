<?php

namespace Rackspace\ObjectStore\v1\Models;

use OpenStack\Common\Transport\Utils;
use Psr\Http\Message\ResponseInterface;

class BulkExtractResponse
{
    /** @var int */
    public $fileCount;

    /** @var array */
    public $errors;

    /** @var string */
    public $responseStatus;

    /** @var string */
    public $responseBody;

    public function fromResponse(ResponseInterface $response)
    {
        $body = Utils::jsonDecode($response);

        $this->fileCount = $body['Number Files Created'];
        $this->responseStatus = $body['Response Status'];
        $this->responseBody = $body['Response Body'];

        foreach ($body['Errors'] as $a) {
            $this->errors[$a[0]] = $a[1];
        }
    }
}