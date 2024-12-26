<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Projector;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\Event\OverdraftUsed;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepository;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnBankAccountOpenedProjector;
use App\Infrastructure\Projector\PersistBankAccountProjectionOnOverDraftUsedProjector;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(PersistBankAccountProjectionOnBankAccountOpenedProjector::class)]
final class PersistBankAccountProjectionOnOverDraftUsedProjectorTest extends KernelTestCase
{
    private PersistBankAccountProjectionOnOverDraftUsedProjector $projector;
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

        $this->projector = self::getContainer()->get(PersistBankAccountProjectionOnOverDraftUsedProjector::class);
    }

    public function testHandleOverdraftUsed(): void
    {
        $this->projector->handleOverdraftUsed(
            event: new OverdraftUsed(
                bankAccountId: $this->bankAccountId,
                newLimit: 100.00,
                oldLimit: 0,
            )
        );
        $bankAccountProjection = $this->projectionRepository->__invoke($this->bankAccountId);

        $this->assertNotNull($bankAccountProjection);
        $this->assertSame(100.0, $bankAccountProjection->overdraftLimit);
    }
}
