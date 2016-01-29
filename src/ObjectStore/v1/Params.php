<?php

namespace Rackspace\ObjectStore\v1;

class Params extends \OpenStack\ObjectStore\v1\Params
{
    public function extractArchive()
    {
        return [
            'type'        => self::STRING_TYPE,
            'location'    => self::QUERY,
            'description' => 'Extracts the uploaded file according to the specified format of the archive',
            'enum'        => ['tar', 'tar.gz', 'tar.bz2'],
            'sentAs'      => 'extract-archive',
        ];
    }

    public function accessLogDelivery()
    {
        return [
            'type'     => self::STRING_TYPE,
            'location' => self::HEADER,
            'sentAs'   => 'X-Container-Meta-Access-Log-Delivery',
            'enum'     => ['True', 'False'],
        ];
    }
}