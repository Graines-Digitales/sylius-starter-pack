<?php

namespace App\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;


class BuildModernWebsiteCommand extends Command
{
    protected static $defaultName = 'app:build-modern-website';
    protected static $defaultDescription = 'Add a short description for your command';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');

        $command = $kernelProjectDir.'/build.sh';
        $process = new Process(
            [$command, $kernelProjectDir]
        );
        // $process->setTimeout(10800); // 3 heures
        try {
            $process->mustRun();
            
            $io->success($process->getOutput());
        } catch (ProcessFailedException $exception) {
            $io->error($exception->getMessage());
        }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
