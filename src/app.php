<?php

require_once __DIR__ . '/../vendor/autoload.php';

/** @var Psr\Log\LoggerInterface $logger */
$logger = new Monolog\Logger('app');

$logger->info("starting");

$client = new GuzzleHttp\Client();

$euroMillions = new \Cementownia\ElGordo\EuroMillionsProvider($client, new \PHPHtmlParser\Dom(), $logger);
$lotto = new \Cementownia\Lotto\LottoProvider($client, new \PHPHtmlParser\Dom(), $logger);
$euroJackpot = new \Cementownia\Lotto\EurojackpotProvider($client, new \PHPHtmlParser\Dom(), $logger);

$manager = new \Cementownia\Result\ProviderManager($logger);
$manager->add($euroMillions);
$manager->add($lotto);
$manager->add($euroJackpot);

$results = $manager->fetchAll();

$resultWriter = new \Cementownia\Result\ResultsWriter();
$out = getcwd() . '/out.json';
$resultWriter->write($results, $out);

$logger->info('done');
