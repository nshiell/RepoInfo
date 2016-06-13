#!/usr/bin/env php
<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Cli\Helpers\DocumentedScript;
use Cli\Helpers\Parameter;
use \Cli\Helpers\IO;

$script = new DocumentedScript();
$script
    ->setName(__FILE__)
    ->setVersion('1.0')
    ->setDescription('Remote Repository Information')
    ->setCopyright('Copyright (c) NShiell 2016')
    ->addParameter(new Parameter('d', 'date' , '2016-05-01 05:06:07') , 'Date To List From')
    ->setProgram(function ($options, $arguments) {
        if (!isset ($arguments[1])) {
            throw new InvalidArgumentException('List name not provided');
        }

        /** @var GuzzleHttp\ClientInterface */
        $httpClient = new GuzzleHttp\Client();

        $apiFactory = new Nshiell\RepoInfo\Factory\ApiFactory($httpClient);

        /** @var Nshiell\RepoInfo\Api\ApiInterface */
        //$api = $apiFactory->create($apiFactory::TYPE_GITHUB);
        $api = $apiFactory->create();

        $repo = new Nshiell\RepoInfo\Repo\RemoteRepo($api);

        switch ($arguments[1]) {
            case $api::QUERY_PULL_REQUESTS:
                $items = $repo->getOpenPullRequests();
                break;
            case $api::QUERY_ISSUES_SINCE:
                $items = $repo->getIssuesSince(new DateTime($options['date']));
                break;
            default:
                throw new InvalidArgumentException('List name not known: '.$arguments[1]);
        }


        //echo json_encode($pullRequests, JSON_PRETTY_PRINT);
        //$items = json_decode(file_get_contents('/home/nicholas/Documents/RepoInfo/d.json'));

        $itemsInformation = array_map(function ($item) {
            return [
                $item->id,
                $item->user->login,
                $item->created_at,
                $item->title
            ];
        }, $items);

        array_unshift($itemsInformation, ['ID', 'User', 'Created', 'Title']);

        echo ucwords(str_replace('-', ' ', $arguments[1])) . ':' . PHP_EOL;
        echo IO::strPadAll(
            $itemsInformation,
            [ // alignment
                3 => STR_PAD_RIGHT,
            ],
            "\n", // line separator
            '   ' // field separator
        );
        echo PHP_EOL;
    })
    ->start();
