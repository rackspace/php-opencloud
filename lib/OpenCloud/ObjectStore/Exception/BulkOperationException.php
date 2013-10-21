<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Exception;

/**
 * Description of UploadArchiveException
 * 
 * @link 
 */
class BulkOperationException extends \Exception
{
    public function __construct(array $errors, \Exception $exception)
    {
        $output = '';
        
        foreach ($errors as $file => $message) {
            $output .= "$file: $message" . PHP_EOL;
        }
        
        parent::__construct(
            'These errors occurred while performing an archive upload: ' . $output
        );
    }
}