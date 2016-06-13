<?php

namespace Nshiell\Test\RepoInfo\Repo;

use PHPUnit\Framework\TestCase;

use DateTime;

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

    /**
     * @dataProvider queryTypesProvider
     */
    public function testQuery($queryType, $url, $data)
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
                $this->equalTo($url))
            ->will($this->returnValue($responseMock));


        $api = new ApiGitHub($httpClientMock);
        $result = $api->query($queryType, $data);

        $this->assertEquals(123, $result->a);
        $this->assertEquals(456, $result->b);
    }

    public function queryTypesProvider()
    {
        return [
            [
                ApiGitHub::QUERY_PULL_REQUESTS,
                'https://api.github.com/repos/symfony/symfony/pulls?state=open',
                null
            ],
            [
                ApiGitHub::QUERY_ISSUES_SINCE,
                'https://api.github.com/repos/symfony/symfony/issues?state=open&since=2005-05-01T05:06:07Z',
                [
                    'datetime' => new DateTime('2005-05-01 05:06:07')
                ]
            ]
        ];
    }
}
