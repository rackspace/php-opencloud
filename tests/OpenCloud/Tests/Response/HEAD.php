<?php

return array(
  
    'rax:object-cdn' => array(
        'container1' => array(
            'body' => '',
            'status' => 204,
            'headers' => array(
                'X-Cdn-Ssl-Uri' => 'https://83c49b9a2f7ad18250b3-346eb45fd42c58ca13011d659bfc1ac1.ssl.cf0.rackcdn.com',
                'X-Ttl' => '259200',
                'X-Cdn-Uri' => 'http://081e40d3ee1cec5f77bf-346eb45fd42c58ca13011d659bfc1ac1.r49.cf0.rackcdn.com',
                'X-Cdn-Enabled' => 'True',
                'X-Log-Retention' => 'False',
                'X-Cdn-Streaming-Uri' => 'http://084cc2790632ccee0a12-346eb45fd42c58ca13011d659bfc1ac1.r49.stream.cf0.rackcdn.com',
                'X-Trans-Id' => 'tx82a6752e00424edb9c46fa2573132e2c',
                'Content-Length' => '0',
            )
        ),
        'container2' => array('status' => 404, 'body' => '{}')
    ),
    
    'object-store' => array(
        'M-ALT-ID$' => array('status' => 204, 'body' => '{}', 'headers' => array(
            'X-Account-Bytes-Used' => '50000000',
            'X-Account-Object-Count' => '1000000',
            'X-Account-Container-Count' => '20',
            'X-Account-Meta-Temp-Url-Key' => 'lalalala'
        )),
        'container1' => array(
            'body' => '',
            'status' => 204,
            'headers' => array(
                'Connection' => 'keep-alive',
                'Content-Type' => 'text/html; charset=UTF-8',
                'X-Container-Object-Count' => '5',
                'X-Trans-Id' => 'tx30e27bcc8bf34c0ebfdf078337895478',
                'X-Timestamp' => '1331584412.96818',
                'X-Container-Meta-Book' => 'MobyDick',
                'Accept-Ranges' => 'bytes',
                'Date' => 'Thu, 08 Nov 2012 19:08:25 GMT',
                'X-Container-Meta-Subject' => 'Whaling',
                'Content-Length' => '0',
                'X-Container-Bytes-Used' => '3846773',
                'X-Quota-Bytes' => '5000000',
                'X-Quota-Count' => '100000'
            )
        ),
        'container2' => 'format'
    ),
    
    'rax:queues' => array(
        '/queues/foobar$' => array('status' => 404, 'body' => '{}'),
        '/queues/(\w|\-)+$' => array('status' => 204, 'body' => '{}')
    )
    
);