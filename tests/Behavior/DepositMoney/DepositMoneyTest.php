<?php

declare(strict_types=1);

namespace App\Tests\Behavior\DepositMoney;

use App\Domain\BankAccount;
use App\Domain\Command\MoneyDeposit;
use App\Domain\Event\MoneyDeposited;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class DepositMoneyTest extends BankAccountTestCase
{
    public function testDepositMoney(): void
    {
        $this->given(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 250.00,
                oldBalance: 0
            )
        )->when(
            new MoneyDeposit(
                amount: 500.00
            )
        )->then(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 750.00,
                oldBalance: 250.00
            )
        );

        $bankAccount = $this->retrieveAggregateRoot($this->aggregateRootId());
        $this->assertSame(750.00, $bankAccount->getBalance());
    }
}
