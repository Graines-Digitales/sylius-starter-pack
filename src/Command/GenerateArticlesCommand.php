<?php

namespace App\Command;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateArticlesCommand extends Command
{
    protected static $defaultName = 'app:generate-articles';
    protected static $defaultDescription = 'Add a short description for your command';

    private $container;

    private $manager;
    
    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $manager
    ){
        parent::__construct();

        $this->container = $container;
        $this->manager = $manager;
        $this->faker = \Faker\Factory::create();
        


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

        if ($input->getOption('option1')) {
            // ...
        }
        $articles = $this->manager->getRepository(Article::class)->findAll();
        $i = 0;
        foreach($articles as $article) 
        {

            $i++;

            $title = $i . ' - ' . $this->faker->sentence(3, true);
            $article->getTranslation()->setAlternativeHeadline('');
            $article->getTranslation()->setHeadline($title);
            $article->getTranslation()->setArticleResume('');
            $article->getTranslation()->setArticleBody($this->faker->text);
            $this->manager->flush();
        }

        // $query = "DELETE FROM comcliengalarov3.shopping_product_feature_translation";
        // $statement = $this->entityManager->getConnection()->prepare($query);
        // $statement->executeStatement();

        // die;
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
