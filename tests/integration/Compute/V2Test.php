<?php declare(strict_types=1);

namespace Rackspace\Integration\Compute;

use OpenCloud\Integration\SampleManagerInterface;
use OpenCloud\Integration\TestCase;
use Psr\Log\LoggerInterface;
use Rackspace\Compute\v2\Models\Flavor;
use Rackspace\Compute\v2\Models\Image;
use Rackspace\Compute\v2\Models\Keypair;
use Rackspace\Compute\v2\Models\Server;
use Rackspace\Compute\v2\Models\VirtualInterface;
use Rackspace\Compute\v2\Models\VolumeAttachment;
use Rackspace\Integration\Utils;
use Rackspace\Rackspace;

class V2Test extends TestCase
{
    private $service;
    private $server;

    public function __construct(LoggerInterface $logger, SampleManagerInterface $sampleManager)
    {
        parent::__construct($logger, $sampleManager);

        $this->service = (new Rackspace(Utils::getAuthOpts()))->computeV2();
    }

    public function __destruct()
    {
        if ($server = $this->getServer()) {
            //$server->delete();
        }
    }

    public function runTests()
    {
        $this->images();
        $this->flavors();
        $this->keypairs();
        $this->imageSchedules();
        $this->servers();
        $this->virtualInterfaces();
        $this->volumeAttachments();
    }

    private function checkEnv(...$args)
    {
        $missing = [];

        foreach ($args as $arg) {
            if (!getenv($arg)) {
                $missing[] = $arg;
            }
        }

        if (!empty($missing)) {
            throw new \Exception(sprintf("%s are required environment variables.", implode(', ', $missing)));
        }
    }

    /**
     * @return Server
     */
    private function getServer()
    {
        if (null === $this->server) {
            $this->server = $this->createServer();
        }

        return $this->server;
    }

    /**
     * @return Server
     */
    private function createServer()
    {
        $this->checkEnv('OS_FLAVOR_ID', 'OS_IMAGE_ID');

        /** @var Server $server */
        $server = $this->service->createServer([
            'name'     => $this->randomStr(),
            'flavorId' => getenv('OS_FLAVOR_ID'),
            'imageId'  => getenv('OS_IMAGE_ID'),
        ]);

        $server->waitUntilActive();

        $this->logStep('Created server id', ['id' => $server->id]);

        return $server;
    }

    public function flavors()
    {
        $this->logStep('Listing flavors');

        /** @var Flavor $flavor */
        $path = $this->sampleFile([], 'flavors/list.php');
        require_once $path;
        $this->assertInstanceOf(Flavor::class, $flavor);

        $this->logStep('Retrieving flavor id', ['id' => $flavor->id]);
        $replacements = ['{id}' => $flavor->id];

        /** @var Flavor $flavor */
        $path = $this->sampleFile($replacements, 'flavors/get.php');
        require_once $path;
        $this->assertInstanceOf(Flavor::class, $flavor);

        $this->logStep('Retrieving extra specs for flavor id', ['id' => $flavor->id]);

        /** @var array $specs */
        $path = $this->sampleFile($replacements, 'flavors/get_extra_specs.php');
        require_once $path;
        $this->assertInternalType('array', $specs);
        $this->assertNotEmpty($specs);
    }

    public function images()
    {
        $server = $this->getServer();

        $replacements = [
            '{id}'   => $server->id,
            '{name}' => $this->randomStr(),
            '{key}'  => $this->randomStr(),
            '{val}'  => $this->randomStr(),
        ];

        $this->logStep('Create image for server');
        $path = $this->sampleFile($replacements, 'images/create_server_image.php');
        require_once $path;

        $this->logStep('Listing images');

        /** @var Image $image */
        $path = $this->sampleFile([], 'images/list.php');
        require_once $path;
        $this->assertInstanceOf(Image::class, $image);

        $this->logStep('Retrieving image id', ['id' => $image->id]);
        $replacements = ['{id}' => $image->id];

        /** @var Image $image */
        $path = $this->sampleFile($replacements, 'images/get.php');
        require_once $path;
        $this->assertInstanceOf(Image::class, $image);

        $imageId = '';
        foreach ($this->service->listImages() as $image) {
            if (stripos($image->name, 'phptest_') === 0) {
                $imageId = $image->id;
            }
        }

        $this->logStep('Delete image');
        $path = $this->sampleFile(['{id}' => $imageId], 'images/delete.php');
        require_once $path;
    }

    public function imageSchedules()
    {
        $server = $this->getServer();

        $replacements = ['{id}' => $server->id];

        $this->logStep('Enable weekly images');
        $path = $this->sampleFile($replacements, 'scheduledImages/enable_weekly_backups.php');
        require_once $path;

        $this->logStep('Enable daily images');
        $path = $this->sampleFile($replacements, 'scheduledImages/enable_daily_backups.php');
        require_once $path;

        $this->logStep('Get scheduled images');
        $path = $this->sampleFile($replacements, 'scheduledImages/get.php');
        require_once $path;

        $this->logStep('Disable scheduled images');
        $path = $this->sampleFile($replacements, 'scheduledImages/disable.php');
        require_once $path;
    }

    public function keypairs()
    {
        $name1 = $this->randomStr();
        $name2 = $this->randomStr();

        $this->logStep('Create keypair');
        /** @var Keypair $keypair */
        $path = $this->sampleFile(['name' => $name1], 'keypairs/create.php');
        require_once $path;
        $this->assertInstanceOf(Keypair::class, $keypair);

        $this->logStep('Import keypair');
        /** @var Keypair $keypair */
        $path = $this->sampleFile(['name' => $name2], 'keypairs/import.php');
        require_once $path;
        $this->assertInstanceOf(Keypair::class, $keypair);

        $this->logStep('List keypair');
        $path = $this->sampleFile([], 'keypairs/list.php');
        require_once $path;
        $this->assertInstanceOf(Keypair::class, $keypair);

        $this->logStep('Delete keypairs');

        $path = $this->sampleFile(['name' => $name1], 'keypairs/delete.php');
        require_once $path;

        $path = $this->sampleFile(['name' => $name2], 'keypairs/delete.php');
        require_once $path;
    }

    public function servers()
    {
        $this->checkEnv('OS_FLAVOR_ID', 'OS_IMAGE_ID');

        $replacements = [
            '{flavorId}' => getenv('OS_FLAVOR_ID'),
            '{imageId}'  => getenv('OS_IMAGE_ID'),
        ];

        $this->logStep('Create new server');
        /** @var Server $server */
        $path = $this->sampleFile($replacements, 'servers/create.php');
        require_once $path;

        $server->waitUntilActive();

        $this->assertInstanceOf(Server::class, $server);
        $this->assertEquals('AUTO', $server->diskConfig);

        $replacements = ['{id}' => $server->id];

        $this->logStep('Retrieve server');
        /** @var Server $server */
        $path = $this->sampleFile($replacements, 'servers/get.php');
        require_once $path;
        $this->assertEquals('api-test-server-1', $server->name);
        $this->assertInstanceOf(Image::class, $server->image);
        $this->assertInstanceOf(Flavor::class, $server->flavor);

        $this->logStep('List servers');
        $path = $this->sampleFile($replacements, 'servers/list.php');
        require_once $path;

        $this->logStep('Update server');
        $path = $this->sampleFile($replacements, 'servers/get.php');
        require_once $path;

        $this->logStep('Get metadata');
        /** @var array $metadata */
        $path = $this->sampleFile($replacements, 'servers/get_metadata.php');
        require_once $path;
        $this->assertEquals(["My Server Name" => "API Test Server 1"], $metadata);

        $this->logStep('Merge metadata');
        $path = $this->sampleFile($replacements, 'servers/merge_metadata.php');
        require_once $path;

        $this->assertArraySubset([
            "My Server Name" => "API Test Server 1",
            "key2"           => "val2",
        ], $server->getMetadata());

        $this->logStep('Reset metadata');
        $path = $this->sampleFile($replacements, 'servers/reset_metadata.php');
        require_once $path;
        $this->assertArraySubset(["key" => "value"], $server->getMetadata());

        $this->logStep('Change server password');
        $path = $this->sampleFile($replacements, 'servers/change_password.php');
        require_once $path;

        $server->waitUntilActive();

        $this->logStep('Reboot server');
        $path = $this->sampleFile($replacements, 'servers/reboot.php');
        require_once $path;
        $server->waitUntilActive();

        $replacements['{password}'] = 'newPassword';
        $replacements['{imageId}'] = getenv('OS_IMAGE_ID');

        $this->logStep('Rebuild server');
        $path = $this->sampleFile($replacements, 'servers/rebuild.php');
        require_once $path;
        $server->waitUntilActive();

        $replacements['{flavorId}'] = '2';
        $this->logStep('Resize server image');
        $path = $this->sampleFile($replacements, 'servers/resize_image.php');
        require_once $path;
        $server->waitUntilActive();

        $this->logStep('Confirm server resize');
        $path = $this->sampleFile($replacements, 'servers/confirm_resize.php');
        require_once $path;

        $replacements['{flavorId}'] = '1';
        $this->logStep('Resize server image (again)');
        $path = $this->sampleFile($replacements, 'servers/resize_image.php');
        require_once $path;
        $server->waitUntilActive();

        $this->logStep('Revert server resize');
        $path = $this->sampleFile($replacements, 'servers/revert_resize.php');
        require_once $path;

        $this->logStep('Rescue server');
        $path = $this->sampleFile($replacements, 'servers/rescue.php');
        require_once $path;
        $server->waitUntilActive();

        $this->logStep('Unrescue server');
        $path = $this->sampleFile($replacements, 'servers/unrescue.php');
        require_once $path;
        $server->waitUntilActive();

        $this->logStep('List IP addresses');
        $path = $this->sampleFile($replacements, 'servers/list_ips.php');
        require_once $path;

        $this->logStep('Delete server');
        $path = $this->sampleFile($replacements, 'servers/delete.php');
        require_once $path;
        $server->waitUntilDeleted();
    }

    public function virtualInterfaces()
    {
        if (!($networkId = getenv('OS_NETWORK_ID'))) {
            $this->logger->emergency('No OS_NETWORK_ID set');
            return;
        }

        $replacements = [
            '{serverId}'  => $this->getServer()->id,
            '{networkId}' => $networkId,
        ];

        $this->logStep('Create virtual interface');
        /** @var VirtualInterface $virtualInterface */
        $path = $this->sampleFile($replacements, 'virtualInterfaces/create.php');
        require_once $path;

        $this->logStep('List virtual interfaces');
        $path = $this->sampleFile($replacements, 'virtualInterfaces/list.php');
        require_once $path;

        $replacements['{id}'] = $virtualInterface->id;

        $this->logStep('Delete virtual interface');
        $path = $this->sampleFile($replacements, 'virtualInterfaces/delete.php');
        require_once $path;
    }

    public function volumeAttachments()
    {
        if (!($volumeId = getenv('OS_VOLUME_ID'))) {
            $this->logger->emergency('No OS_VOLUME_ID set');
            return;
        }

        $server = $this->getServer();
        $server->waitUntilActive();

        $replacements = [
            '{serverId}' => $server->id,
            '{volumeId}' => $volumeId,
        ];

        $this->logStep('Attach volume');
        /** @var VolumeAttachment $volumeAttachment */
        $path = $this->sampleFile($replacements, 'volumeAttachments/attach.php');
        require_once $path;
        sleep(5);

        $this->logStep('List volume attachments');
        $path = $this->sampleFile($replacements, 'volumeAttachments/list.php');
        require_once $path;

        $replacements['{attachmentId}'] = $volumeAttachment->id;

        $this->logStep('Get volume attachment');
        $path = $this->sampleFile($replacements, 'volumeAttachments/get.php');
        require_once $path;

        $this->logStep('Detach volume');
        $path = $this->sampleFile($replacements, 'volumeAttachments/detach.php');
        require_once $path;
    }
}