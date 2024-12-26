<?php

declare(strict_types=1);

namespace App\Infrastructure\Projector;

use App\Domain\Event\OverdraftUsed;
use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;

final class PersistBankAccountProjectionOnOverDraftUsedProjector extends EventConsumer
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function handleOverDraftUsed(OverdraftUsed $event): void
    {
        $this->connection->update(
            table: 'bank_account_projection',
            data: [
                'overdraft_limit' => $event->newLimit,
            ],
            criteria: [
                'bank_account_id' => $event->bankAccountId->toString(),
            ]
        );
    }
}
