<?php

/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke;

/**
 * Description of Step
 * 
 * @link 
 */
class Step
{
    const SPACE_PREFIX = '   ';
    const TYPE_SPACER  = 'spacer';
    const TYPE_DOTTER  = 'dotter';
    
    /**
     * @var string  The content to be outputted.
     */
    public $message;
    
    /**
     * @var string  Either default, `spacer` or `dotter`. 
     */
    public $outputType;
    
    /**
     * @var array  Child steps.
     */
    public $steps = array();
    
    /**
     * @var int  How far down the rabbit hole is this step?
     */
    public $depth = 0;
    
    /**
     * @var int  The count for this step. 
     */
    public $count = 1;
    
    /**
     * @var Step  The parent of this step.
     */
    public $parent;
    
    /**
     * Factory method for outputting basic messages.
     */
    public static function factory($message, $count = 1)
    {
        $step = new self();
        return $step->setMessage($message)->setCount($count)->output();
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function setOutputType($outputType)
    {
        $this->outputType = $outputType;
        return $this;
    }
    
    public function getOutputType()
    {
        return $this->outputType;
    }
    
    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }
    
    public function getDepth()
    {
        return $this->depth;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    public function setParent(Step $step)
    {
        $this->parent = $step;
        return $this;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getOutput()
    {
        switch ($this->getOutputType()) {
            default:
                $leadingLine  = true;
                $outputString = sprintf('%d. %s', $this->getCount(), $this->getMessage());  
                break;
            case self::TYPE_DOTTER:
                $outputString = sprintf('... %s', $this->getMessage());
                break;
            case self::TYPE_SPACER:
                $outputString = sprintf('%s', $this->getMessage());
                break;
        }
        
        return ((isset($leadingLine)) ? PHP_EOL : '') 
            . $this->computeSpacePrefix() . $outputString;
    }    
    
    private function computeSpacePrefix()
    {
        return str_repeat(self::SPACE_PREFIX, $this->getDepth() + 1);
    }
    
    public function output()
    {
        Utils::log($this->getOutput());
        
        if (null !== ($parent = $this->getParent())) {
            $parent->steps[] = $this;
        }
        
        return $this;
    }
    
    public function subStep($message)
    {
        $subStep = new self();
        $subStep->setMessage($message)
                ->setDepth($this->getDepth() + 1)
                ->setParent($this);
        
        return $subStep;
    }
    
    
    /*** FACTORY METHODS ***/
    
    public function stepInfo()
    {
        $string = Utils::convertArgsToString(func_get_args());
        return $this->createSubStep($string, self::TYPE_SPACER);
    }
    
    public function stepInfoDotter()
    {
        $string = Utils::convertArgsToString(func_get_args());
        return $this->createSubStep($string, self::TYPE_DOTTER);
    }
    
    public function createSubStep($string, $outputType = null)
    {
        return $this->subStep($string)
            ->setOutputType($outputType)
            ->setCount($this->getCount() + 1)
            ->output();
    }
    
}