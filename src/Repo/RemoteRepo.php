<?php

namespace Nshiell\RepoInfo\Repo;

use Nshiell\RepoInfo\Api\ApiInterface;

/**
 * {@inheritdoc} for Symfony in GitHub
 */
class RemoteRepo extends AbstractRepo
{
    /**
     * {@inheritdoc}
     */
    public function getOpenPullRequests()
    {
        return $this->api->query(ApiInterface::QUERY_PULL_REQUESTS);
    }
}
