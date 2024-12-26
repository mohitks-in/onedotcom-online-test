<?php

declare(strict_types=1);

namespace App\Api\Dto;

final readonly class MoneyDepositDto
{
    public function __construct(
        public float $amount,
    ) {
    }
}
