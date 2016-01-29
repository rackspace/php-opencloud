<?php

namespace Rackspace\ObjectStoreCDN\v1;

class Params extends \Rackspace\ObjectStore\v1\Params
{
    public function cdnEnabled()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-CDN-Enabled',
            'enum'     => ['True', 'False'],
        ];
    }

    public function cdnLogDelivery()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-Log-Retention',
            'enum'     => ['True', 'False'],
        ];
    }

    public function ttl()
    {
        return [
            'type'     => self::INT_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-Ttl',
        ];
    }
}