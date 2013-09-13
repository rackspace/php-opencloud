<?php

/**
 * @copyright Copyright 2012-2013 Rackspace US, Inc. 
  See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

/**
 * Description of DNS
 * 
 * @link 
 */
class DNS extends AbstractUnit implements UnitInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function setupService()
    {
        return $this->getConnection()->DNS();
    }
    
    /**
     * {@inheritDoc}
     */
    public function main()
    {
        $domainName = 'domain-' . time() . '.com';
        
        // Add a domain
        
        Utils::step('Try to add a domain %s', $domainName);
        
        $domain = $this->getService()->domain();
        
        $asyncResponse = $domain->create(array(
            'name'         => $domainName,
            'emailAddress' => 'sdk-support@rackspace.com',
            'ttl'          => 3600
        ));
        $asyncResponse->waitFor('COMPLETED', 300, $this->getWaiterCallback(), 1);

        if ($asyncResponse->Status() == 'ERROR') {
            Utils::log('Error condition (this may be valid if the domain exists');
            Utils::logf(
                '[%d] %s - %s',
                $asyncResponse->error->code, 
                $asyncResponse->error->message, 
                $asyncResponse->error->details
            );
        }
        
        // Add a CNAME
        
        Utils::step("Adding a CNAME record www.%s", $domainName);
        
        $domains = $dns->domainList(array('name' => $domainName));
        $domain = $domains->next();

        $record = $domain->record();
        $asyncResponse = $record->create(array(
            'type' => 'CNAME', 
            'ttl'  => 600, 
            'name' => 'www.'. $domainName,
            'data' => 'developer.rackspace.com'
        ));
        $asyncResponse->WaitFor('COMPLETED', 300, $this->getWaiterCallback(), 1);

        if ($asyncResponse->status() == 'ERROR') {
            Utils::log(
                'Error status: [%d] $s - %s', 
                $asyncResponse->error->code, 
                $asyncResponse->error->message,
                $asyncResponse->error->details
            );
        }

        Utils::step('List domains and records');
        
        $domains = $dns->domainList();
        while ($domain = $domains->Next()) {
            
            Utils::log('%s [%s]', $domain->Name(), $domain->emailAddress);
            Utils::log('Domain Records:');
            
            $recordList = $domain->recordList();
            
            while ($record = $recordList->next()) {
                Utils::logf(
                    '- %s %d %s %s',
                    $record->type, 
                    $record->ttl, 
                    $record->name(), 
                    $record->data
                );
            }
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function teardown()
    {
        while ($domain = $this->getService()->domainList()) {
            if ($this->shoulDelete($domain->name)) {
                $domain->delete();
            }
        }
    }
}