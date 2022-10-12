<?php

namespace App\Command;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;


class CreateContentDataCommand extends Command
{
    private $container;

    protected static $defaultName = 'app:create-content-data';

    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

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
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $path = $kernelProjectDir . '/data';
        $contentPath = $kernelProjectDir . '/content';
        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                $data = json_decode(
                    file_get_contents($absoluteFilePath)
                    , true
                );

                if('organizations' !== $filename) {
                    if (!$filesystem->exists($contentPath . DIRECTORY_SEPARATOR . 'fr' . DIRECTORY_SEPARATOR . $filename)) {
                        $filesystem->mkdir($contentPath . DIRECTORY_SEPARATOR . 'fr' . DIRECTORY_SEPARATOR . $filename);
                    }
                    foreach($data['hydra:member'] as $item) {
                        $filepath = $contentPath . DIRECTORY_SEPARATOR . 'fr' . DIRECTORY_SEPARATOR . $filename;
                        $filepath.= DIRECTORY_SEPARATOR . $item['slug'] . '.json';
                        $file = json_encode(
                            $item
                            , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                        );
                        $filesystem->dumpFile($filepath, $file);
                        dump($filepath);
                    }
                } else {
                    if (!$filesystem->exists($contentPath . DIRECTORY_SEPARATOR . $filename)) {
                        $filesystem->mkdir($contentPath . DIRECTORY_SEPARATOR . $filename);
                    }
                    foreach($data['hydra:member'] as $item) {

                        $slug = ('villa-gonatouki' === $item['slug'])? 'main': $item['slug'];
                        $filepath = $contentPath . DIRECTORY_SEPARATOR . $filename;
                        $filepath.= DIRECTORY_SEPARATOR . $slug . '.json';
                        $file = json_encode(
                            $item
                            , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                        );
                        $filesystem->dumpFile($filepath, $file);
                        dump($filepath);
                    }
                }
            }  
        }
        $io->success('Done!');

        return Command::SUCCESS;
    }
}
