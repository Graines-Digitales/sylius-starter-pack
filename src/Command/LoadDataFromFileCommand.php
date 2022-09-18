<?php

namespace App\Command;

use App\Data\Action as DataAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDataFromFileCommand extends Command
{   
    /**
     * Exemple : ./bin/console app:load-data-from-file newsletter.md components
     */
    protected static $defaultName = 'app:load-data-from-file';
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
            ->addArgument('filename', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('folder', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('locale', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $folder = $input->getArgument('folder');
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        
        if ($filename) {
            $absoluteFilePath = $kernelProjectDir . DIRECTORY_SEPARATOR;
            $absoluteFilePath.= 'content/fr' .DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename;
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            if(empty($extension)) {
                $io->error('Extension de fichier manquante');

                return Command::FAILURE;
            }
            
            $data = $this->dataAction->extractData($absoluteFilePath, $extension);
            $entity = $this->dataAction->dataServicesDispatch($data, $folder);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
