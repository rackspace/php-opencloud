<?php

require __DIR__ . '/../../lib/php-opencloud.php';

use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(
    'username' => 'jamiehannaford1',
    'apiKey'   => '504de62cad6b2356d378027e40d25d7c'
));

$service = $client->objectStoreService('cloudFiles');

$container = $service->getContainer('PHPSmokeTest55098');

$files = array(
    array('name' => 'cloud_files-7.x-1.1.zip', 'path' => '/Users/jami6682/Downloads/cloud_files-7.x-1.1.zip'),
    array('name' => 'IMG_2744.JPG', 'path' => '/Users/jami6682/Downloads/IMG_2744.JPG'),
    array('name' => 'OpenStackWelcomeGuide.pdf', 'path' => '/Users/jami6682/Downloads/OpenStackWelcomeGuide.pdf'),
    array('name' => 'video2.mp4', 'path' => '/Users/jami6682/Downloads/video2.mp4'),
    array('name' => 'Untitleddocument.docx', 'path' => '/Users/jami6682/Downloads/Untitleddocument.docx'),
    array('name' => 'CWT Portrait User upload file(1).xlsx', 'path' => '/Users/jami6682/Documents/Rackspace/CWT Portrait User upload file(1).xlsx')
);

$container->uploadObjects($files);