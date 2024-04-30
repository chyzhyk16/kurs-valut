<?php

declare(strict_types=1);

namespace App\Bank;

use App\DTO\CurrencyExchangeRateDto;
use App\Enum\Currency;

class MonoBank implements BankInterface
{
    public const BANK_NAME = 'MonoBank';
    private const USD_CODE = 840;
    private const EUR_CODE = 978;
    private const UAH_CODE = 980;
    private const API_URL = 'https://api.monobank.ua/';

    private const EXCHANGE_RATE_PATH = 'bank/currency';

    public function getExchangeRate(): array
    {
        $json = file_get_contents(self::API_URL . self::EXCHANGE_RATE_PATH);

        $currencyExchangeRates = [];

        foreach (json_decode($json, true) as $item) {
            if ($item['currencyCodeB'] == self::UAH_CODE) {
                if ($item['currencyCodeA'] == self::USD_CODE) {
                    $currencyExchangeRates[] = new CurrencyExchangeRateDto(
                        Currency::USD->value,
                        Currency::UAH->value,
                        floatval($item['rateBuy']),
                        floatval($item['rateSell']),
                    );
                } elseif ($item['currencyCodeA'] == self::EUR_CODE) {
                    $currencyExchangeRates[] = new CurrencyExchangeRateDto(
                        Currency::EUR->value,
                        Currency::UAH->value,
                        floatval($item['rateBuy']),
                        floatval($item['rateSell']),
                    );
                }
            }
        }

        return $currencyExchangeRates;
    }

    public function getBankName(): string
    {
        return self::BANK_NAME;
    }
}