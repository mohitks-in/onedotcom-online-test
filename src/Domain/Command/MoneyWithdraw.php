<?php

declare(strict_types=1);

namespace App\Domain\Command;

final readonly class MoneyWithdraw
{
    public function __construct(
        public float $amount,
    ) {
    }
}
