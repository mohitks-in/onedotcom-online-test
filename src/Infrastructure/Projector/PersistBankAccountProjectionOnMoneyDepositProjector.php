<?php

declare(strict_types=1);

namespace App\Infrastructure\Projector;

use App\Domain\Event\MoneyDeposited;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;

final class PersistBankAccountProjectionOnMoneyDepositProjector extends EventConsumer
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function handleMoneyDeposited(MoneyDeposited $event): void
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
