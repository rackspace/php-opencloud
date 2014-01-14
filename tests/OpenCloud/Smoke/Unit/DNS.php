<?php
/**
 * PHP OpenCloud library.
 *
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Smoke\Unit;

use OpenCloud\Smoke\Enum;

class DNS extends AbstractUnit implements UnitInterface
{
    public function setupService()
    {
        return $this->getConnection()->dnsService();
    }

    public function main()
    {
        $domainName = 'domain-' . time() . '.com';
        
        // Add a domain
        $this->step('Try to add a domain %s', $domainName);

        $domain = $this->getService()->domain();
        $domain->addRecord($domain->record(array(
            'ttl'  => 5771,
            'name' => 'foo.' . $domainName,
            'type' => 'A',
            'data' => '192.0.2.8'
        )));
        $asyncResponse = $domain->create(array(
            'name'         => $domainName,
            'emailAddress' => 'jamie.hannaford@rackspace.com',
            'ttl'          => 3600
        ));
        $asyncResponse->waitFor('COMPLETED', 300, $this->getWaiterCallback(), 1);

        if ($asyncResponse->Status() == 'ERROR') {
            $this->stepInfo(
                'Error: [%d] %s - %s',
                $asyncResponse->error->code, 
                $asyncResponse->error->message, 
                $asyncResponse->error->details
            );
        }
        
        // Add a CNAME
        $this->step("Adding a CNAME record www.%s", $domainName);
        
        $domains = $this->getService()->domainList(array('name' => $domainName));
        $domain = $domains->getElement(0);

        $record = $domain->record();
        $asyncResponse = $record->create(array(
            'type' => 'CNAME', 
            'ttl'  => 600, 
            'name' => 'www.'. $domainName,
            'data' => 'developer.rackspace.com'
        ));
        $asyncResponse->waitFor('COMPLETED', 300, $this->getWaiterCallback(), 1);

        if ($asyncResponse->status() == 'ERROR') {
            $this->stepInfo(
                'Error: [%d] $s - %s', 
                $asyncResponse->error->code, 
                $asyncResponse->error->message,
                $asyncResponse->error->details
            );
        }

        $records = $domain->recordList();
        foreach ($records as $record) {
            $record->update(array('name' => 'something-else.com'));
        }

        // List everything
        $this->step('List domains and records');
        
        $domains = $this->getService()->domainList();
        $domains->setOption('limit.total', Enum::DISPLAY_ITER_LIMIT);

        foreach ($domains as $domain) {
            
            $this->stepInfo('%s [%s]', $domain->name(), $domain->emailAddress);
            $step = $this->stepInfo('Domain Records:');

            $records = $domain->recordList();
            foreach ($records as $record) {
                $step->stepInfo(
                    '- %s %d %s %s',
                    $record->type, 
                    $record->ttl, 
                    $record->name(), 
                    $record->data
                );
            }
        }
    }

    public function teardown()
    {
        $domains = $this->getService()->domainList();
        foreach ($domains as $domain) {
            if ($this->shouldDelete($domain->name())) {
                $domain->delete();
            }
        }
    }
}