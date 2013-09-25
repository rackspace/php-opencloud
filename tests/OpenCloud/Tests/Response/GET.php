<?php

return array(
    
    'rax:autoscale' => array(
        
        // Groups
        'groups' => 'Group/list',
        'groups/{w}' => 'Group/get',
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
    
);