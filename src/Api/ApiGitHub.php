<?php

namespace Nshiell\RepoInfo\Api;

use InvalidArgumentException;
use UnexpectedValueException;
use GuzzleHttp\ClientInterface;

class ApiGitHub implements ApiInterface
{
    /** @var GuzzleHttp\ClientInterface */
    private $httpClient;

    /**
     * @param ClientInterface $httpClient for querying
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function query($type, $data = null)
    {
        switch ($type) {
            case self::QUERY_PULL_REQUESTS:
                $url = 'https://api.github.com/repos/symfony/symfony/pulls?state=open';
                break;
            case self::QUERY_ISSUES_SINCE:
                $url = 'https://api.github.com/repos/symfony/symfony/issues?state=open'
                    .'&since='.$data['datetime']->format('Y-m-d\TH:i:s\Z');
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown type "%s"', $type));
        }

        $response = $this->httpClient->request('GET', $url);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            throw new UnexpectedValueException(sprintf('Bad statuscode: "%d"', $statusCode));
        }

        return json_decode($response->getBody());
    }
}
