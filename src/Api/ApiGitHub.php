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
    public function query($type)
    {
        $response = $this->httpClient->request('GET', 'https://api.github.com/repos/symfony/symfony/pulls?state=open');
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            throw new UnexpectedValueException(sprintf('Bad statuscode: "%d"', $statusCode));
        }

        return json_decode($response->getBody());
    }
}
