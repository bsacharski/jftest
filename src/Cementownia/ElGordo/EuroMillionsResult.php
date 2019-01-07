<?php

namespace Cementownia\ElGordo;

use Cementownia\Result\AbstractResult;

class EuroMillionsResult extends AbstractResult
{
    private $numbers;
    private $e1;
    private $e2;

    public function __construct(array $numbers, int $e1, int $e2)
    {
        $this->numbers = $numbers;
        $this->e1 = $e1;
        $this->e2 = $e2;
    }

    public function getGameName(): string
    {
        return 'EuroMillions';
    }

    public function getData(): array
    {
        $data = array_merge([], $this->numbers);
        $data['e1'] = $this->e1;
        $data['e2'] = $this->e2;

        return $data;
    }
}
