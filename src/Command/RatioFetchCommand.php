<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Phil\MoneyBundle\MoneyException;
use Phil\MoneyBundle\Pair\PairManagerInterface;

/**
 * Class RatioFetchCommand.“
 */
class RatioFetchCommand extends Command
{
    public function __construct(private PairManagerInterface $pairManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('phil:money:ratio-fetch')
            ->setHelp('The <info>phil:money:ratio-fetch</info> fetch all needed ratio from a external ratio provider')
            ->setDescription('fetch all needed ratio from a external ratio provider');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->pairManager->saveRatioListFromRatioProvider();
            $output->writeln('ratio fetched from provider'.PHP_EOL.print_r($this->pairManager->getRatioList(), true));

            return Command::SUCCESS;
        } catch (MoneyException $e) {
            $output->writeln('ERROR during fetch ratio : '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
