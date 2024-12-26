<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Api\Dto\MoneyDepositDto;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\MoneyDeposit;
use EventSauce\EventSourcing\AggregateRootRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class MoneyDepositAction
{
    /**
     * @param AggregateRootRepository<BankAccount> $aggregateRootRepository
     */
    public function __construct(
        private AggregateRootRepository $aggregateRootRepository,
    ) {
    }

    #[Route('/money-deposit/{bankAccountId}', name: 'deposit_money', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] MoneyDepositDto $depositMoneyDto, string $bankAccountId): Response
    {
        $bankAccountId = BankAccountId::fromString($bankAccountId);

        $bankAccount = $this->aggregateRootRepository->retrieve($bankAccountId);
        $bankAccount->depositMoney(
            command: new MoneyDeposit(
                amount: $depositMoneyDto->amount
            )
        );

        $this->aggregateRootRepository->persist($bankAccount);

        return new JsonResponse(
            data: [
                'bankAccountId' => $bankAccountId->toString(),
            ],
            status: Response::HTTP_ACCEPTED
        );
    }
}
