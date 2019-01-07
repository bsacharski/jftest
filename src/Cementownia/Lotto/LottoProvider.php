<?php
declare(strict_types=1);

namespace Cementownia\Lotto;

use Cementownia\Result\AbstractHttpProvider;
use Cementownia\Result\AbstractResult;
use Cementownia\Result\ResultFetchException;
use PHPHtmlParser\Dom;

class LottoProvider extends AbstractHttpProvider
{
    const EXPECTED_NUMBERS = 6;
    const URL = 'https://www.lotto.pl/lotto/wyniki-i-wygrane';

    public function fetch(): AbstractResult
    {
        $html = $this->getHtml();
        $results = $this->extractResults($html);

        return $results;
    }

    private function extractResults(string $html): LottoResult
    {
        $this->dom->load($html);

        $numbers = $this->extractNumbers();
        return new LottoResult($numbers);
    }

    private function extractNumbers(): array
    {
        $numbers = [];
        /** @var Dom\Collection $nodes */
        $nodes = $this->dom->find('.wynik .resultsItem');
        if ($nodes->count() === 0) {
            throw new ResultFetchException("No results on the page");
        }

        $containerNode = $nodes[0];
        /** @var Dom\Collection $numberNodes */
        $numberNodes = $containerNode->find('span');
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

    protected function getUrl(): string
    {
        return self::URL;
    }


}
