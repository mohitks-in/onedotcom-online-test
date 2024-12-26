<?php

declare(strict_types=1);

namespace App\Tests\Behavior;

use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\CloseBankAccount;
use App\Domain\Command\MoneyDeposit;
use App\Domain\Command\MoneyWithdraw;
use App\Domain\Command\OpenBankAccount;
use App\Domain\Command\SetOverdraftLimit;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

abstract class BankAccountTestCase extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return BankAccountId::create();
    }

    protected function aggregateRootClassName(): string
    {
        return BankAccount::class;
    }

    protected function aggregateRootId(): BankAccountId
    {
        /** @var BankAccountId $bankAccountId */
        $bankAccountId = parent::aggregateRootId();

        return $bankAccountId;
    }

    protected function retrieveAggregateRoot(AggregateRootId $id): BankAccount
    {
        /** @var BankAccount $bankAccount */
        $bankAccount = parent::retrieveAggregateRoot($id);

        return $bankAccount;
    }

    public function handle($command): void
    {
        if ($command instanceof OpenBankAccount) {
            $bankAccount = BankAccount::openBankAccount($command);
        } else {
            $bankAccount = $this->retrieveAggregateRoot($this->aggregateRootId());
        }

        if ($command instanceof SetOverdraftLimit) {
            $bankAccount->setOverdraftLimit($command);
        } elseif ($command instanceof CloseBankAccount) {
            $bankAccount->closeBankAccount($command);
        } elseif ($command instanceof MoneyDeposit) {
            $bankAccount->depositMoney($command);
        } elseif ($command instanceof MoneyWithdraw) {
            $bankAccount->withdrawMoney($command);
        }

        $this->repository->persist($bankAccount);
    }
}
