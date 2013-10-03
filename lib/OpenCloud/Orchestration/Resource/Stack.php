<?php
/**
 * Defines an OpenStack Heat Stack
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Stephen Sugden <openstack@stephensugden.com>
 * @author Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Orchestration\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Exceptions\CreateError;

/**
 * The Stack class requires a CloudFormation template and may contain additional
 * parameters for that template.
 *
 * A Stack is always associated with an (Orchestration) Service.
 *
 * @codeCoverageIgnore
 */
class Stack extends PersistentObject
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
     * A list of Parameter structures that specify input parameters for the stack.
     *
     * @var mixed
     */
    protected $parameters;

    /**
     * Structure containing the template body.
     *
     * @var string
     */
    protected $template;

    /**
     * Set to true to disable rollback of the stack if stack creation failed.
     *
     * @var bool
     */
    protected $disable_rollback = false;

    /**
     * Description of stack.
     *
     * @var string
     */
    protected $description;

    /**
     * @var type
     */
    protected $stack_status_reason;

    /**
     * @var type
     */
    protected $outputs;

    /**
     * @var type
     */
    protected $creation_time;

    /**
     * Array of stack lists.
     *
     * @var array
     */
    protected $links;

    /**
     * The list of capabilities that you want to allow in the stack.
     *
     * @var mixed
     */
    protected $capabilities;

    /**
     * The Simple Notification Service topic ARNs to publish stack related events.
     *
     * @var mixed
     */
    protected $notification_topics;

    /**
     * The amount of time that can pass before the stack status becomes
     * CREATE_FAILED; if DisableRollback is not set or is set to false, the
     * stack will be rolled back.
     *
     * @var string
     */
    protected $timeout_mins = 60;

    /**
     * @var type
     */
    protected $stack_status;

    /**
     * @var type
     */
    protected $updated_time;

    /**
     * @var type
     */
    protected $template_description;

    protected static $json_name = "stack";
    protected static $url_resource = "stacks";
    protected $createKeys = array(
        'template',
        'stack_name'
    );

    /**
     * {@inheritDoc}
     */
    protected function createJson()
    {
        $pk = $this->primaryKeyField();

        if (!empty($this->{$pk})) {
            throw new CreateError(sprintf(
                'Stack is already created and has ID of %s',
                $this->$pk
            ));
        }

        $object = (object) array(
            'disable_rollback' => (bool) $this->disable_rollback,
            'timeout_mins' => $this->timeout_mins
        );

        foreach ($this->createKeys as $property) {
            if (empty($this->$property)) {
                throw new CreateError(sprintf(
                    'Cannot create Stack with null %s',
                    $property
                ));
            } else {
                $object->$property = $this->$property;
            }
        }

        if (null !== $this->parameters) {
            $object->parameters = $this->parameters;
        }

        return $object;
    }

    public function getName()
    {
        return $this->stack_name;
    }

    public function getStatus()
    {
        return $this->stack_status;
    }

    public function resource($id = null) 
    {
        $resource = new Resource($this->getService());
        $resource->setParent($this);
        $resource->populate($id);
        return $resource;
    }

    public function getResources()
    {
        return $this->getService()->collection(
            'OpenCloud\Orchestration\Resource\Resource',
            $this->url('resources'),
            $this
        );
    }

    public function output($name)
    {
        $outputs = $this->outputs;
        if (is_array($outputs) && array_key_exists($name, $outputs)) {
            return $outputs[$name];
        }
    }

    public function getOutputs()
    {
        if (isset($this->_outputs)) {
            return $this->_outputs;
        }
        if (!is_array($this->outputs)) {
            return;
        }
        $this->_outputs = array();
        foreach($this->outputs as $output) {
            $this->_outputs[$output->output_key] = $output->output_value;
        }
        return $this->_outputs;
    }
}
