<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\BankAccountId;
use App\Domain\Event\MoneyDeposited;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MoneyDeposited::class)]
class MoneyDepositedTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: MoneyDeposited::class,
            actual: new MoneyDeposited(
                bankAccountId: BankAccountId::create(),
                newBalance: 500.00,
                oldBalance: 250.00
            )
        );
    }
}
