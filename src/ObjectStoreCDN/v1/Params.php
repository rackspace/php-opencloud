<?php declare(strict_types=1);

namespace Rackspace\ObjectStoreCDN\v1;

class Params extends \Rackspace\ObjectStore\v1\Params
{
    public function cdnEnabled(): array
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-CDN-Enabled',
            'enum'     => ['True', 'False'],
        ];
    }

    public function cdnLogDelivery(): array
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-Log-Retention',
            'enum'     => ['True', 'False'],
        ];
    }

    public function ttl(): array
    {
        return [
            'type'     => self::INT_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-Ttl',
        ];
    }
}