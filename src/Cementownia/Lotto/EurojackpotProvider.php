<?php
declare(strict_types=1);

namespace Cementownia\Lotto;

use Cementownia\Result\AbstractHttpProvider;
use Cementownia\Result\AbstractResult;
use Cementownia\Result\ResultFetchException;
use PHPHtmlParser\Dom;

class EurojackpotProvider extends AbstractHttpProvider
{
    const URL = "https://www.lotto.pl/eurojackpot/wyniki-i-wygrane";
    const EXPECTED_NUMBERS = (7 + 1); // the +1 is due to "advantage" sign being matched by selector

    public function fetch(): AbstractResult
    {
        $html = $this->getHtml();
        $results = $this->extractResults($html);

        return $results;
    }

    private function extractResults(string $html): EurojackpotResult
    {
        $this->dom->load($html);

        $numbers = $this->extractNumbers();
        $outOf10 = array_splice($numbers, 5);

        return new EurojackpotResult($numbers, $outOf10);
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
        $numberNodes = $containerNode->find('div.number span');
        if ($numberNodes->count() !== self::EXPECTED_NUMBERS) {
            $this->logger->error("Unexpected number of numbers in the result",
                ['expected' => self::EXPECTED_NUMBERS, 'actual' => $numberNodes->count()]);
            throw new ResultFetchException("Unexpected number of numbers in result");
        }

        foreach ($numberNodes as $node) {
            /** @var Dom\HtmlNode $node */
            $parentCssClass = $node->getParent()->getAttribute('class');
            if (strpos($parentCssClass, 'advantageNumber')) {
                continue;
            }

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
