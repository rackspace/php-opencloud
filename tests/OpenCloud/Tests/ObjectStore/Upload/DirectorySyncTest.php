<?php

namespace OpenCloud\Tests\ObjectStore\Upload;

use Guzzle\Http\Url;
use OpenCloud\ObjectStore\Upload\DirectorySync;
use OpenCloud\Tests\OpenCloudTestCase;
use Prophecy\Argument;
use Prophecy\Prophet;

class DirectorySyncTest extends OpenCloudTestCase
{
    private $prophet;

    public function setUp()
    {
        $this->prophet = new Prophet();
    }

    public function test_it_uploads_to_a_container()
    {
        $baseUrl = 'foo.com/bar';

        $containerMock = $this->prophet->prophesize('OpenCloud\ObjectStore\Resource\Container');
        $iteratorMock = $this->prophet->prophesize('OpenCloud\Common\Collection\PaginatedIterator');

        $iteratorMock->rewind()->shouldBeCalled();
        $iteratorMock->populateAll()->shouldBeCalled();
        $iteratorMock->valid()->willReturn(false);
        $iteratorMock->search(Argument::type('callable'))->willReturn(false);

        $containerMock->getUrl()->willReturn(Url::factory($baseUrl));

        $guzzleMock = $this->prophet->prophesize('Guzzle\Http\Client');

        $guzzleMock->put($baseUrl . '/test1', [], Argument::type('Guzzle\Http\EntityBody'))->shouldBeCalled();
        $guzzleMock->send(Argument::that(function ($array) { return count($array) === 1; }))->shouldBeCalled();

        $containerMock->getClient()->willReturn($guzzleMock->reveal());

        $sync = new DirectorySync();
        $sync->setBasePath(__DIR__ . '/fixtures');
        $sync->setContainer($containerMock->reveal());
        $sync->setRemoteFiles($iteratorMock->reveal());

        $sync->execute();
    }

    public function test_it_uploads_to_a_nested_sub_dir_in_a_container()
    {
        $baseUrl = 'foo.com/bar';

        $containerMock = $this->prophet->prophesize('OpenCloud\ObjectStore\Resource\Container');
        $iteratorMock = $this->prophet->prophesize('OpenCloud\Common\Collection\PaginatedIterator');

        $iteratorMock->rewind()->shouldBeCalled();
        $iteratorMock->populateAll()->shouldBeCalled();
        $iteratorMock->valid()->willReturn(false);
        $iteratorMock->search(Argument::type('callable'))->willReturn(false);

        $containerMock->getUrl()->willReturn(Url::factory($baseUrl));

        $guzzleMock = $this->prophet->prophesize('Guzzle\Http\Client');

        $guzzleMock->put($baseUrl . '/sub-dir/test1', [], Argument::type('Guzzle\Http\EntityBody'))->shouldBeCalled();
        $guzzleMock->send(Argument::that(function ($array) { return count($array) === 1; }))->shouldBeCalled();

        $containerMock->getClient()->willReturn($guzzleMock->reveal());

        $sync = new DirectorySync();
        $sync->setBasePath(__DIR__ . '/fixtures');
        $sync->setContainer($containerMock->reveal());
        $sync->setRemoteFiles($iteratorMock->reveal());
        $sync->setTargetDir('sub-dir');

        $sync->execute();
    }
}
