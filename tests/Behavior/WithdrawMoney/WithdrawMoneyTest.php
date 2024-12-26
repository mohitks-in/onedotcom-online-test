<?php

declare(strict_types=1);

namespace App\Tests\Behavior\WithdrawMoney;

use App\Domain\BankAccount;
use App\Domain\Command\MoneyWithdraw;
use App\Domain\Event\MoneyDeposited;
use App\Domain\Event\MoneyWithdrawn;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class WithdrawMoneyTest extends BankAccountTestCase
{
    public function testWithdrawMoney(): void
    {
        $this->given(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 1000.00,
                oldBalance: 0
            )
        )->when(
            new MoneyWithdraw(
                amount: 425.00
            )
        )->then(
            new MoneyWithdrawn(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 575.00,
                oldBalance: 1000.00
            )
        );

        $bankAccount = $this->retrieveAggregateRoot($this->aggregateRootId());
        $this->assertSame(575.00, $bankAccount->getBalance());
    }
}
