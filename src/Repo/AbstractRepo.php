<?php
namespace Nshiell\RepoInfo\Repo;

use Nshiell\RepoInfo\Api\ApiInterface;

/**
 * Represents a source code repository
 */
abstract class AbstractRepo
{
    /** @var ApiInterface */
    protected $api;

    /**
     * @param ApiInterface $api for querying
     */
    public function __construct(ApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Shows all the open pull requests in the repo
     * @return array requests
     */
    abstract public function getOpenPullRequests();
}
