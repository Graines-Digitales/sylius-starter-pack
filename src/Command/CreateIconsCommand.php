<?php

namespace App\Command;

use App\Tools\Media;
use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateIconsCommand extends Command
{
    protected static $defaultName = 'app:create-icons';
    protected static $defaultDescription = 'Add a short description for your command';

    private $container;
    
    private $toolsMediaService;

    private $manager;

    private $slugger;
    
    public function __construct(
        ContainerInterface $container
        , Media $toolsMediaService
        , EntityManagerInterface $manager
        , SluggerInterface $slugger
    ){
        parent::__construct();

        $this->container = $container;
        $this->toolsMediaService = $toolsMediaService;
        $this->manager = $manager;
        $this->slugger = $slugger;
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
      
        $icons = $this->toolsMediaService->getIcons();
        
        foreach($icons as $icon) {
            $basename = pathinfo($icon, PATHINFO_FILENAME);
            $basename = u($basename)->replace('_', ' ')->title()->toString();
            $slug = $this->slugger->slug($icon);
            $media = $this->manager->getRepository(MediaObject::class)->findOneBySlug($slug);
            if(null === $media) {
                $media = new MediaObject();
                $media->setName($basename);
                $media->setFilename($icon);
                // $media->setFilename($icon);
                $media->setEncodingFormat('image/svg+xml');
                $this->manager->persist($media);
            }
        }
        $this->manager->flush();
       

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
