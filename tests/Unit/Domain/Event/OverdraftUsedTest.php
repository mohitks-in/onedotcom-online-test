<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\BankAccountId;
use App\Domain\Event\OverdraftUsed;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OverdraftUsed::class)]
class OverdraftUsedTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: OverdraftUsed::class,
            actual: new OverdraftUsed(
                bankAccountId: BankAccountId::create(),
                newLimit: 500.00,
                oldLimit: 250.00
            )
        );
    }
}
