<?php

declare(strict_types=1);

namespace App\Tests\Behavior\WithdrawMoney;

use App\Domain\BankAccount;
use App\Domain\Command\MoneyWithdraw;
use App\Domain\Event\MoneyDeposited;
use App\Domain\Event\MoneyWithdrawn;
use App\Domain\Event\OverdraftLimitSet;
use App\Domain\Event\OverdraftUsed;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class WithdrawMoneyUsingOverDraftTest extends BankAccountTestCase
{
    public function testWithdrawMoneyUsingOverDraftTest(): void
    {
        $this->given(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 100.00,
                oldBalance: 0
            ),
            new OverdraftLimitSet(
                bankAccountId: $this->aggregateRootId(),
                newOverdraftLimit: 200.0,
                oldOverdraftLimit: 0
            )
        )->when(
            new MoneyWithdraw(
                amount: 150.00
            )
        )->then(
            new OverdraftUsed(
                bankAccountId: $this->aggregateRootId(),
                newLimit: 150.00,
                oldLimit: 200.00
            ),
            new MoneyWithdrawn(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 0.00,
                oldBalance: 100.00
            )
        );

        $bankAccount = $this->retrieveAggregateRoot($this->aggregateRootId());
        $this->assertSame(0.00, $bankAccount->getBalance());
        $this->assertSame(150.0, $bankAccount->getOverdraftLimit());
    }
}
