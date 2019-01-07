<?php

namespace Cementownia\Result;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom;
use Psr\Log\LoggerInterface;

abstract class AbstractHttpProvider implements ResultProviderInterface
{
    /** @var ClientInterface */
    protected $client;
    /** @var Dom */
    protected $dom;
    /** @var LoggerInterface */
    protected $logger;

    public function __construct(ClientInterface $client, Dom $domParser, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->dom = $domParser;
        $this->logger = $logger;
    }

    protected function getHtml(): string
    {
        try {
            $request = $this->client->request('GET', $this->getUrl());
            return $request->getBody()->getContents();
        } catch (GuzzleException $e) {
            $this->logger->error("Failed to get the response", ['message' => $e->getMessage()]);
            throw new ResultFetchException("Failed to get data from remote");
        }
    }

    abstract protected function getUrl(): string;
}
