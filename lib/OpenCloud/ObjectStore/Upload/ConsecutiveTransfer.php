<?php
/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Upload;

/**
 * Description of ConsecutiveTransfer
 * 
 * @link 
 */
class ConsecutiveTransfer
{
    
    public function transfer()
    {
        while (!$this->entityData->isConsumed()) {
            
            if ($this->entityData->getContentLength() && $this->entityData->isSeekable()) {
                // Stream directly from the data
                $body = new ReadLimitEntityBody($this->entityData, $this->partSize, $this->entityData->ftell());
            } else {
                // If not-seekable, read the data into a new, seekable "buffer"
                $body = EntityBody::factory();
                $output = true;
                while ($body->getContentLength() < $this->partSize && $output !== false) {
                    // Write maximum of 10KB at a time
                    $length = min(10 * Size::KB, $this->partSize - $body->getContentLength());
                    $output = $body->write($this->entityData->read($length));
                }
            }

            if ($body->getContentLength() == 0) {
                break;
            }

            $request = UploadPart::getRequest(
                $body, 
                $this->transferState->count() + 1,
                $this->getClient(), 
                $this->options
            );
            
            $response = $request->getResponse();

            $this->transferState->addPart(UploadPart::fromResponse($response));
        }
    }
    
}