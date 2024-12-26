<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\BankAccountId;

final readonly class MoneyDeposited
{
    public function __construct(
        public BankAccountId $bankAccountId,
        public float $newBalance,
        public float $oldBalance,
    ) {
    }
}
