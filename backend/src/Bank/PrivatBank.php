<?php

declare(strict_types=1);

namespace App\Bank;

use App\DTO\CurrencyExchangeRateDto;

class PrivatBank implements BankInterface
{
    public const BANK_NAME = 'PrivatBank';
    private const API_URL = 'https://api.privatbank.ua/';

    private const EXCHANGE_RATE_PATH = 'p24api/pubinfo?exchange&coursid=11';

    public function getExchangeRate(): array
    {
        $json = file_get_contents(self::API_URL . self::EXCHANGE_RATE_PATH);

        $currencyExchangeRates = [];

        foreach (json_decode($json, true) as $item) {
            $currencyExchangeRates[] = new CurrencyExchangeRateDto(
                $item['ccy'],
                $item['base_ccy'],
                floatval($item['buy']),
                floatval($item['sale']),
            );
        }

        return $currencyExchangeRates;
    }

    public function getBankName(): string
    {
        return self::BANK_NAME;
    }
}