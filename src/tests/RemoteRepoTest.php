<?php

namespace Nshiell\Test\RepoInfo\Repo;

use PHPUnit\Framework\TestCase;

use Nshiell\RepoInfo\Repo\RemoteRepo;
use Nshiell\RepoInfo\Api\ApiInterface;

class RemoteRepoTest extends TestCase
{
    public function testGetOpenPullRequests()
    {
        $apiMock = $this->createMock(ApiInterface::class);

        $mockReturn = [123];

        $apiMock
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo(ApiInterface::QUERY_PULL_REQUESTS))
            ->will($this->returnValue($mockReturn));

        $repo = new RemoteRepo($apiMock);

        $this->assertEquals(
            $mockReturn,
            $repo->getOpenPullRequests());
    }
}
