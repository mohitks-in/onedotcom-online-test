<?php

declare(strict_types=1);

namespace App\Infrastructure\Projector;

use App\Domain\Event\MoneyWithdrawn;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;

final class PersistBankAccountProjectionOnMoneyWithdrawnProjector extends EventConsumer
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function handleMoneyWithdrawn(MoneyWithdrawn $event): void
    {
        $this->connection->update(
            table: 'bank_account_projection',
            data: [
                'balance' => $event->newBalance,
            ],
            criteria: [
                'bank_account_id' => $event->bankAccountId->toString(),
            ]
        );
    }
}
