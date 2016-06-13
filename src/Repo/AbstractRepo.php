<?php
namespace Nshiell\RepoInfo\Repo;

use Nshiell\RepoInfo\Api\ApiInterface;
use DateTime;

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
     * Gets all the open pull requests in the repo
     * @return array requests
     */
    abstract public function getOpenPullRequests();

    /**
     * Gets all issues after the datetime provided
     * @param  DateTime $dateTime
     * @return array issues
     */
    abstract public function getIssuesSince(DateTime $dateTime);
}
