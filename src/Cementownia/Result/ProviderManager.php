<?php

namespace Cementownia\Result;

use Psr\Log\LoggerInterface;

class ProviderManager
{
    private $logger;
    private $providers = [];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function add(ResultProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function fetchAll(): array
    {
        $results = [];

        foreach ($this->providers as $provider) {
            /** @var ResultProviderInterface $provider */
            $result = $provider->fetch();

            $results[$result->getGameName()] = $result->getData();
        }

        return $results;
    }
}
