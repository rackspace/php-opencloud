<?php declare(strict_types=1);

namespace Rackspace\Integration\ObjectStoreCDN;

use OpenCloud\Integration\TestCase;
use Rackspace\Integration\Utils;
use Rackspace\ObjectStoreCDN\v1\Models\Container;
use Rackspace\ObjectStoreCDN\v1\Models\Object;
use Rackspace\Rackspace;

class V1Test extends TestCase
{
    /** @var \Rackspace\ObjectStore\v1\Service */
    private $service;

    /** @var \Rackspace\ObjectStoreCDN\v1\Service */
    private $cdnService;

    protected function getService()
    {
        if (null === $this->service) {
            $this->service = (new Rackspace())->objectStoreV1(Utils::getAuthOpts());
        }

        return $this->service;
    }

    protected function getCdnService()
    {
        if (null === $this->cdnService) {
            $this->cdnService = (new Rackspace())->objectStoreCdnV1(Utils::getAuthOpts());
        }

        return $this->cdnService;
    }

    public function runTests()
    {
        $this->startTimer();

        $this->containers();
        $this->objects();

        $this->outputTimeTaken();
    }

    protected function containers()
    {
        $name = $this->randomStr();

        $container = $this->getService()->createContainer(['name' => $name]);
        $this->logStep("NAME container created", ['NAME' => $name]);

        $replacements = ['{containerName}' => $name];

        $this->logStep('Enable CDN');
        require_once $this->sampleFile($replacements, 'containers/enable_cdn.php');

        $this->logStep('Get container');
        /** @var \Rackspace\ObjectStoreCDN\v1\Models\Container $container */
        require_once $this->sampleFile($replacements, 'containers/get.php');
        $this->assertInstanceOf(Container::class, $container);

        $this->logStep('List containers');
        require_once $this->sampleFile($replacements, 'containers/list.php');

        $this->logStep('Enable logging');
        require_once $this->sampleFile($replacements, 'containers/enable_logging.php');

        $this->logStep('Is CDN enabled?');
        /** @var bool $result */
        require_once $this->sampleFile($replacements, 'containers/is_cdn_enabled.php');
        $this->assertTrue($result);

        $this->logStep('Disable logging');
        require_once $this->sampleFile($replacements, 'containers/disable_logging.php');

        $this->logStep('Disable CDN');
        require_once $this->sampleFile($replacements, 'containers/disable_cdn.php');

        $this->logStep('Is logging enabled?');
        require_once $this->sampleFile($replacements, 'containers/is_logging_enabled.php');
        $this->assertFalse($result);

        $this->logStep('Deleting container');
        $this->getService()->getContainer($name)->delete();
    }

    protected function objects()
    {
        $name = $this->randomStr();
        $objectName = $this->randomStr();

        $container = $this->getService()->createContainer(['name' => $name]);
        $cdnContainer = $this->getCdnService()->getContainer($name);
        $cdnContainer->enableCdn();

        $replacements = ['{containerName}' => $name, '{objectName}' => $objectName];
        $container->createObject(['name' => $objectName]);

        $this->logStep('Get object from cache');
        /** @var \Rackspace\ObjectStoreCDN\v1\Models\Object $object */
        require_once $this->sampleFile($replacements, 'objects/get.php');
        $this->assertInstanceOf(Object::class, $object);

        $this->logStep('Delete object from cache');
        require_once $this->sampleFile($replacements, 'objects/delete.php');

        $container->getObject($objectName)->delete();
        $cdnContainer->disableCdn();
        $container->delete();
    }
}