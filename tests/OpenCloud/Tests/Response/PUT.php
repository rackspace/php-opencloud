<?php
return array(
    
    'rax:autoscale' => array(
        'groups/{w}/config' => 'Config/put',
        'groups/{w}/launch' => 'Config/put_launch',
        'groups/{w}/policies/{w}' => 'Policy/put',
        'groups/{w}/policies/{w}/webhooks/{w}'=> 'Webhook/put',
    ),
    
    'rax:dns' => array(
        'import' => array(
            'body'   => '{"status":"RUNNING","verb":"GET","jobId":"852a1e4a-45b4-409b-9d46-2d6d641b27cf","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/status/852a1e4a-45b4-409b-9d46-2d6d641b27cf","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/domains/3612932/export"}',
            'status' => 202
        )
     ),
    
    'object-store' => array(
        'bad-container\?extract-archive' => array('status' => 200, 'body' => '{"Number Files Created":10,"Response Status":"400 Bad Request","Errors":[["/v1/AUTH_test/test_cont/big_file.wav","413 Request Entity Too Large"]],"Response Body":""}'),
        '{w}\?extract-archive' => array('status' => 200, 'body' => '{"Number Files Created":10,"Response Status":"201 Created","Errors":[],"Response Body":""}'),
        'M-ALT-ID/existing-container$' => array('status' => 202, 'body' => ''),
        'M-ALT-ID/{w}/?$' => array(
            'status' => 201,
            'body' => '{}',
            'headers' => array('X-Container-Meta-InspectedBy' => 'JackWolf')
        )
    ),
    
    'rax:queues' => array(
        '/queues/baz$' => array('status' => 404, 'body' => '{}'),
        '/queues/test-queue$' => array('status' => 201, 'body' => '{}'),
        '/queues/{w}$' => array('status' => 201, 'body' => '{}', 'headers' => array('Location' => 'foo')),
        '/queues/(\w|\-)+/metadata$' => array('status' => 204, 'body' => '{}')
        
    ),
    
    'compute' => array(
        'metadata' => '{"metadata": {"foo" : "bar"}}'
    )
    
);