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
     * Object representing the template used by the stack.
     *
     * @var \OpenCloud\Orchestration\Resource\Template
     */
    protected $template;

    /**
     * Whether a failure during stack creation should delete all previously-
     * created resources in that stack.
     *
     * @var boolean
     */
    protected $disable_rollback;

    /**
     * Reason why the stack is in the current status.
     *
     * @var string
     */
    protected $stack_status_reason;

    /**
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
     * The amount of time that can pass before the stack status becomes
     * CREATE_FAILED; if DisableRollback is not set or is set to false, the
     * stack will be rolled back.
     *
     * @var string
     */
    protected $timeout_mins;

    /**
     * State the stack is currently in.
     *
     * @var string
     */
    protected $stack_status;

    /*
     * URL of the stack template. Will be ignored if template is also supplied.
     *
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
            if (isset($this->$key)) {
                $object->$key = $this->$key;
            }
        }

        return $object;
    }

}
