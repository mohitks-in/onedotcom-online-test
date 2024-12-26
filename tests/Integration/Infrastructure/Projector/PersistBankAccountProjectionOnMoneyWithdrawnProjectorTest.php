<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Projector;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\Event\MoneyWithdrawn;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepository;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnBankAccountOpenedProjector;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnMoneyWithdrawnProjector;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(PersistBankAccountProjectionOnBankAccountOpenedProjector::class)]
final class PersistBankAccountProjectionOnMoneyWithdrawnProjectorTest extends KernelTestCase
{
    private PersistBankAccountProjectionOnMoneyWithdrawnProjector $projector;
    private BankAccountProjectionRepository $projectionRepository;
    private BankAccountId $bankAccountId;

    protected function setUp(): void
    {
        $projector = self::getContainer()->get(PersistBankAccountProjectionOnBankAccountOpenedProjector::class);
        $this->projectionRepository = self::getContainer()->get(BankAccountProjectionRepository::class);
        $this->bankAccountId = BankAccountId::create();

        $projector->handleBankAccountOpened(
            event: new BankAccountOpened(
                bankAccountId: $this->bankAccountId,
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        );
        $this->projectionRepository->__invoke($this->bankAccountId);

        $this->projector = self::getContainer()->get(PersistBankAccountProjectionOnMoneyWithdrawnProjector::class);
    }

    public function testHandleMoneyWithdrawn(): void
    {
        $this->projector->handleMoneyWithdrawn(
            event: new MoneyWithdrawn(
                bankAccountId: $this->bankAccountId,
                newBalance: 50.00,
                oldBalance: 100.00,
            )
        );

        $bankAccountProjection = $this->projectionRepository->__invoke($this->bankAccountId);

        $this->assertNotNull($bankAccountProjection);
        $this->assertSame(50.0, $bankAccountProjection->balance);
    }
}
