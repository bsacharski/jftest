<?php

namespace Cementownia\Result;

abstract class AbstractResult
{
    abstract public function getData(): array;

    abstract public function getGameName(): string;
}
