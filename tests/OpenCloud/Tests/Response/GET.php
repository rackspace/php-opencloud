<?php

return array(
    
    'misc' => array(
        'BADJSON' => '{"bad jjson',
        '/instances/' => 'instance',
        '/instances' => '{"instances":[]}',
        'limits/?$' => 'limits'
    ),
    
    'rax:autoscale' => array(
        
        // Groups
        'groups/?$' => 'Group/list',
        'groups/{w}/?$' => 'Group/get',
        'groups/{w}/state' => 'Group/state',
        
        // Config
        'groups/{w}/config' => 'Config/get',
        'groups/{w}/launch' => 'Config/launch',
        
        // Policies
        'groups/{w}/policies' => 'Policy/list',
        'groups/{w}/policies/{w}' => 'Policy/get',
        
        // Webhooks
        'groups/{w}/policies/{w}/webhooks' => 'Webhook/list',
        'groups/{w}/policies/{w}/webhooks/{w}' => 'Webhook/get'
    ),
    
    'rax:monitor' => array(
        
        'entities/?$' => 'Entity/entities',
        'entities/{w}/?$' => 'Entity/entity',

        'entities/{w}/checks/?$' => 'Check/list_checks',
        'entities/{w}/checks/{w}/?$' => 'Check/get',

        'entities/{w}/checks/{w}/metrics/?$' => 'Metric/list',
        'entities/{w}/checks/{w}/metrics/{w}' => 'Metric/data_points',

        'check_types/?$' => 'CheckType/list',
        'check_types/(\w|\.)+?$' => 'CheckType/get',

        'entities/{w}/alarms/?$' => 'Alarm/list',
        'entities/{w}/alarms/{w}/?$' => 'Alarm/get',

        'entities/{w}/alarms/{w}/notification_history/?$' => 'NotificationHistory/list',
        'entities/{w}/alarms/{w}/notification_history/{w}/?$' => 'NotificationHistory/get',
        'entities/{w}/alarms/{w}/notification_history/{w}/{w}/?$' => 'NotificationHistory/get_history_item',

        'monitoring_zones/?$' => 'Zone/list',
        'monitoring_zones/{w}/?$' => 'Zone/get',

        'notifications/?$' => 'Notification/list',
        'notifications/{w}/?$' => 'Notification/get',

        'notification_plans/?$' => 'NotificationPlan/list',
        'notification_plans/{w}/?$' => 'NotificationPlan/get',

        'notification_types/?$' => 'NotificationType/list',
        'notification_types/{w}/?$' => 'NotificationType/get',

        'changelogs/alarms/?$' => 'Changelog/list',
        'changelogs/alarms?entityId={w}' => 'Changelog/list',

        'views/overview/?$' => 'View/get',
        'views/overview?id=entityId&id={w}' => 'View/get',
        'views/overview?uri={w}&uri={w}' => 'View/get',

        'alarm_examples/?$' => 'Alarm/example_list',
        'alarm_examples/{w}/?$' => 'Alarm/example_get',

        'agents/?$' => 'Agent/list',
        'agents/{w}/?$' => 'Agent/get',
        'agents/{w}/connections/?$' => 'Agent/connections_list',
        'agents/{w}/connections/{w}' => 'Agent/connections_get',

        'agent_tokens/?$' => 'Agent/tokens_list',
        'agent_tokens/{w}' => 'Agent/tokens_get',

        'entities/{w}/agent/check_types/{w}/targets' => 'Agent/targets_list',

        'agents/{w}/host_info/cpus' => 'Agent/host_cpu',
        'agents/{w}/host_info/disks' => 'Agent/host_disk',
        'agents/{w}/host_info/filesystems' => 'Agent/host_filesystem',
        'agents/{w}/host_info/memory' => 'Agent/host_memory',
        'agents/{w}/host_info/network_interfaces' => 'Agent/host_network_interface',
        'agents/{w}/host_info/processes' => 'Agent/host_processes',
        'agents/{w}/host_info/system' => 'Agent/host_sysinfo',
        'agents/{w}/host_info/who' => 'Agent/host_logged_in_user'
    ),
    
    "object-store" => array(
        'TEST/?$' => '{}',
        'TEST\?format=json' => 'format',
        'NON-CDN' => 'format',
        'delimeter' => '[{"subdir": "files/Pseudo1/"},{"subdir": "files/Pseudo2/"}]'
    ),
    
    "rax:object-cdn" => array(
        'TEST' => '{}',
    ),
    
    "rax:database" => array(
        '/instances/' => 'instance',
        '/instances' => '{"instances":[]}',
        'flavors/{w}' => 'flavor',
        'flavors' => 'flavors',
    ),
    
    "volume" => array(
        'detail' => 'detail',
        'types/{w}' => 'type'
    ),
    
    "rax:load-balancer" => array(
        '/loadbalancers/{w}/stats$/' => '{"connectTimeOut":10,"connectError":20,"connectFailure":30,"dataTimedOut":40,"keepAliveTimedOut":50,"maxConn":60}',
        '/loadbalancers' => '{"loadBalancers":[{"name":"one","id":1,"protocol":"HTTP","port":80}]}',
        '/virtualips' => '{}',
        '/nodes' => '{}',
        '/billable' => '{}',
        '/algorithms' => '{}',
        '/sessionpersistence' => '{}',
        '/errorpage' => '{}',
        '/healthmonitor' => '{}',
        '/usage' => '{}',
        '/accesslist' => '{}',
        '/connectionthrottle' => '{}',
        '/connectionlogging' => '{}',
        '/contentcaching' => '{}',
        '/alloweddomains' => '{}',
        '/protocols' => '{}',
        '/ssltermination' => '{}',
        '/metadata' => '{}',
        '/2000' => array('path' => 'get')
    ),
    
    "rax:backup" => array(),
    
    "compute" => array(
        'os-volume_attachments/' => '{"volumeAttachment":{"volumeId":"FOO"}}',
        'os-volume_attachments' => '{"volumeAttachments": []}',
        'os-networksv2' => '{}',
        'os-keypairs' => '{"keypairs":[{"keypair":{"fingerprint":"15:b0:f8:b3:f9:48:63:71:cf:7b:5b:38:6d:44:2d:4a","name":"name_of_keypair-601a2305-4f25-41ed-89c6-2a966fc8027a","public_key":"ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQC+Eo/RZRngaGTkFs7I62ZjsIlO79KklKbMXi8F+KITD4bVQHHn+kV+4gRgkgCRbdoDqoGfpaDFs877DYX9n4z6FrAIZ4PES8TNKhatifpn9NdQYWA+IkU8CuvlEKGuFpKRi/k7JLos/gHi2hy7QUwgtRvcefvD/vgQZOVw/mGR9Q== Generated by Nova\n"}}]}',
        'metadata' => '{"metadata":{"foo":"bar"}}',
        'extensions' => 'extensions',
        '/images/detail' => 'image',
        '/servers/{w}/rax-si-image-schedule' => 'schedule',
        '/servers/' => 'server',
        'EMPTY' => '{}',
        '/volumes/' => '{"volume":[]}', 
        'flavors/{w}' => 'flavor',
        'flavors' => 'flavors',
    ),
    
    "rax:dns" => array(
        'export' => array(
            'body'   => '{"status":"RUNNING","verb":"GET","jobId":"852a1e4a-45b4-409b-9d46-2d6d641b27cf","callbackUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/status/852a1e4a-45b4-409b-9d46-2d6d641b27cf","requestUrl":"https://dns.api.rackspacecloud.com/v1.0/696206/domains/3612932/export"}',
            'status' => 202
        ),
        'limits/types' => array(
            'body' => '{"limitTypes":["RATE_LIMIT","DOMAIN_LIMIT","DOMAIN_RECORD_LIMIT"]}',
            'status' => 202
         ),
        'limits/DOMAIN_LIMIT' => array(
            'body' => '{"absolute":{"limits":[{"name":"domains","value":500}]}}',
            'status' => 202
        ),
        'limits{w}/limits' => array(
            'path' => 'limits',
            'status' => 202
        ),
        'changes' => array(
            'body' => '{"changes":[],"from":"2013-02-20T00:00:00.000+0000","to":"2013-02-20T16:12:08.000+0000","totalEntries":0}',
            'status' => 202
        ),
        'domains' => array(
            'body' => '{"domains":[{"name":"raxdrg.info","id":999919,"accountId":"TENANT-ID","emailAddress":"noname@dontuseemail.com","updated":"2013-02-15T16:30:28.000+0000","created":"2013-02-15T16:30:27.000+0000"}]}',
            'status' => 200
        ),
        'rdns' => array(
            'body' => '{"records":[{"name":"foobar.raxdrg.info","id":"PTR-548486","type":"PTR","data":"2001:4800:7811:513:199e:7e1e:ff04:be3f","ttl":900,"updated":"2013-02-18T20:24:50.000+0000","created":"2013-02-18T20:24:50.000+0000"},{"name":"foobar.raxdrg.info","id":"PTR-548485","type":"PTR","data":"166.78.48.90","ttl":900,"updated":"2013-02-18T20:24:34.000+0000","created":"2013-02-18T20:24:34.000+0000"}]}',
            'status' => 200
        )
        
    ),
    
    "rax:queues" => array(
        '/queues(\?.+)?$' => 'list_queues',
        '/queues/foobar/metadata$' => array('status' => 404, 'body' => '{}'),
        '/queues/{w}/metadata' => 'queue_metadata',
        '/queues/{w}/stats$' => 'queue_stats',
        '/queues/{w}/messages\?marker\=1\&limit\=2?$' => array('status' => 204, 'path' => 'queue_exists'),
        '/queues/{w}/messages(\?.+)?$' => 'list_messages',
        '/queues/{w}/messages/(\w|\-)+(\?.+)?$' => 'get_message',
        '/queues/{w}/claims/(\w|\-)+$' => 'get_claim'
    ),
    
);