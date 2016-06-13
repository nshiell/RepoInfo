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
    /*->addParameter(new Parameter('H', 'host'    , '127.0.0.1')              , 'Host.')
    ->addParameter(new Parameter('u', 'username', Parameter::VALUE_REQUIRED), 'User name.')
    ->addParameter(new Parameter('p', 'password', Parameter::VALUE_REQUIRED), 'Password.')
    ->addParameter(new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE), 'Enable verbosity.')*/
    ->setProgram(function ($options, $arguments) {
        /** @var GuzzleHttp\ClientInterface */
        $httpClient = new GuzzleHttp\Client();

        $apiFactory = new Nshiell\RepoInfo\Factory\ApiFactory($httpClient);

        /** @var Nshiell\RepoInfo\Api\ApiInterface */
        //$api = $apiFactory->create($apiFactory::TYPE_GITHUB);
        $api = $apiFactory->create();

        $repo = new Nshiell\RepoInfo\Repo\RemoteRepo($api);
        $pullRequests = $repo->getOpenPullRequests();
        //echo json_encode($pullRequests, JSON_PRETTY_PRINT);
        //$pullRequests = json_decode(file_get_contents('/home/nicholas/Documents/RepoInfo/d.json'));

        $pullRequestsInformation = array_map(function ($pullRequest) {
            return [
                $pullRequest->id,
                $pullRequest->user->login,
                $pullRequest->created_at,
                $pullRequest->head->label
            ];
        }, $pullRequests);

        array_unshift($pullRequestsInformation, ['ID', 'User', 'Created', 'Label']);

        echo IO::strPadAll(
            $pullRequestsInformation,
            [ // alignment
                3 => STR_PAD_RIGHT,
            ],
            "\n", // line separator
            '   ' // field separator
        );
        echo PHP_EOL;
    })
    ->start();
