<?php

return array(
    
    'misc' => array(
        'tokens' => array('path' => 'tokens', 'status' => 204)
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
        
    ),
    
    'object-store' => array(
        
    ),
    
    'rax:object-cdn' => array(
        
    ),
    
    'rax:database' => array(
        
    ),
    
    'rax:load-balancer' => array(
        
    ),
    
    
);
