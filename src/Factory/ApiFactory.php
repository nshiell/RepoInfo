<?php

namespace Nshiell\RepoInfo\Factory;

use GuzzleHttp\ClientInterface;
use Nshiell\RepoInfo\Api\ApiGitHub;

final class ApiFactory
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
     * Creates an instance of the API
     * @return ApiInterface
     */
    public function create()
    {
        return new ApiGitHub($this->httpClient);
    }
}
