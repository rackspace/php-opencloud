<?php

namespace OpenCloud\Images;

use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Images\Resource\Image;

class Service extends CatalogService
{
    public function listImages(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Image::jsonName())->addQuery($params);

        return $this->resourceList('Image', $url);
    }

    public function getImage($imageId)
    {
        return $this->resource('Image', $imageId);
    }

    /**
     * Iterator use only.
     *
     * @param $data
     * @return object
     */
    public function image($data)
    {
        return $this->resource('Image', $data);
    }

    public function getImagesSchema()
    {

    }

    public function getImageSchema()
    {

    }

    public function getMembersSchema()
    {

    }

    public function getMemberSchema()
    {

    }
}