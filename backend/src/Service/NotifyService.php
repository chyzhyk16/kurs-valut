<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CurrencyExchangeRateDto;
use App\Notifier\NotifierInterface;

class NotifyService
{
    public function __construct(
        private NotifierInterface $notifier
    ) {
    }
    public function notifyUser(string $bankName, array $currencies): void
    {
        $this->notifier->notify($this->formatMessage($bankName, $currencies));
    }

    private function formatMessage(string $bankName, array $currencies): string
    {
        $currenciesList = array_map(fn($value): string => $value->getTargetCurrency() . '/' . $value->getBaseCurrency(), $currencies);

        /** @var CurrencyExchangeRateDto $value */
        $currenciesCodesList = implode(',', $currenciesList);

        return sprintf('The exchange rate of these currencies [%s] has changed in %s.', $currenciesCodesList, $bankName);
    }
}