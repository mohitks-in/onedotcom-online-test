<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Command;

use App\Domain\Command\MoneyDeposit;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MoneyDeposit::class)]
class MoneyDepositTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: MoneyDeposit::class,
            actual: new MoneyDeposit(
                amount: 250.00
            )
        );
    }
}
