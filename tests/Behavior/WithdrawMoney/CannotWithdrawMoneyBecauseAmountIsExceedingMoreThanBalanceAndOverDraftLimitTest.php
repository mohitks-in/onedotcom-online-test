<?php

declare(strict_types=1);

namespace App\Tests\Behavior\WithdrawMoney;

use App\Domain\BankAccount;
use App\Domain\Command\MoneyWithdraw;
use App\Domain\Event\MoneyDeposited;
use App\Domain\Event\OverdraftLimitSet;
use App\Domain\Exception\CannotWithdrawAmountMoreThanBalanceAndOverDraftLimit;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class CannotWithdrawMoneyBecauseAmountIsExceedingMoreThanBalanceAndOverDraftLimitTest extends BankAccountTestCase
{
    public function testMoneyWithdrawn_ExceedFromLimit_ThrowsDomainException(): void
    {
        $this->given(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 500.00,
                oldBalance: 0
            ),
            new OverdraftLimitSet(
                bankAccountId: $this->aggregateRootId(),
                newOverdraftLimit: 1500.0,
                oldOverdraftLimit: 0
            )
        )->when(
            new MoneyWithdraw(
                amount: 10000.00
            )
        )->expectToFail(
            new CannotWithdrawAmountMoreThanBalanceAndOverDraftLimit()
        );
    }
}
