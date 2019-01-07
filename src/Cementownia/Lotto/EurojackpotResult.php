<?php
declare(strict_types=1);

namespace Cementownia\Lotto;

use Cementownia\Exception;
use Cementownia\Result\AbstractResult;

class EurojackpotResult extends AbstractResult
{
    const OUT_OF_50_COUNT = 5;
    const OUT_OF_10_COUNT = 2;

    private $outOf50;
    private $outOf10;

    public function __construct(array $outOf50, array $outOf10)
    {
        $this->validateOutOf50($outOf50);
        $this->validateOutOf10($outOf10);

        $this->outOf50 = $outOf50;
        $this->outOf10 = $outOf10;
    }

    private function validateOutOf50(array $input)
    {
        $inputCount = count($input);

        if ($inputCount !== self::OUT_OF_50_COUNT) {
            throw new Exception("Wrong number of numbers detected: '$inputCount'");
        }

        if (!$this->isIntArray($input)) {
            throw new Exception("Input is not int-only array");
        }
    }

    private function isIntArray(array $input)
    {
        foreach ($input as $element) {
            if (!is_numeric($element)) {
                return false;
            }

            if (!$this->isInt($element)) {
                return false;
            }
        }

        return true;
    }

    private function isInt($value): bool
    {
        $intVal = intval($value);
        return floatval($intVal) === floatval($value);
    }

    private function validateOutOf10(array $input)
    {
        $inputCount = count($input);

        if ($inputCount !== self::OUT_OF_10_COUNT) {
            throw new Exception("Wrong number of numbers detected: '$inputCount'");
        }

        if (!$this->isIntArray($input)) {
            throw new Exception("Input is not int-only array");
        }
    }

    public function getData(): array
    {
        return [
            '5/50' => $this->outOf50,
            '2/10' => $this->outOf10,
        ];
    }

    public function getGameName(): string
    {
        return 'Eurojackpot';
    }
}
