<?php

namespace App\Command;

use App\Data\ImportCommand;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Filesystem\Filesystem;
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

    private $importAction;

    protected static $defaultName = 'app:project-setup';

    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(
        ContainerInterface $container
        , ImportCommand $importAction
    ){
        $this->container = $container;
        $this->importAction = $importAction;

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
        $pathContentDirectory = $kernelProjectDir . '/content';
        $pathImagesDirectory = $kernelProjectDir . '/content/images';

        $configurationProject = $this->container->getParameter('configuration_project');
        $folders = $configurationProject['folders'];

        $filesystem = new Filesystem();
        if ($filesystem->exists($pathImagesDirectory)) {
            $finder = new Finder();
            $finder->files()->in($pathImagesDirectory);
            if ($finder->hasResults()) {
                $question = new ConfirmationQuestion(
                    'Voulez-vous créer les images trouvées dans le dossier content/ ? (Y|n)',
                    true
                );
                if ($helper->ask($input, $output, $question)) {
                    $this->importAction->fromImagesFolder($io);

                    $io->success('Les données du dossier content/ ont bien été enregistrées.');
                }
            }
        }

        if (!$result = $this->importAction->getMainOrganization($pathContentDirectory)) {
            $io->error('Le processus à été arrété : Les données de votre organisation n\'ont pas été trouvées');

            return Command::FAILURE;
        }


        $question = new ConfirmationQuestion(
            'Les données de votre organisation sont elles correctes ? (Y|n)',
            true
        );
        $data = $result;
       
        unset($result['addresses']);
        unset($result['primaryImage']);
        unset($result['secondaryImage']);
        unset($result['category']);
        // dump($result);die;
        $table = new Table($output);
        $table
            ->setHeaders(array_keys($result))
            ->setRows([$result])
        ;
        $table->render();
        if ($helper->ask($input, $output, $question)) {
            $this->importAction->createMainOrganization($data);
            $io->success('Les données de votre organisation ont bien été enregistrées.');
        }
        
        $filesystem = new Filesystem();
        if ($filesystem->exists($pathContentDirectory)) {
            $finder = new Finder();
            $finder->files()->in($pathContentDirectory);
            if ($finder->hasResults()) {
                $question = new ConfirmationQuestion(
                    'Voulez-vous créer les données du dossier content/ ? (Y|n)',
                    true
                );
                if ($helper->ask($input, $output, $question)) {
                    $this->importAction->fromContentFolder($io);

                    $io->success('Les données du dossier content/ ont bien été enregistrées.');
                }
            }
        }
 
        return Command::SUCCESS;
    }
}
