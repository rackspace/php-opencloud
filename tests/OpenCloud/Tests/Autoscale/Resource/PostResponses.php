<?php

return array(
    'tokens' => 'tokens',
 
    'groups' => 'Group/post',
    
    'groups/{w}/config' => 'Config/put',
    'groups/{w}/launch' => 'Config/put_launch',
    'groups/{w}/pause'  => 'Config/post_pause',
    'groups/{w}/resume' => 'Config/post_resume',
    
    'groups/{w}/policies' => 'Policy/post',
    'groups/{w}/policies/{w}' => 'Policy/put',
    'groups/{w}/policies/{w}/execute' => 'Policy/post_execute',
    
    'groups/{w}/policies/{w}/webhooks' => 'Webhook/post',
    'groups/{w}/policies/{w}/webhooks/{w}'=> 'Webhook/put'
    
);