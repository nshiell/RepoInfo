<?php

namespace Nshiell\RepoInfo\Api;

interface ApiInterface
{
    const QUERY_PULL_REQUESTS = 'pull-requests';
    const QUERY_ISSUES_SINCE  = 'issues-since';

    /**
     * Query the API with the type given
     * @param  string $type one of ApiInterface::QUERY_...
     *
     * @throws InvalidArgumentException on unmatched $type
     * @throws UnexpectedValueException on bad response or response code is bad
     *
     * @return mixed the result
     */
    public function query($type);
}
