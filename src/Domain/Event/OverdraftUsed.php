<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\BankAccountId;

final readonly class OverdraftUsed
{
    public function __construct(
        public BankAccountId $bankAccountId,
        public float $newLimit,
        public float $oldLimit,
    ) {
    }
}
