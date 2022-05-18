<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Phil\MoneyBundle\Pair\PairManagerInterface;

/**
 * Class RatioListCommand.
 */
class RatioListCommand extends Command
{
    public function __construct(private PairManagerInterface $pairManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('phil:money:ratio-list')
            ->setHelp('The <info>phil:money:ratio-list</info> display list of registered ratio')
            ->setDescription('display list of registered ratio');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ratioList = $this->pairManager->getRatioList();
        $output->writeln('Ratio list');
        /**
         * @var float $ratio
         */
        foreach ($ratioList as $currencyCode => $ratio) {
            $output->writeln($currencyCode.';'.(string) $ratio);
        }

        return 0;
    }
}
