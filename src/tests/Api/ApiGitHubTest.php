<?php

namespace Nshiell\Test\RepoInfo\Repo;

use PHPUnit\Framework\TestCase;

use UnexpectedValueException;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\ResponseInterface;

use Nshiell\RepoInfo\Api\ApiGitHub;

class ApiGitHubTest extends TestCase
{
    public function testBadResponse()
    {
        $responseMock = $this->getMockBuilder('StdClass')
            ->setMethods(['getStatusCode'])
            ->getMock();

        $responseMock->expects($this->once())
        ->method('getStatusCode')
        ->will($this->returnValue(500));

        $httpClientMock = $this->createMock(ClientInterface::class);

        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('https://api.github.com/repos/symfony/symfony/pulls?state=open'))
            ->will($this->returnValue($responseMock));


        $api = new ApiGitHub($httpClientMock);

        $this->expectException(UnexpectedValueException::class, 'Bad StatusCode 500');

        $result = $api->query($api::QUERY_PULL_REQUESTS);
    }

    public function testQuery()
    {
        $responseMock = $this->getMockBuilder('StdClass')
            ->setMethods(['getStatusCode', 'getBody'])
            ->getMock();

        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $responseMock->expects($this->once())
        ->method('getBody')
        ->will($this->returnValue('{"a": 123, "b": 456}'));

        $httpClientMock = $this->createMock(ClientInterface::class);

        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('https://api.github.com/repos/symfony/symfony/pulls?state=open'))
            ->will($this->returnValue($responseMock));


        $api = new ApiGitHub($httpClientMock);
        $result = $api->query($api::QUERY_PULL_REQUESTS);

        $this->assertEquals(123, $result->a);
        $this->assertEquals(456, $result->b);
    }
}
