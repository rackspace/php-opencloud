<?php

return array(
    
    'rax:autoscale' => array(
        '/' => array('status' => 204)
    ),
    
    'object-store' => array(
        
        '\?bulk-delete' => array(
            array('pattern' => 'nonEmptyContainer', 'status' => 202, 'body' => '{"Number Not Found":0,"Response Status":"400 Bad Request","Errors":[["/v1/AUTH_test/non_empty_container","409 Conflict"]],"Number Deleted":0,"Response Body":""}'),
            array('pattern' => 'DELETE', 'status' => 202, 'body' => '{"Number Not Found":1,"Response Status":"200 OK","Errors":[],"Number Deleted":10,"Response Body":""}')
        )
    )
    
);