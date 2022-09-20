<?php

namespace App\Command;

use App\Data\Action as DataAction;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ProjectSetupCommand extends Command
{
    private $container;

    private $dataAction;

    protected static $defaultName = 'app:project-setup';

    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(
        ContainerInterface $container
        , DataAction $dataAction
    ){
        $this->container = $container;
        $this->dataAction = $dataAction;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $contentPath = $kernelProjectDir . '/content';
        $configurationProject = $this->container->getParameter('configuration_project');
        $folders = $configurationProject['folders'];

        

        $finder = new Finder();
        
        $finder->files()->in($contentPath);
        if ($finder->hasResults()) {
            $question = new ConfirmationQuestion(
                'Voulez-vous créer les données du dossier content/ ? (Y|n)',
                true
            );
            if ($helper->ask($input, $output, $question)) {
                $this->dataAction->run($contentPath, $folders);

                $io->success('Les données du dossier content/ ont bien été enregistrées.');
            }
        }
        

        if($result = $this->dataAction->getMainOrganization($contentPath)) {
            
            $question = new ConfirmationQuestion(
                'Les données de votre organisation sont elles correctes ? (Y|n)',
                true
            );
            $data = $result;
            unset($result['addresses']);

            $table = new Table($output);
            $table
                ->setHeaders(array_keys($result))
                ->setRows([$result])
            ;
            $table->render();
            if ($helper->ask($input, $output, $question)) {
                $this->importService->createMainOrganization($data);
                $io->success('Les données de votre organisation ont bien été enregistrées.');

                return Command::SUCCESS;
            }
        }

        $io->error('Le processus à été arrété : Les données de votre organisation n\'ont pas été trouvées');

        return Command::SUCCESS;
    }
}
