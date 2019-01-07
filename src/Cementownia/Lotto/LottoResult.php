<?php
declare(strict_types=1);

namespace Cementownia\Lotto;

use Cementownia\Result\AbstractResult;

class LottoResult extends AbstractResult
{
    /** @var array */
    private $numbers;

    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    public function getData(): array
    {
        return $this->numbers;
    }

    public function getGameName(): string
    {
        return 'Lotto';
    }
}
