<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ExchangeRateService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check-exchange-rate', description: 'Check exchange rate')]
class CheckExchangeRateCommand extends Command
{
    public function __construct(
        private ExchangeRateService $checkExchangeRateService,
        ?string                     $name = null
    )
    {
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command started');

        $this->checkExchangeRateService->checkExchangeRate();

        $output->writeln('Command finished');

        return Command::SUCCESS;
    }
}