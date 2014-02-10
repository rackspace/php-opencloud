<?php

namespace OpenCloud\Images\Resource;

use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\Common\Exceptions\ForbiddenOperationException;
use OpenCloud\Common\Exceptions\ResourceNotFoundException;
use OpenCloud\Images\Enum\MemberStatus;

class Member extends AbstractSchemaResource
{
    protected static $url_resource = 'members';
    protected static $json_name = '';
    protected static $json_collection_name = 'members';

    private $allowedStates = array(
        MemberStatus::ACCEPTED,
        MemberStatus::PENDING,
        MemberStatus::REJECTED
    );

    public function updateStatus($status)
    {
        if (!in_array($status, $this->allowedStates)) {
            throw new \InvalidArgumentException(
                sprintf('Status must be one of these defined types: %s', implode($this->allowedStates, ','))
            );
        }

        $json = json_encode(array('status' => $status));

        $request = $this->getClient()->put($this->getUrl(), self::getJsonHeader(), $json);

        try {
            return $request->send();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();

            switch ($response->getStatusCode()) {
                case 403:
                    $exception = ForbiddenOperationException::factory($e);
                    break;
                case 404:
                    $exception = ResourceNotFoundException::factory($e);
                    break;
            }

            throw isset($exception) ? $exception : $e;
        }
    }

    public function delete()
    {
        return $this->getClient()->delete($this->getUrl());
    }
}