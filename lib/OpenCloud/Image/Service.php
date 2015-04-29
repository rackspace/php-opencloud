<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Image;

use OpenCloud\Common\Service\CatalogService;
use OpenCloud\Image\Resource\Image;
use OpenCloud\Image\Resource\Schema\Schema;

/**
 * Service class that represents OpenStack Glance / Rackspace Cloud Images
 *
 * @package OpenCloud\Images
 */
class Service extends CatalogService
{
    const DEFAULT_TYPE = 'image';
    const DEFAULT_NAME = 'cloudImages';

    /**
     * This operation returns images you created, shared images that you accepted, and standard images.
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function listImages(array $params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Image::resourceName())->setQuery($params);

        return $this->resourceList('Image', $url);
    }

    /**
     * Returns details for a specific image.
     *
     * @param $imageId
     * @return object
     */
    public function getImage($imageId)
    {
        $image = $this->resource('Image');
        $image->setId($imageId);
        $image->refresh();

        return $image;
    }

    /**
     * For iterator use only.
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

    /**
     * A convenience method which returns the URL needed to retrieve schemas.
     *
     * @param $path
     * @return \Guzzle\Http\Url
     */
    protected function getSchemaUrl($path)
    {
        $url = clone $this->getUrl();

        return $url->addPath('schemas')->addPath($path);
    }

    /**
     * Return a JSON schema for a collection of image resources
     *
     * @return Schema
     */
    public function getImagesSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('images'))->send()->json();

        return Schema::factory($data);
    }

    /**
     * Return a JSON schema for an individual image resource
     *
     * @return Schema
     */
    public function getImageSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('image'))->send()->json();

        return Schema::factory($data);
    }

    /**
     * Return a JSON schema for a collection of member resources
     *
     * @return Schema
     */
    public function getMembersSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('members'))->send()->json();

        return Schema::factory($data);
    }

    /**
     * Return a JSON schema for an individual member resource
     *
     * @return Schema
     */
    public function getMemberSchema()
    {
        $data = $this->getClient()->get($this->getSchemaUrl('member'))->send()->json();

        return Schema::factory($data);
    }
}
