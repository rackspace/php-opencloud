<?php

require __DIR__ . '/../../lib/php-opencloud.php';

use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(
    'username' => 'jamiehannaford1',
    'apiKey'   => '504de62cad6b2356d378027e40d25d7c'
));


$service = $client->computeService('cloudServersOpenStack');

$server = $service->server();

$key = <<<EOT
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCzLy8vaWcMkPQOdMj0eC7s0aW+EcazWXkzsEFdHEPXhfS+YyXkDrzMfzGHxCmv2bsheKHkPzstAO1fTlXtDR2PCH4XbkyHSKs9T+WnXXb2VBbZ4lzR7jmCnxJcvdcqCwGtUMikVGYos2j1cXxhvsQ4wYvMGLA6IkUb/HNkUov4rpNfdajBbOPU7bTsreNCSgRqI8qS9HOdfThV2DjVJI9/QeXNpXXAhbwNxK8nffyhfFPOb9H2WT2BXwfuKsrtpkMxgivk1rp3TzoVB2x6/kQlNg1j5H2XhkFu8gMOZFbpqGlFY99y5ODIi40VF24PLmZqfR9CAYrVvgm8FfQZA21p jamie.hannaford@rackspace.com
EOT;

$images = $service->imageList();
while ($image = $images->next()) {
    if (isset($image->metadata->os_distro) && $image->metadata->os_distro == 'centos') {
        break;
    }
}

try {
    $server->create(array(
        'name'     => 'keypair_test',
        'image'    => $image,
        'flavor'   => $service->flavorList()->first(),
        'networks' => array(
            $service->network(RAX_PUBLIC),
            $service->network(RAX_PRIVATE)
        ),
        "OS-DCF:diskConfig" => "AUTO",
        'keypair' => array(
            'name'      => 'id_rsa.pub',
            'publicKey' => $key
        )
    ));
} catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
    var_export((string)$e->getResponse());die;
}

echo $server->adminPass . PHP_EOL;

$callback = function($object) {
    if (!empty($object->error)) {
        var_dump($object->error); die;
    } else {
        echo sprintf(
            "Waiting on %s/%-12s %4s%%",
            $object->name(),
            $object->status(),
            isset($object->progress) ? $object->progress : 0
        ) . PHP_EOL;
    }
};

$server->waitFor('ACTIVE', 600, $callback);

echo $server->accessIPv4;