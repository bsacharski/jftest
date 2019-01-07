<?php

namespace Cementownia\ElGordo;

use Cementownia\Result\AbstractHttpProvider;
use Cementownia\Result\AbstractResult;
use Cementownia\Result\ResultFetchException;
use PHPHtmlParser\Dom;

class EuroMillionsProvider extends AbstractHttpProvider
{
    const EXPECTED_NUMBERS = 7;
    const URL = 'https://www.elgordo.com/results/euromillonariaen.asp';

    /**
     * @return AbstractResult
     * @throws ResultFetchException
     */
    public function fetch(): AbstractResult
    {
        $html = $this->getHtml();
        $results = $this->extractResults($html);

        return $results;
    }

    private function extractResults(string $html): EuroMillionsResult
    {
        $this->dom->load($html);

        $numbers = $this->extractNumbers();

        return $this->buildResultsFromNumbers($numbers);
    }

    private function extractNumbers(): array
    {
        $numbers = [];

        /** @var Dom\Collection $numberNodes */
        $numberNodes = $this->dom->find('.int-num');
        if ($numberNodes->count() !== self::EXPECTED_NUMBERS) {
            $this->logger->error("Unexpected number of numbers in the result",
                ['expected' => self::EXPECTED_NUMBERS, 'actual' => $numberNodes->count()]);
            throw new ResultFetchException("Unexpected number of numbers in result");
        }

        foreach ($numberNodes as $node) {
            /** @var Dom\HtmlNode $node */
            $value = $node->innerHtml();
            if (!is_numeric($value)) {
                $this->logger->error("Unexpected data in the result node", ['value' => $value]);
                throw new ResultFetchException("Unexpected data");
            }

            $numbers[] = intval($value);
        }

        return $numbers;
    }

    private function buildResultsFromNumbers(array $numbers): EuroMillionsResult
    {
        $e2 = array_pop($numbers);
        $e1 = array_pop($numbers);
        return new EuroMillionsResult($numbers, $e1, $e2);
    }

    protected function getUrl(): string
    {
        return self::URL;
    }
}
