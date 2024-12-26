<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Command;

use App\Domain\Command\MoneyWithdraw;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MoneyWithdraw::class)]
class MoneyWithdrawTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: MoneyWithdraw::class,
            actual: new MoneyWithdraw(
                amount: 250.00
            )
        );
    }
}
