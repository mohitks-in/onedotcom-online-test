<?php

declare(strict_types=1);

namespace App\Tests\Behavior\DepositMoney;

use App\Domain\BankAccount;
use App\Domain\Command\MoneyDeposit;
use App\Domain\Event\MoneyDeposited;
use App\Domain\Exception\CannotDepositNegativeAmount;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class CannotDepositMoneyBecauseAmountIsNegative extends BankAccountTestCase
{
    public function testCloseBankAccount_AccountIsClosed_ThrowsDomainException(): void
    {
        $this->given(
            new MoneyDeposited(
                bankAccountId: $this->aggregateRootId(),
                newBalance: 250.00,
                oldBalance: 0
            )
        )->when(
            new MoneyDeposit(
                amount: -100.00
            )
        )->expectToFail(
            new CannotDepositNegativeAmount()
        );
    }
}
