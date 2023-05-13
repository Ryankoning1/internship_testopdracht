<?php declare(strict_types=1);

namespace App\Account\Command;

use App\Account\Service\AccountService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'account:import', description: 'Imports new accounts', aliases: ['account:import'], hidden: false
)]
class EnrichAccountsFromRandomUserApi extends Command
{
    protected static $defaultDescription = 'Imports new accounts';

    public function __construct(
        private readonly AccountService $accountService,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command imports new accounts into the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->output = $output;
            $this->printLine('start');

            // Execute the command
            $this->accountService->importAccounts();

            $this->printLine('done');
            return Command::SUCCESS;
        } catch (Exception $exception) {
            $this->output->writeln('');
            $this->output->writeln('ERROR: '.$exception->getMessage());
            $this->output->writeln('');
            return Command::FAILURE;
        }
    }


    private function printLine($line)
    {
        $this->output->writeln('');
        $this->output->writeln('--------------------------------------------------');
        $this->output->writeln('| Account import: ' . $line . '                  |');
        $this->output->writeln('--------------------------------------------------');
        $this->output->writeln('');

    }
}
