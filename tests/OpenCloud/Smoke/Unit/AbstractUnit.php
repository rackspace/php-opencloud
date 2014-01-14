<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\OpenStack;
use OpenCloud\Common\Service\AbstractService;
use OpenCloud\Smoke\Enum;
use OpenCloud\Smoke\Step;
use OpenCloud\Smoke\Utils;

/**
 * Description of AbstractUnit
 * 
 * @link 
 */
abstract class AbstractUnit
{
    /**
     * The credentials cache filename.
     * 
     * @var string 
     */
    private $credentialsCacheFile;
    
    /**
     * The connection object which everything routes through.
     * 
     * @var OpenCloud\OpenStack
     */
    protected $connection;
    
    /**
     * The particular service that each unit uses.
     * 
     * @var OpenCloud\Common\Service 
     */
    protected $service;
     
    protected $currentStep;
    protected $currentSubStep;
    
    protected $includedUnits;
    
    /**
     * Factory method for instantiating the unit object, and executing its 
     * main algorithm.
     * 
     * @return UnitInterface
     */
    public static function factory(OpenStack $connection, array $includedUnits)
    {
        $unit = new static();
        
        $unit->setConnection($connection)
            ->setIncludedUnits($includedUnits);
        
        if (!$service = $unit->setupService()) {
            return false;
        }
        $unit->setService($service);
        
        // Run execution...
        $unit->main();
        
        // Clean stuff up if necessary...
        $unit->teardown();
        
        return $unit;
    }
    
    public function setConnection(OpenStack $connection)
    {
        $this->connection = $connection;
        return $this;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function setService(AbstractService $service)
    {
        $this->service = $service;
        return $this;
    }
    
    public function getService()
    {
        return $this->service;
    }
    
    public function setIncludedUnits(array $includedUnits)
    {
        $this->includedUnits = $includedUnits;
        return $this;
    }
    
    public function getIncludedUnits()
    {
        return $this->includedUnits;
    }
        
    public function getWaiterCallback()
    {
        return function($object) {
            if (!empty($object->error)) {
                var_dump($object->error); die;
            } else {
                $this->stepInfoDotter(
                    "Waiting on %s/%-12s %4s%%",
                    $object->name(),
                    $object->status(),
                    isset($object->progress) ? $object->progress : 0
                );
            }
        };
    }
    
    public function shouldDelete($string)
    {
        return preg_match('#(?:S|s)moke(?:T|t)est#', $string) !== false;
        //return stristr($string, Enum::GLOBAL_PREFIX) !== false;
    }
    
    public function prepend($string)
    {
        return Enum::GLOBAL_PREFIX . $string;
    }
    
    public function step()
    {
        $string = Utils::convertArgsToString(func_get_args());
        $count = (!$this->currentStep) ? 1 : $this->currentStep->getCount() + 1;
        $this->currentStep = Step::factory($string, $count);
    }
    
    public function subStep()
    {
        $string = Utils::convertArgsToString(func_get_args());
        return $this->createSubStep($string);
    }
    
    public function stepInfo()
    {
        $string = Utils::convertArgsToString(func_get_args());
        return $this->createSubStep($string, Step::TYPE_SPACER);
    }
    
    public function stepInfoDotter()
    {
        $string = Utils::convertArgsToString(func_get_args());
        return $this->createSubStep($string, Step::TYPE_DOTTER);
    }
    
    public function createSubStep($string, $outputType = null)
    {
        // Set basic properties
        $step = $this->currentStep->subStep($string)
            ->setOutputType($outputType);
        
        // If we have a preceding sub-step, we need to make sure our new one
        // is properly incremented
        if ($this->currentSubStep) {
            $step->setCount($this->currentSubStep->getCount() + 1);
        }
        
        $this->currentSubStep = $step;
        return $step->output();
    }
    
}