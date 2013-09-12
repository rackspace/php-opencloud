<?php

/**
 * PHP OpenCloud library.
 *
 * @author    jami6682
 * @version   1.5.9
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 */

namespace OpenCloud\Smoke\Unit;

/**
 * Description of UnitInterface
 */
interface UnitInterface
{

    /**
     * Method for executing the main algorithm of the test.
     */
    public function main();
    
    /**
     * Allows for the setting up of any required object variables.
     */
    public function setup();
    
    /**
     * Allows for the deletion of any persistent resources created during 
     * execution.
     */
    public function teardown();
    
}