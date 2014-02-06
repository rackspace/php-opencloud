<?php

namespace OpenCloud\Images\Resource;

use OpenCloud\Common\PersistentObject;

class Image extends PersistentObject implements ImageInterface
{
    protected static $url_resource = 'images';
    protected static $json_collection_name = 'images';

    public function update()
    {
        
    }
} 