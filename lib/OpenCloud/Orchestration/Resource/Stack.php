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
     * URL of the template used to instantiate the stack.
     *
     * @var string
     */
    protected $template_url;

    /**
     * The template object used to instantiate the stack.
     *
     * @var stdClass
     */
     protected $template;

    /*
     * Environment variables used by the stack.
     *
     * @var array
     */
    protected $environment;

    /**
     * @var array
     */
    protected $files;

    /**
     * User-defined parameters to pass to the template.
     *
     * @var array
     */
    protected $parameters;

    /**
     * The amount of time that can pass before the stack status becomes
     * CREATE_FAILED; if DisableRollback is not set or is set to false, the
     * stack will be rolled back.
     *
     * @var string
     */
    protected $timeout_mins;


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
     * The normalized output array. This is cached between calls to refresh.
     *
     * @var array
     */
    protected $outputs;

    /**
     * When the stack was created.
     *
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
     * @var string
     */
    protected $stack_status;

    /**
     * @var array
     */
    protected $notification_topics;

    /**
     * @var array
     */
    protected $capabilities;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $template_description;

    /**
     * @var array
     */
    protected $links;

    protected static $json_name = "stack";
    protected static $url_resource = "stacks";

    /**
     * @return string
     */
    public function getStackName()
    {
        return $this->stack_name;
    }

    /**
     * @param string $stack_name
     */
    public function setStackName($stack_name)
    {
        $this->stack_name = $stack_name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStackName()
    {
        return $this->stack_name;
    }

    /**
     * @param string $stack_name
     */
    public function setStackName($stack_name)
    {
        $this->stack_name = $stack_name;
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
     * @param string $template_url
     */
    public function setTemplateUrl($template_url)
    {
        $this->template_url = $template_url;
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
     * @param stdClass $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param mixed $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
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
    public function getParameters()
    {
        return ( is_object($this->parameters) ? get_object_vars($this->parameters) : array() );
    }

    /**
     * Set the array of parameters (key-value pairs) that will be used by the stack.
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = (object) $parameters;
    }

    /**
     * Set an individual parameter to be used by the stack.
     *
     * @param $key
     * @param $value
     */
    public function setParameter($key, $value)
    {
        $this->parameters->$key = $value;
    }

    /**
     * @return string
     */
    public function getTimeoutMins()
    {
        return $this->timeout_mins;
    }

    /**
     * @param string $timeout_mins
     */
    public function setTimeoutMins($timeout_mins)
    {
        $this->timeout_mins = $timeout_mins;
    }

    /**
     * @return bool
     */
    public function getDisableRollback()
    {
        return $this->disable_rollback;
    }

    /**
     * @param boolean $disable_rollback
     */
    public function setDisableRollback($disable_rollback)
    {
        $this->disable_rollback = $disable_rollback;
    }

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
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }

    /**
     * @return array
     */
    public function getOutputs()
    {
        return $this->outputs;
    }

    /**
     * @return array
     */
    public function getNotificationTopics()
    {
        return $this->notification_topics;
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * State the stack is currently in.
     *
     * @return string
     * @see \OpenCloud\Orchestration\Constants\StackStatus
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
    public function getStackStatusReason()
    {
        return $this->stack_status_reason;
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

    public function getEvents()
    {
        return $this->getService()->resourceList('Event', null, $this);
    }

    public function getResources()
    {
        return $this->getService()->resourceList('Resource', null, $this);
    }

    public function getResource($data)
    {
        return new Resource($this->getService(), $data);
    }

    public function abandon()
    {
        $stackAbandon = $this->getService()->resource('Abandon', null, $this);
        $stackAbandon->delete();
    }

    // TODO: actions {resume, suspend}
}
