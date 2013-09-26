<?php

return array(
  
    'rax:object-cdn' => array(
        'TEST' => array(
            'body' => '',
            'status' => 204,
            'headers' => array(
                'X-Cdn-Ssl-Uri' => 'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
                'X-Ttl' => 259200,
                'X-Cdn-Uri' => 'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
                'X-Cdn-Enabled' => 'True',
                'X-Log-Retention' => 'False',
                'X-Cdn-Streaming-Uri' => 'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
                'X-Cdn-Ios-Uri' => 'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.ios.cf0.rackcdn.com',
                'X-Trans-Id' => 'tx82a6752e00424edb9c46fa2573132e2c',
                'Content-Length' => 0
            )
        ),
        'NON-CDN' => 'format'
    ),
    
    'rax:queues' => array(
        '/queues/foobar$' => array('status' => 404, 'body' => '{}'),
        '/queues/(\w|\-)+$' => array('status' => 204, 'body' => '{}')
    )
    
);