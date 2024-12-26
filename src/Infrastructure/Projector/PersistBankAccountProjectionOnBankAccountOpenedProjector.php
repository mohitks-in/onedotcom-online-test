<?php

declare(strict_types=1);

namespace App\Infrastructure\Projector;

use App\Domain\Event\BankAccountOpened;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;

final class PersistBankAccountProjectionOnBankAccountOpenedProjector extends EventConsumer
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function handleBankAccountOpened(BankAccountOpened $event): void
    {
        $this->connection->insert(
            table: 'bank_account_projection',
            data: [
                'bank_account_id' => $event->bankAccountId->toString(),
                'account_holder_name' => $event->accountHolderName,
                'balance' => 0.0,
                'account_type' => $event->accountType->value,
                'currency_code' => $event->currency->value,
                'overdraft_limit' => 0.0,
            ]
        );
    }
}
