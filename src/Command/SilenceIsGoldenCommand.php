<?php

namespace App\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SilenceIsGoldenCommand extends Command
{
    protected static $defaultName = 'app:silence-is-golden';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->addArgument('folder', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('directory_path', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $folder = $input->getArgument('folder');

        if ($folder) {
            
        }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        // if ($this->lock()) {

            $start = date('Y-m-d\ H:i:s.u');
            $io->info('start to => ' . $start);

            // $frontendPath = $this->container->getParameter('assets.path');
            // $silenceFolder = $this->container->get('app.nuxtjs.silence_folder');

            // $output->writeln($frontendPath);die;
            // $silenceFolder->generate($frontendPath . DIRECTORY_SEPARATOR . 'uploads');
            // $silenceFolder->generate($frontendPath . DIRECTORY_SEPARATOR . 'media');
            // $silenceFolder->generate($frontendPath . DIRECTORY_SEPARATOR . 'pdf');
            // $silenceFolder->generate($frontendPath . DIRECTORY_SEPARATOR . 'js');
            // $silenceFolder->generate($frontendPath . DIRECTORY_SEPARATOR . 'images');
            $path = '';
dump('define your path');
die;
            $this->generate($path);
            $end = date('Y-m-d\ H:i:s.u');
            $io->info('start to => ' . $start);
            $io->info('end to => ' . $end);
        // }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

  
    public function generate($folderPath) 
    {
        $filesystem = new Filesystem();
        $content = "<?php" . PHP_EOL . "// silence is golden";

        $finder = new Finder();
        $finder->directories()->in($folderPath);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filename =  $absoluteFilePath . DIRECTORY_SEPARATOR . 'index.php';
                $filesystem->dumpFile($filename, $content);
                dump($filename);
            }
        }

        $filename =  $folderPath . DIRECTORY_SEPARATOR . 'index.php';
        $filesystem->dumpFile($filename, $content);
        dump($filename);
    }
}
