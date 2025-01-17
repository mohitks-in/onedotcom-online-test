<?php

declare(strict_types=1);

namespace App\Tests\Application\DepositMoney;

use App\Api\Action\MoneyDepositAction;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\OpenBankAccount;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Tests\Application\ApplicationTestCase;
use EventSauce\EventSourcing\AggregateRootRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

#[CoversClass(MoneyDepositAction::class)]
final class MoneyDepositTest extends ApplicationTestCase
{
    private KernelBrowser $client;
    private BankAccountId $bankAccountId;

    public function setUp(): void
    {
        $this->client = self::createClient();
        self::getContainer()->get(AggregateRootRepository::class)->persist(
            BankAccount::openBankAccount(
                command: new OpenBankAccount(
                    bankAccountId: $this->bankAccountId = BankAccountId::create(),
                    accountHolderName: 'Test Name',
                    accountType: AccountType::SAVINGS,
                    currency: Currency::EUR
                )
            )
        );
    }

    public function testMoneyDeposit(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/money-deposit/'.$this->bankAccountId->toString(),
            content: json_encode([
                'amount' => 300,
            ]),
        );

        $this->assertResponseIsSuccessful();

        $bankAccount = self::getContainer()->get(AggregateRootRepository::class)->retrieve($this->bankAccountId);
        $this->assertSame(300.0, $bankAccount->getBalance());
    }
}
