<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests\Command;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Phil\MoneyBundle\Command\RatioFetchCommand;
use Phil\MoneyBundle\MoneyException;
use Phil\MoneyBundle\Pair\PairManagerInterface;

class RatioFetchCommandTest extends KernelTestCase
{
    private MockObject|PairManagerInterface $pairManager;

    protected function setUp(): void
    {
        $this->pairManager = $this->createMock(PairManagerInterface::class);
    }

    public function testWillWriteOk(): void
    {
        $this->pairManager
            ->expects($this->once())
            ->method('saveRatioListFromRatioProvider');
        $this->pairManager
            ->expects($this->once())
            ->method('getRatioList')
            ->willReturn(['EUR' => 1.1, 'USD' => 1.2]);

        $command = new RatioFetchCommand($this->pairManager);
        $tester = new CommandTester($command);
        $tester->execute([]);
        self::assertSame(Command::SUCCESS, $tester->getStatusCode());
        $output = $tester->getDisplay();
        self::assertStringContainsString('[EUR] => 1.1', $output);
    }

    public function testWillWriteExceptionMessage(): void
    {
        $this->pairManager
            ->expects($this->once())
            ->method('saveRatioListFromRatioProvider')
            ->willThrowException(new MoneyException('test'));

        $this->pairManager
            ->expects($this->never())
            ->method('getRatioList');

        $command = new RatioFetchCommand($this->pairManager);
        $tester = new CommandTester($command);
        $tester->execute([]);
        self::assertSame(Command::FAILURE, $tester->getStatusCode());
        $output = $tester->getDisplay();
        self::assertStringContainsString('ERROR during fetch ratio : test', $output);
    }
}
