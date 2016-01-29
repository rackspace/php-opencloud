<?php

namespace Rackspace\ObjectStore\v1\Models;

use OpenStack\Common\Transport\Utils;
use Psr\Http\Message\ResponseInterface;

class BulkDeleteResponse
{
    /** @var int */
    public $notFoundCount;

    /** @var int */
    public $deletedCount;

    /** @var array */
    public $errors;

    /** @var string */
    public $responseStatus;

    /** @var string */
    public $responseBody;

    public function fromResponse(ResponseInterface $response)
    {
        $body = Utils::jsonDecode($response);

        $this->notFoundCount = $body['Number Not Found'];
        $this->deletedCount = $body['Number Deleted'];
        $this->responseStatus = $body['Response Status'];
        $this->responseBody = $body['Response Body'];

        foreach ($body['Errors'] as $a) {
            $this->errors[$a[0]] = $a[1];
        }
    }
}