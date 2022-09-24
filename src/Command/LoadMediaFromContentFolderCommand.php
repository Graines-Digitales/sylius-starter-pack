<?php

namespace App\Command;

use App\Data\Action as DataAction;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadMediaFromContentFolderCommand extends Command
{   
    /**
     * Exemple : ./bin/console app:load-media-from-content-folder
     */
    protected static $defaultName = 'app:load-media-from-content-folder';
    protected static $defaultDescription = 'Add a short description for your command';

    private $container;

    private $entityManager;

    private $dataAction;

    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $entityManager
        , DataAction $dataAction
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->dataAction = $dataAction;

        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this

            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->dataAction->importImages();
        $io->success('Les données du dossier content/ ont bien été enregistrées.');
   
        return Command::SUCCESS;
    }
}
