<?php

declare(strict_types=1);

namespace Vostelmakh\HitsOfCode;

use DateTimeInterface;

class Hits
{
    public function __construct(
        private DateTimeInterface $date,
        private int $total
    ) {
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}