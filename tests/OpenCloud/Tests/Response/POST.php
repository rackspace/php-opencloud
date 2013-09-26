<?php

return array(
    
    'misc' => array(
        'tokens' => array(
            array('pattern' => 'badPassword', 'status' => 400),
            array('pattern' => 'POST', 'path' => 'tokens', 'status' => 204)
         )
    ),
    
    'rax:autoscale' => array(
        
        // Groups
        'groups' => 'Group/post',

        // Config
        'groups/{w}/config' => 'Config/put',
        'groups/{w}/launch' => 'Config/put_launch',
        'groups/{w}/pause'  => 'Config/post_pause',
        'groups/{w}/resume' => 'Config/post_resume',

        // Policies
        'groups/{w}/policies' => 'Policy/post',
        'groups/{w}/policies/{w}' => 'Policy/put',
        'groups/{w}/policies/{w}/execute' => 'Policy/post_execute',
        
        // Webhooks
        'groups/{w}/policies/{w}/webhooks' => 'Webhook/post',
        'groups/{w}/policies/{w}/webhooks/{w}'=> 'Webhook/put',
    ),
    
    'rax:monitor' => array(
        
        // Entities
        'entities' => 'Entity/post',
        'entities/{w}' => 'Entity/put',

        // Checks
        'entities/{w}/checks' => 'Entity/check_post',
        'entities/{w}/test-check' => 'Entity/check_test_post',
        'entities/{w}/test-check\?debug=true' => 'Entity/check_test_debug_post',
        'entities/{w}/checks/{w}/test' => 'Check/test_existing',
        'entities/{w}/checks/{w}/test\?debug=true' => 'Check/test_debug',
        'entities/{w}/checks/{w}' => 'Entity/check_put',
        
        // Traceroute
        'monitoring_zones/{w}/traceroute' => 'Zone/traceroute',

        // Alarms
        'entities/{w}/alarms' => 'Alarm/post',
        'entities/{w}/test-alarm' => 'Alarm/test_alarm_post',
        'entities/{w}/alarms/{w}' => 'Alarm/put',
        'alarm_examples/{w}' => 'Alarm/example_post',

        // Notifications
        'notification_plans' => 'NotificationPlan/post',
        'notification_plans/{w}' => 'NotificationPlan/put',
        'notifications' => 'Notification/post',
        'notifications/{w}' => 'Notification/put',
        'notifications/{w}/test' => 'Notification/test',
        'test-notification' => 'Notification/test'
    ),
    
    'compute' => array(
        
        'action' => array(
            array('pattern' => 'rescue', 'body' => '{"adminPass": "m7UKdGiKFpqM"}'),
            array('pattern' => 'EPIC-IMAGE', 'body' => '', 'status' => 202, 'headers' => array('Location' => 'fooBar')), 
        ),
        'network' => array(
            '{"network":{"id":"1","cidr":"192.168.0.0/24","label":"foo"}}'
        ),
        'servers/{w}/rax-si-image-schedule$' => array(
            'path' => 'schedule_create',
            'status' => 204
        ),
        'servers' => array(
            'path' => 'server_create'
        )
        
    ),
    
    'object-store' => array(
        
    ),
    
    'rax:object-cdn' => array(
        
    ),
    
    'rax:database' => array(
        'root' => '{"user":{"name":"root","password":"foo"}}',
        'databases' => array('body' => '{to be filled in}', 'status' => 202),
        'instances' => array('path' => 'instance_create')
    ),
    
    'rax:load-balancer' => array(
        'loadbalancers' => array(
            'body' => '{"loadBalancer":{"id":"123","name":"NONAME"}}', 
            'status' => 202
        )
    ),
    
    'rax:dns' => array(
        'import' => array(
            'body'   => '{"status":"RUNNING","verb":"GET","jobId":"852a1e4a-45b4-409b-9d46-2d6d641b27cf","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/status/852a1e4a-45b4-409b-9d46-2d6d641b27cf","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/domains/3612932/export"}',
            'status' => 202
        ),
        'domains' => array(
            'body'   => '{"status":"RUNNING","verb":"GET","jobId":"852a1e4a-45b4-409b-9d46-2d6d641b27cf","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/status/852a1e4a-45b4-409b-9d46-2d6d641b27cf","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/domains/3612932/export"}',
            'status' => 202
        ),
        'rdns' => array(
            'body'   => '{"status":"RUNNING","verb":"GET","jobId":"852a1e4a-45b4-409b-9d46-2d6d641b27cf","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/status/852a1e4a-45b4-409b-9d46-2d6d641b27cf","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/domains/3612932/export"}',
            'status' => 202
        )
    ),
    
    'rax:queues' => array(
        '/queues/foobar/messages$' => array('status' => 404, 'body' => '{}'),
        '/queues/test-queue/messages$' => array('status' => 201, 'path' => 'post_message', 'headers' => array()),
        '/queues/{w}/messages$' => array('status' => 201, 'path' => 'post_message', 'headers' => array('Location' => '/v1/queues/demoqueue/messages?ids=51db6f78c508f17ddc924357')),
        '/queues/foobar/claims(\?(\w|\&|\=)+)?$' => array('status' => 204, 'body' => '{}'),
        '/queues/baz/claims(\?(\w|\&|\=)+)?$' => array('status' => 404, 'body' => '{}'),
        '/queues/(\w|\-)+/claims(\?(\w|\&|\=)+)?$' => array('status' => 201, 'path' => 'claim_messages')
    )
    
);
