<?php

namespace App\Command;

use App\Tools\ArrayToCsv;
use App\Entity\LocalBusiness;
use App\Configuration\Project;

use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\u;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ReworkProductCsvFileCommand extends Command
{
    protected static $defaultName = 'app:rework-product-csv-file';
    protected static $defaultDescription = 'Add a short description for your command';

    private $container;

    private $configurationService;
    
    private $slugger;

    private $manager;
    
    
    public function __construct(
        ContainerInterface $container,
        Project $configurationService,
        EntityManagerInterface $manager
    )
    {
        parent::__construct();

        $this->container = $container;
        $this->manager = $manager;
        $this->configurationService = $configurationService;
        $this->slugger = new AsciiSlugger();
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

        // dump($this->configurationService->getUrlPreprod());
        // dump($this->container->getParameter('kernel.project_dir'));

        $filepath = $this->container->getParameter('kernel.project_dir') . '/schema/sylius_csv_import/products.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($filepath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $headerProductFile = $rows[0];

        $filepath = $this->container->getParameter('kernel.project_dir') . '/schema/sylius_csv_import/products_attr.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($filepath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $headerProductAttrFile = $rows[0];

        $filepath = $this->container->getParameter('kernel.project_dir') . '/schema/sylius_csv_import/taxonomies.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($filepath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $headerTaxonomieFile = $rows[0];

        $filepath = $this->container->getParameter('kernel.project_dir') . '/products_file.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $spreadsheet = $reader->load($filepath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        /** Local Business */
        $repository = $this->manager->getRepository(LocalBusiness::class);
        $results = $repository->findAll();
        $localBusinesses = [];
        foreach($results as $result) {
            $localBusinesses[] = $this->slugger->slug($result->getName())->lower()->toString();
        }
        $headers = [];
        $taxonsRoot = null;
        $products = [];
        $taxonomies = [];
        $taxonomies['root'] = [
            "Code" => "root",
            "Parent" => null,
            "Locale" => 'fr_FR',
            "Name" => "Root",
            "Slug" => "root",
            "Description" => null
        ];
        
        foreach($rows as $key => $row) {
            if(empty($headers)) {
                $headers = $row;
                $taxonsRoot = $this->getTaxonsRoot($headers);
                continue;
            }
            $row = array_combine($headers, $row);
            $description = $row['Description'];
            $meta = trim(trim(strstr($description, ':'), ':'));
            $row['Name'] = $row['Name'] . ' ' . $meta;
            $row['Short_description'] = $row['destockage'];
            $row['Meta_description'] = $row['destockage'];
            $row['Taxons'] = trim($row['Taxons']);
            // $taxons = explode(",", $row['Taxons']);
            // $row['Main_taxon'] = $this->slugger->slug($row['Taxons'])->lower()->toString();
            // $row['Main_taxon'] = str_replace(',', ' ', $row['Taxons']);
            $row['Main_taxon'] = $row['Taxons'];
            
            $row['originalPrice'] = trim(trim($row['Price'], '€'));
            $row['originalPrice'] = str_replace('/', '', $row['originalPrice']);
            $row['originalPrice'] = str_replace(',', '.', $row['originalPrice']);
            $row['originalPrice'] = (empty($row['Price']))? 0: $row['originalPrice'];
            $row['originalPrice'] = (float)filter_var($row['originalPrice']
            , FILTER_SANITIZE_NUMBER_FLOAT
            , FILTER_FLAG_ALLOW_FRACTION
            );

            $row['Price'] = trim(trim($row['Discount'], '€'));
            $row['Price'] = str_replace('/', '', $row['Price']);
            $row['Price'] = str_replace(',', '.', $row['Price']);
            $row['Price'] = (empty($row['Price']))? 0: $row['Price'];
            $row['Price'] = (float)filter_var($row['Price']
            , FILTER_SANITIZE_NUMBER_FLOAT
            , FILTER_FLAG_ALLOW_FRACTION
            );
            
            // pour cette info il faut modifier le vendor !!
            // vendor/friendsofsylius/sylius-import-export-plugin/src/Processor/ProductProcessor.php
            $row['originalPrice'] = $row['originalPrice'] * 100;
            $row['Price'] = $row['Price'] * 100;
            
            // dump(floatval(preg_replace('/[^A-Za-z0-9\.\-]/', '', $row['Price'])));
            // dump($row['Price']);
            // dump(gettype($row['Price']));
            // $row['Price'] = is_numeric($row['Price']) ? $row['Price']: null;
            // dump($row['Price']);
            // dump(floatval($row['Price']));
            // dump($row);
// die;
            /** INSERTION DES TAXONS DANS TAXONOMIES A REFACTORISER */
            $taxonsCode = [];
            // foreach($taxons as $taxon) {
                $taxonomies[$this->slugger->slug($row['Main_taxon'])->lower()->toString()] = [
                    "Code" => $this->slugger->slug($row['Main_taxon'])->lower()->toString(),
                    "Parent" => "root",
                    "Locale" => 'fr_FR',
                    "Name" => $row['Main_taxon'],
                    "Slug" => $this->slugger->slug($row['Main_taxon'])->lower()->toString(),
                    "Description" => null
                ];
                array_push($taxonsCode, $this->slugger->slug($row['Main_taxon'])->lower()->toString());
            // }
            // dump($taxonsRoot);die;
            foreach($taxonsRoot as $iter => $columnValue) {
                $parent = $columnValue;
                $taxonomies[$this->slugger->slug($parent)->lower()->toString()] = [
                    "Code" => $this->slugger->slug($parent)->lower()->toString(),
                    "Parent" => "root",
                    "Locale" => 'fr_FR',
                    "Name" => ucfirst($parent),
                    "Slug" => $this->slugger->slug($parent)->lower()->toString(),
                    "Description" => null
                ];
                if(null !== $row[$iter]) {
                    $taxon = $row[$iter];
                    $taxonomies[$this->slugger->slug($taxon)->lower()->toString()] = [
                        "Code" => $this->slugger->slug($taxon)->lower()->toString(),
                        "Parent" => $this->slugger->slug($parent)->lower()->toString(),
                        "Locale" => 'fr_FR',
                        "Name" => $taxon,
                        "Slug" => $this->slugger->slug($taxon)->lower()->toString(),
                        "Description" => null
                    ];
                    array_push($taxonsCode, $this->slugger->slug($taxon)->lower()->toString());
                }
                
            };
            $taxonsCode = array_merge($localBusinesses, $taxonsCode);
            /** INSERTION DES TAXONS END */
            $row['Taxons'] = implode('|', $taxonsCode);
            $row['Main_taxon'] = $this->slugger->slug($row['Main_taxon'])->lower()->toString();
            foreach(array_keys($row) as $item) {
                if(!in_array($item, $headerProductFile)) {
                    // dump($item);
                    unset($row[$item]);
                }
            }
            
            array_push($products, $row);
        };
        array_unshift($products, array_keys($products[0]));

        /** ajout des points de ventes pour les taxonomies */

        $repository = $this->manager->getRepository(LocalBusiness::class);
        $localBusinesses = $repository->findAll();
        $parent = 'point-de-vente';
        $taxonomies[$parent] = [
            "Code" => $parent,
            "Parent" => 'root',
            "Locale" => 'fr_FR',
            "Name" => "Point de vente",
            "Slug" => $parent,
            "Description" => null
        ];
        foreach($localBusinesses as $localBusiness) {
            // dump($localBusiness->getOrganization()->getSlug());die;
            $taxonomies[$this->slugger->slug($localBusiness->getName())->lower()->toString()] = [
                "Code" => $this->slugger->slug($localBusiness->getName())->lower()->toString(),
                "Parent" => $parent,
                "Locale" => 'fr_FR',
                "Name" => $localBusiness->getName(),
                "Slug" => $localBusiness->getOrganization()->getSlug(),
                "Description" => null
            ];
        }

        // dump($taxonomies);die;
        $filepath = $this->container->getParameter('kernel.project_dir') . '/products.csv';
        $csv = new ArrayToCsv(',');
        $file = $csv->convert($products);
        $filesystem = new Filesystem();
        $filesystem->dumpFile($filepath, $file);

        array_unshift($taxonomies, array_keys($taxonomies['root']));
        $filepath = $this->container->getParameter('kernel.project_dir') . '/taxonomies.csv';
        $csv = new ArrayToCsv(',');
        $file = $csv->convert($taxonomies);
        $filesystem = new Filesystem();
        $filesystem->dumpFile($filepath, $file);
        
        die;
        

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function getTaxonsRoot($headers)
    {
        $attrHeaders = [];
        $pattern = "/attribut_/i";
        foreach($headers as $iter => $row) {
            if(preg_match($pattern, $row, $matches, PREG_OFFSET_CAPTURE)) {
                $pattern = "/_/";
                $items = preg_split($pattern, $row);
                $attrHeaders[$row] = $items[1];
                // dump($attrHeaders);die;
            }
           
        };

        return $attrHeaders;
    }
}
