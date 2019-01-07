<?php
declare(strict_types=1);

namespace Cementownia\Result;

interface ResultProviderInterface
{
    public function fetch(): AbstractResult;
}
