<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
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
    public function setupService();
    
    /**
     * Allows for the deletion of any persistent resources created during 
     * execution.
     */
    public function teardown();
    
}