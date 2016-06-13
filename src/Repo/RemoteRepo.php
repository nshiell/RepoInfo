<?php

namespace Nshiell\RepoInfo\Repo;

use DateTime;

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

    /**
     * {@inheritdoc}
     */
    public function getIssuesSince(DateTime $dateTime)
    {
        return $this->api->query(ApiInterface::QUERY_ISSUES_SINCE, [
            'datetime' => $dateTime
        ]);
    }
}
