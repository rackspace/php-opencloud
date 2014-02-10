<?php

namespace OpenCloud\Images;

use OpenCloud\Common\Constants\Header;
use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Images\Resource\Image;
use OpenCloud\Images\Resource\Schema\Schema;

class Service extends CatalogService
{
    const DEFAULT_TYPE = 'image';
    const DEFAULT_NAME = 'cloudImages';

    const PATCH_CONTENT_TYPE = 'application/openstack-images-v2.1-json-patch';

    public function getPatchHeaders()
    {
        return array(Header::CONTENT_TYPE => self::PATCH_CONTENT_TYPE);
    }

    public function listImages(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Image::resourceName())->setQuery($params);

        return $this->resourceList('Image', $url);
    }

    public function getImage($imageId)
    {
        $image = $this->resource('Image');
        $image->setId($imageId);
        $image->refresh();

        return $image;
    }

    /**
     * Iterator use only.
     *
     * @param $data
     * @return object
     */
    public function image($data)
    {
        $image = $this->resource('Image');
        $image->setData((array) $data);

        return $image;
    }

    protected function getSchemaUrl($path)
    {
        $url = clone $this->getUrl();
        return $url->addPath('schemas')->addPath($path);
    }

    public function getImagesSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('images'))->send()->json();
        return Schema::factory($data);
    }

    public function getImageSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('image'))->send()->json();
        return Schema::factory($data);
    }

    public function getMembersSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('members'))->send()->json();
        return Schema::factory($data);
    }

    public function getMemberSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('member'))->send()->json();
        return Schema::factory($data);
    }
}