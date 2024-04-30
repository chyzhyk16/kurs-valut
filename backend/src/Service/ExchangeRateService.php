<?php

declare(strict_types=1);

namespace App\Service;

use App\Bank\BankProvider;
use App\DTO\CurrencyExchangeRateDto;
use App\Storage\StorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ExchangeRateService
{
    public function __construct(
        private NotifyService       $notifyService,
        private BankProvider        $apiProvider,
        private StorageInterface    $storage,
        private SerializerInterface $serializer,
        private float               $threshold
    )
    {
    }

    public function checkExchangeRate(): void
    {
        $banksApi = $this->apiProvider->getBanks();

        foreach ($banksApi as $bankApi) {
            $apiRates = $bankApi->getExchangeRate();
            $dbRates = $this->storage->read($bankApi->getBankName());

            if ($dbRates != '') {
                $dbExchangeRates = $this->serializer->deserialize($dbRates, CurrencyExchangeRateDto::class . '[]', 'json');
                $changedCurrencies = $this->getChangedCurrencies($dbExchangeRates, $apiRates);

                if ($changedCurrencies) {
                    $this->notifyService->notifyUser($bankApi->getBankName(), $changedCurrencies);
                }
            }

            $this->storage->write($this->serializer->serialize($apiRates, 'json'), $bankApi->getBankName());
        }
    }

    /**
     * @param CurrencyExchangeRateDto[] $bankExchangeRateFromDb
     * @param CurrencyExchangeRateDto[] $bankExchangeRateFromApi
     */
    public function getChangedCurrencies(array $bankExchangeRateFromDb, array $bankExchangeRateFromApi): array
    {
        $changedCurrencies = [];

        foreach ($bankExchangeRateFromApi as $currency) {
            foreach ($bankExchangeRateFromDb as $dbCurrency) {
                if (
                    $currency->getTargetCurrency() === $dbCurrency->getTargetCurrency()
                    && $currency->getBaseCurrency() === $dbCurrency->getBaseCurrency()
                ) {
                    if ($this->checkIfRateDifferenceIsBiggerThanThreshold($currency, $dbCurrency)) {
                        $changedCurrencies[] = $currency;
                    }
                }
            }
        }

        return $changedCurrencies;
    }

    private function checkIfRateDifferenceIsBiggerThanThreshold(CurrencyExchangeRateDto $firstCurrency, CurrencyExchangeRateDto $secondCurrency): bool
    {
        if (
            abs($firstCurrency->getBuyRate() - $secondCurrency->getBuyRate()) >= $this->threshold
            || abs($firstCurrency->getSellRate() - $secondCurrency->getSellRate()) >= $this->threshold
        ) {
            return true;
        }

        return false;
    }
}