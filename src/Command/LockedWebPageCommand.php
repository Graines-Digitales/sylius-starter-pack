<?php

namespace App\Command;

use App\Entity\WebPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LockedWebPageCommand extends Command
{
    private $entityManager;

    protected static $defaultName = 'app:locked-web-page';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
        ->addArgument('slug', InputArgument::REQUIRED, 'The slug.')
        // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $slug = $input->getArgument('slug');

        $repository = $this->entityManager->getRepository(WebPage::class);
        $entity = $repository->findOneBySlug($slug);
        if(null === $entity){
            $io->error('WebPage not found');

            return Command::FAILURE;
        }
        
        $isLocked = $entity->getIsLocked();
        $isLocked = !$isLocked;
        $entity->setIsLocked($isLocked);
        $this->entityManager->flush();
        if($isLocked) {
            $io->success('Your WebPage has been locked');
        } else {
            $io->success('Your WebPage has been unlocked');
        }

        return Command::SUCCESS;
    }
}
