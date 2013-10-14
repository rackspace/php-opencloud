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
        $parts = $this->prepareParts($workers);
        
        $requestQueue = array();
        
        while ($this->transferState->count() < $totalParts) {
            
            $completedParts = $this->transferState->count();
            
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
                $requestQueue[] = UploadPart::createRequest(
                    $parts[$i], 
                    $this->transferState->count() + 1 + $i, 
                    $this->client, 
                    $this->options
                );
            }
            
            // Iterate over our queued requests and process them
            foreach ($this->client->send($requestQueue) as $response) {
                // Add this part to the TransferState
                $this->transferState->addPart(UploadPart::fromResponse($response));
            }
        }
    }
    
    private function prepareParts($workers)
    {
        //$uri = $this->entityBody->getUrl();
        
        $firstPart = new ReadLimitEntityBody($this->entityBody, $this->partSize);
        $array = array($firstPart);
        
        for ($i = 0; $i < $workers; $i++) {
            $array[] = clone $firstPart;
        }
        
        return $array;
    }
    
}