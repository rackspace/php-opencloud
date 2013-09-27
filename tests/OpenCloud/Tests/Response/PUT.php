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
        'NON-CDN' => array(
            'body' => '',
            'status' => 201,
            'headers' => array(
                'ETag' => 'd9f5eb4bba4e2f2f046e54611bc8196b',
                'Content-Length' => 0,
                'Content-Type' => 'text/plain; charset=UTF-8'
            )
        ),
        'TEST' => array(
            array(
                'pattern' => 'X-CDN-Enabled: True',
                'status' => 201,
                'body'   => ''
            )
        )
    ),
    
    'rax:queues' => array(
        '/queues/baz$' => array('status' => 404, 'body' => '{}'),
        '/queues/test-queue$' => array('status' => 201, 'body' => '{}'),
        '/queues/{w}$' => array('status' => 201, 'body' => '{}', 'headers' => array('Location' => 'foo')),
        '/queues/(\w|\-)+/metadata$' => array('status' => 204, 'body' => '{}')
        
    )
);