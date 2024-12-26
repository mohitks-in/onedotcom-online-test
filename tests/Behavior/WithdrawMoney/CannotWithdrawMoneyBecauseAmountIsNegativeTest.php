<?php

declare(strict_types=1);

namespace App\Tests\Behavior\WithdrawMoney;

use App\Domain\BankAccount;
use App\Domain\Command\MoneyWithdraw;
use App\Domain\Event\MoneyDeposited;
use App\Domain\Exception\CannotWithdrawNegativeAmount;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class CannotWithdrawMoneyBecauseAmountIsNegativeTest extends BankAccountTestCase
{
    public function testMoneyWithdrawn_AmountIsNegative_ThrowsDomainException(): void
    {
        $this->given(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 250.00,
                oldBalance: 0
            )
        )->when(
            new MoneyWithdraw(
                amount : -100.00
            )
        )->expectToFail(
            new CannotWithdrawNegativeAmount()
        );
    }
}
