<?php declare(strict_types=1);

namespace Rackspace\Image\v1;

use OpenStack\Common\Api\AbstractApi;

class Api extends AbstractApi
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params;
    }

    /**
     * Returns information about GET /tasks HTTP operation
     *
     * @return array
     */
    public function getTasks()
    {
        return [
            'method' => 'GET',
            'path'   => '/tasks',
            'params' => [],
        ];
    }

    /**
     * Returns information about POST /tasks HTTP operation
     *
     * @return array
     */
    public function postTasks()
    {
        return [
            'method' => 'POST',
            'path'   => '/tasks',
            'params' => [
                'imageUuid'               => $this->params->imageUuidJson(),
                'input'                   => $this->params->inputJson(),
                'receivingSwiftContainer' => $this->params->receivingSwiftContainerJson(),
                'type'                    => $this->params->typeJson(),
            ],
        ];
    }

    /**
     * Returns information about GET /images HTTP operation
     *
     * @return array
     */
    public function getImages()
    {
        return [
            'method' => 'GET',
            'path'   => '/images',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /schemas/task HTTP operation
     *
     * @return array
     */
    public function getTask()
    {
        return [
            'method' => 'GET',
            'path'   => '/schemas/task',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /schemas/image HTTP operation
     *
     * @return array
     */
    public function getImage()
    {
        return [
            'method' => 'GET',
            'path'   => '/schemas/image',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /schemas/member HTTP operation
     *
     * @return array
     */
    public function getMember()
    {
        return [
            'method' => 'GET',
            'path'   => '/schemas/member',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /tasks/{taskID} HTTP operation
     *
     * @return array
     */
    public function getTaskID()
    {
        return [
            'method' => 'GET',
            'path'   => '/tasks/{taskID}',
            'params' => [
                'taskID' => $this->params->taskIDUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /schemas/members HTTP operation
     *
     * @return array
     */
    public function getMembers()
    {
        return [
            'method' => 'GET',
            'path'   => '/schemas/members',
            'params' => [],
        ];
    }

    /**
     * Returns information about GET /images/{image_id} HTTP operation
     *
     * @return array
     */
    public function getImageId()
    {
        return [
            'method' => 'GET',
            'path'   => '/images/{image_id}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{image_id} HTTP operation
     *
     * @return array
     */
    public function deleteImageId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/images/{image_id}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about PATCH /images/{image_id} HTTP operation
     *
     * @return array
     */
    public function patchImageId()
    {
        return [
            'method' => 'PATCH',
            'path'   => '/images/{image_id}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about POST /images/{image_id}/members HTTP operation
     *
     * @return array
     */
    public function postMembers()
    {
        return [
            'method'  => 'POST',
            'path'    => '/images/{image_id}/members',
            'jsonKey' => 'member',
            'params'  => [
                'imageId' => $this->params->imageIdUrl(),
                'member'  => $this->params->memberJson(),
            ],
        ];
    }

    /**
     * Returns information about PUT /images/{image_id}/tags/{tag} HTTP operation
     *
     * @return array
     */
    public function putTag()
    {
        return [
            'method' => 'PUT',
            'path'   => '/images/{image_id}/tags/{tag}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
                'tag'     => $this->params->tagUrl(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{image_id}/tags/{tag} HTTP operation
     *
     * @return array
     */
    public function deleteTag()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/images/{image_id}/tags/{tag}',
            'params' => [
                'imageId' => $this->params->imageIdUrl(),
                'tag'     => $this->params->tagUrl(),
            ],
        ];
    }

    /**
     * Returns information about PUT /images/{image_id}/members/{member_id} HTTP
     * operation
     *
     * @return array
     */
    public function putMemberId()
    {
        return [
            'method'  => 'PUT',
            'path'    => '/images/{image_id}/members/{member_id}',
            'jsonKey' => 'status',
            'params'  => [
                'imageId'  => $this->params->imageIdUrl(),
                'memberId' => $this->params->memberIdUrl(),
                'status'   => $this->params->statusJson(),
            ],
        ];
    }

    /**
     * Returns information about DELETE /images/{image_id}/members/{member_id} HTTP
     * operation
     *
     * @return array
     */
    public function deleteMemberId()
    {
        return [
            'method' => 'DELETE',
            'path'   => '/images/{image_id}/members/{member_id}',
            'params' => [
                'imageId'  => $this->params->imageIdUrl(),
                'memberId' => $this->params->memberIdUrl(),
            ],
        ];
    }

    /**
     * Returns information about GET /images/{image_id}/members/{member_id} HTTP
     * operation
     *
     * @return array
     */
    public function getMemberId()
    {
        return [
            'method' => 'GET',
            'path'   => '/images/{image_id}/members/{member_id}',
            'params' => [
                'imageId'  => $this->params->imageIdUrl(),
                'memberId' => $this->params->memberIdUrl(),
            ],
        ];
    }
}
