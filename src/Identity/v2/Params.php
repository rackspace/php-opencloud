<?php

namespace Rackspace\Identity\v2;

use OpenStack\Common\Api\AbstractParams;

class Params extends AbstractParams
{
    public function username()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'path'     => 'auth.RAX-KSKEY:apiKeyCredentials',
            'required' => true,
        ];
    }

    public function apiKey()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::JSON,
            'path'     => 'auth.RAX-KSKEY:apiKeyCredentials',
            'required' => true,
        ];
    }
}