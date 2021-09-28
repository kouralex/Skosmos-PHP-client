<?php

namespace NatLibFi\Finto\PhpClient\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends FintoCommand
{
    protected static $defaultName = 'finto:search';

    protected function configure(): void
    {
        $this->addArgument('query', InputArgument::REQUIRED, 'Query');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = $input->getArgument('query');
        $result = $this->finto->search($query);
        $output->writeln(json_encode($result, JSON_PRETTY_PRINT));
        return Command::SUCCESS;
    }
}
