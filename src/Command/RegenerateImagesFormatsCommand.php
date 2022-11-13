<?php

namespace App\Command;

use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class RegenerateImagesFormatsCommand extends Command
{   
    /**
     * Exemple : ./bin/console app:load-media-from-content-folder
     */
    protected static $defaultName = 'app:regenerate-images-formats';
    protected static $defaultDescription = 'Add a short description for your command';

    private $container;

    private $entityManager;

    private $importAction;

    private $imagine;

    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $entityManager
        , FilterService $imagine
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->imagine = $imagine;

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

        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $path = $kernelProjectDir . '/public/media/image';
       
  
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {

                $absoluteFilePath = $file->getRealPath();
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                $io->info($filename);
                
                $io->info($absoluteFilePath);
                $filter_sets = $this->container->getParameter(
                    'liip_imagine.filter_sets'
                );
                foreach($filter_sets as $filterName => $filter) {
                    

                    $pattern='/sylius|monsieurbiz/i';
                    if (!preg_match($pattern, $filterName) ) {
                        $io->info($filterName);
                        $this->imagine->getUrlOfFilteredImage($filename, $filterName);
                    }
                    
                }
            
              
                // exit;
                
            }
        }
        
        $io->success('Les formats d images ont bien été regénérer');
   
        return Command::SUCCESS;
    }
}
