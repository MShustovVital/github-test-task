<?php

namespace App\Services\Github\Commands;

use App\Services\Github\Contracts\GithubService;
use App\Services\Github\Exceptions\InvalidResponseException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-repository',
    description: 'Command for create repository',
)]
class CreateRepositoryCommand extends Command
{
    public function __construct(private readonly GithubService $githubService, private readonly LoggerInterface $logger)
    {

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of repository');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        try {
            $this->githubService->createRepository($name);
        }
        catch (InvalidResponseException $e)
        {
            $this->logger->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
