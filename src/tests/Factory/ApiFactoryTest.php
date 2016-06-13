<?php

namespace Nshiell\Test\RepoInfo\Repo;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\ClientInterface;
use Nshiell\RepoInfo\Api\ApiGitHub;
use Nshiell\RepoInfo\Factory\ApiFactory;

class ApiFactoryTest extends TestCase
{
    public function testCreate()
    {
        $httpClientMock = $this->createMock(ClientInterface::class);

        $factory = new ApiFactory($httpClientMock);
        $result = $factory->create();

        $this->assertInstanceOf(ApiGitHub::class, $result);
    }
}
