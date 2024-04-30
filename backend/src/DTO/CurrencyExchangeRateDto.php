<?php

declare(strict_types=1);

namespace App\DTO;

readonly class CurrencyExchangeRateDto
{
    public function __construct(
        private string $baseCurrency,
        private string $targetCurrency,
        private float  $buyRate,
        private float  $sellRate
    ) {}

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): string
    {
        return $this->targetCurrency;
    }

    public function getBuyRate(): float
    {
        return $this->buyRate;
    }

    public function getSellRate(): float
    {
        return $this->sellRate;
    }
}