<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Upload;

use OpenCloud\Common\Exceptions\RuntimeException;
use Guzzle\Http\ReadLimitEntityBody;
use Guzzle\Http\EntityBody;
use Guzzle\Batch\BatchBuilder;

/**
 * Description of ConcurrentTransfer
 * 
 * @link 
 */
class ConcurrentTransfer extends AbstractTransfer
{
    
    public function setup()
    {
        parent::setup();

        if (!$this->entityBody->isLocal()) {
            throw new RuntimeException('The entity data must be a local file stream when using concurrent transfers .');
        }

        if (empty($this->options['concurrency'])) {
            throw new RuntimeException('The `concurrency` option must be specified when using concurrent transfers.');
        }
        
        return $this;
    }
    
    public function transfer()
    {
        $totalParts = (int) ceil($this->entityBody->getContentLength() / $this->partSize);
        $workers = min($totalParts, $this->options['concurrency']);
        $parts = $this->collectParts($workers);
        
        while ($this->transferState->count() < $totalParts) {
            
            $completedParts = $this->transferState->count();
            $requests = array();
            
            // Iterate over number of workers until total completed parts is what we need it to be
            for ($i = 0; $i < $workers && ($completedParts + $i) < $totalParts; $i++) {
                
                // Offset is the current pointer multiplied by the standard chunk length
                $offset = ($completedParts + $i) * $this->partSize;
                $parts[$i]->setOffset($offset);
                
                // If this segment is empty (i.e. buffering a half-full chunk), break the iteration
                if ($parts[$i]->getContentLength() == 0) {
                    break;
                }

                // Add this to the request queue for later processing
                $requests[] = UploadPart::createRequest(
                    $parts[$i], 
                    $this->transferState->count() + $i + 1, 
                    $this->client, 
                    $this->options
                );
            }

            // Iterate over our queued requests and process them
            foreach ($this->client->send($requests) as $response) {
                // Add this part to the TransferState
                $this->transferState->addPart(UploadPart::fromResponse($response));
            }
        }
    }
    
    private function collectParts($workers)
    {
        $uri = $this->entityBody->getUri();
        
        $array = array(new ReadLimitEntityBody($this->entityBody, $this->partSize));
        
        for ($i = 1; $i < $workers; $i++) {
        	// Need to create a fresh EntityBody, otherwise you'll get weird 408 responses
            $array[] = new ReadLimitEntityBody(new EntityBody(fopen($uri, 'r')), $this->partSize);
        }

        return $array;
    }
    
}