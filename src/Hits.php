<?php

declare(strict_types=1);

namespace Vostelmakh\HitsOfCode;

class Hits
{
    public function __construct(
        private int $total
    ) {
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
