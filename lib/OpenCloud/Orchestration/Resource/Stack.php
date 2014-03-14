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

namespace OpenCloud\Orchestration\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Resource\PersistentResource;
use OpenCloud\Orchestration\Enum\Action;
use OpenCloud\Orchestration\Enum\Status;

/**
 * A stack is a group of resources (servers, load balancers, databases, and so
 * forth) combined to fulfill a useful purpose. Based on a template, Heat
 * orchestration engine creates an instantiated set of resources (a stack) to
 * run the application framework or component specified (in the template). A
 * stack is a running instance of a template. The result of creating a stack is
 * a deployment of the application framework or component.
 *
 */
class Stack extends PersistentResource
{
    /**
     * Whether a failure during stack creation should delete all previously-
     * created resources in that stack.
     *
     * @var boolean
     */
    protected $disable_rollback;

    /**
     * @var string
     */
    protected $stack_status_reason;

    /**
     * @var array
     */
    protected $outputs;

    /**
     * The normalized output array
     *
     * @var array
     */
    protected $_outputs;

    /**
     * @var string
     */
    protected $creation_time;

    /**
     * When the stack was last updated.
     *
     * @var string
     */
    protected $updated_time;

    /**
     * The amount of time that can pass before the stack status becomes
     * CREATE_FAILED; if DisableRollback is not set or is set to false, the
     * stack will be rolled back.
     *
     * @var string
     */
    protected $timeout_mins;

    /**
     * @var string
     */
    protected $stack_status;

    /*
     * @var string
     */
    protected $template_url;

    /*
     * @var array
     */
    protected $environment;

    /**
     * @var array
     */
    protected $files;

    /**
     * @var array
     */
    protected $links;

    /**
     * Identifier of stack.
     *
     * @var string
     */
    protected $id;

    /**
     * The name associated with the stack. Must be unique within your account,
     * contain only alphanumeric characters (case sensitive) and start with an
     * alpha character. Maximum length of the name is 255 characters.
     *
     * @var string
     */
    protected $stack_name;

    /**
     * User-defined parameters to pass to the template.
     *
     * @var array
     */
    protected $parameters;

    /**
     * @var \stdClass
     */
    protected $template;

    /**
     * When the stack was created.
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->creation_time;
    }

    /**
     * @param boolean $disable_rollback
     */
    public function setDisableRollback($disable_rollback)
    {
        $this->disable_rollback = $disable_rollback;
    }

    /**
     * @return bool
     */
    public function getDisableRollback()
    {
        return $this->disable_rollback;
    }

    /**
     * @param mixed $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the array of parameters (key-value pairs) that will be used by the stack.
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Set an individual parameter to be used by the stack.
     *
     * @param $key
     * @param $value
     */
    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getOutputs()
    {
        if (!isset($this->outputs) || !is_array($this->outputs)) {
            return array();
        }
        if (!isset($this->_outputs)) {
            $this->_outputs = array();
            foreach ($this->outputs as $output) {
                $this->_outputs[$output->output_key] = $output->output_value;
            }
        }
        return $this->_outputs;
    }

    /**
     * Get a specific output from the stack.
     *
     * @param string $key
     * @return mixed
     */
    public function getOutput($key)
    {
        $outputs = $this->getOutputs();
        return isset($outputs[$key]) ? $outputs[$key] : null;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param string $stack_name
     */
    public function setName($stack_name)
    {
        $this->stack_name = $stack_name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->stack_name;
    }

    /**
     * State the stack is currently in.
     *
     * @return string
     * @see \OpenCloud\Orchestration\Enum\StackStatus
     */
    public function getStackStatus()
    {
        return $this->stack_status;
    }

    /**
     * Reason why the stack is in the current status.
     *
     * @return string
     */
    public function getStatusReason()
    {
        return $this->stack_status_reason;
    }

    /**
     * @param string $template_url
     */
    public function setTemplateUrl($template_url)
    {
        $this->template_url = $template_url;
    }

    /**
     * URL of the stack template. Will be ignored if template is also supplied.
     *
     * @return string
     */
    public function getTemplateUrl()
    {
        return $this->template_url;
    }

    /**
     * @param \OpenCloud\Orchestration\Resource\Template $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Object representing the template used by the stack.
     *
     * @return \OpenCloud\Orchestration\Resource\Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $timeout_mins
     */
    public function setTimeoutMins($timeout_mins)
    {
        $this->timeout_mins = $timeout_mins;
    }

    /**
     * @return string
     */
    public function getTimeoutMins()
    {
        return $this->timeout_mins;
    }

    /**
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    protected static $json_name = "stack";
    protected static $url_resource = "stacks";

    protected $createKeys = array(
        'stack_name',
        'template_url',
        'template',
        'environment',
        'files',
        'parameters',
        'timeout_mins',
        'disable_rollback'
    );

    protected $updateKeys = array(
        'template_url',
        'template',
        'environment',
        'files',
        'parameters',
        'timeout_mins',
        'disable_rollback'
    );

    /**
     * {@inheritDoc}
     */
    protected function updateJson($params = array())
    {

        $object = (object)array();

        foreach ($this->updateKeys as $key) {
            if (isset($params[$key])) {
                $object->$key = $params[$key];
            }
            elseif (isset($this->$key)) {
                $object->$key = $this->$key;
            }
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     *
     * The stack-create command does not nest stack properties under a top level "stack" key.
     */
    protected function createJson()
    {
        $data = parent::createJson();
        return $data->{$this->jsonName()};
    }

    public function eventList()
    {
        /** @var \OpenCloud\Orchestration\Service $service */
        return $this->resourceList('event');
    }

    public function getEvent($data)
    {
        return new Event($this->getService(), $data);
    }

    public function getResources()
    {
        return $this->resourceList('resource');
    }

    public function getResource($data)
    {
        return new Resource($this->getService(), $data);
    }

    public function getStatus()
    {
        return $this->stack_status;
    }

    /**
     * Return boolean indicating whether the stack is in a FAILED state.
     *
     * @return bool
     */
    public function isFailed()
    {
        return strpos($this->getStatus(), 'FAILED') !== false;
    }

    /**
     * @param $resourceName
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    protected function resourceList($resourceName)
    {
        /** @var \OpenCloud\Orchestration\Service $service */
        $service = $this->getService();
        return $service->resourceList($resourceName, null, $this);
    }
}
