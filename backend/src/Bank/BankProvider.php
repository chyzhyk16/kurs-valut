<?php

declare(strict_types=1);

namespace App\Bank;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Traversable;

class BankProvider
{
    private array $banks;

    public function __construct(
        #[TaggedIterator('app.bank')]
        iterable $banks
    ) {
        $this->banks = $banks instanceof Traversable ? iterator_to_array($banks) : [];
    }

    /**
     * @return BankInterface[]
     */
    public function getBanks(): array
    {
        return $this->banks;
    }
}