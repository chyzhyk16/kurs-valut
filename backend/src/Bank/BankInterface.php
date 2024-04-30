<?php

namespace App\Bank;

use App\DTO\CurrencyExchangeRateDto;

interface BankInterface
{
    /**
     * @return CurrencyExchangeRateDto[]
     */
    public function getExchangeRate(): array;

    public function getBankName(): string;
}