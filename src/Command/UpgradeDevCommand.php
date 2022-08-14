<?php

namespace App\Command;

use App\Entity\User\AdminUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpgradeDevCommand extends Command
{
    private $entityManager;

    protected static $defaultName = 'app:upgrade-dev';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $adminUser = $this->entityManager->getRepository(AdminUser::class)->findOneBy(['email' => $email]);

        if(null === $adminUser){
            $io->error('User not found');

            return Command::FAILURE;
        }

        $roleDev = 'ROLE_DEV';

        $isDev = $adminUser->hasRole($roleDev);

        if ($isDev) {
            $adminUser->removeRole($roleDev);
            $io->success('Role dev removed');
        } else {
            $adminUser->addRole($roleDev);
            $io->success('Role dev added');
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
