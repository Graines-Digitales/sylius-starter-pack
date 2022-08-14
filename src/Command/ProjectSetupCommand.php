<?php

namespace App\Command;

use App\Entity\WebPage;
use App\Configuration\Project;
use App\Data\Action;
use App\Entity\Address;
use App\Entity\CmsComponent;
use App\Entity\CmsTemplate;
use App\Entity\Organization;
use App\Entity\WebPageTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProjectSetupCommand extends Command
{
    private $container;

    private $manager;

    private $configurationService;

    private $dataService;

    protected static $defaultName = 'app:project-setup';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $manager
        , Project $configurationService
        , Action $dataService
    ){
        $this->container = $container;
        $this->manager = $manager;
        $this->configurationService = $configurationService;
        $this->dataService = $dataService;

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
        $configurationProject = $this->container->getParameter('configuration_project');

        if(isset($configurationProject['categories']) 
            && !empty($configurationProject['categories'])
        ) {
            $categories = $configurationProject['categories'];
            foreach ($categories as $data) {
                $category = $this->dataService->createCategoryDemand($data);
                $this->manager->persist($category);
            }
            $this->manager->flush();
        }

        if(isset($configurationProject['web_pages']) 
            && !empty($configurationProject['web_pages'])
        ) {
            $webPages = $configurationProject['web_pages'];
            foreach ($webPages as $data) {
                $webPage = $this->dataService->createWebPageDemand($data);
                $this->manager->persist($webPage);
            }
        }
        $this->manager->flush();

        if(isset($configurationProject['articles']) 
            && !empty($configurationProject['articles'])
        ) {
            $articles = $configurationProject['articles'];
            foreach ($articles as $data) {
                $article = $this->dataService->createArticleDemand($data);
                $this->manager->persist($article);
            }
        }
        $this->manager->flush();


        /**
         * Ci dessous à refacto avec le data service
         * 
         * createComponentDemand
         * createOrganizationDemand
         */

        foreach ($configurationProject['components'] as $component) {
            $componentExists = $this->manager->getRepository(CmsComponent::class)->findOneBy(['code' => $component['code']]);
            if (null == $componentExists) {
                $newComponent = new CmsComponent;
                $newComponent->setIsEnabled(true)
                    ->setName($component['name'])
                    ->setCode($component['code']);
                foreach ($component['template'] as $template) {
                    $newTemplate = new CmsTemplate;
                    $newTemplate->setIsEnabled(true)
                        ->setName($template['name'])
                        ->setCode($template['code']);
                    $this->manager->persist($newTemplate);
                    $newComponent->addTemplate($newTemplate);
                }
                $this->manager->persist($newComponent);
                $feedBack = '"' . $newComponent->getName() . '" created';
                $output->writeln($feedBack);
            }
        }
        $this->manager->flush();

        foreach ($configurationProject['organization'] as $organization) {
            $organizationExists = $this->manager->getRepository(Organization::class)->findOneBy(['code' => $organization['code']]);
            if (null == $organizationExists) {
                $newOrganization = new Organization;
                $newOrganization->setCode($organization['code']);
                $newOrganization->setName($organization['name']);
                $newOrganization->setPhone($organization['phone']);
                $newOrganization->setEmail($organization['email']);
                $newOrganization->setUrl($organization['url']);
                foreach ($organization['address'] as $address) {
                    $newAddress = new Address;
                    $newAddress->setName($address['name']);
                    $newAddress->setAddress($address['address']);
                    $newAddress->setPostCode($address['postcode']);
                    $newAddress->setCity($address['city']);
                    $newAddress->setCountry($address['country']);
                    $this->manager->persist($newAddress);
                    $newOrganization->addAddress($newAddress);
                }
                $this->manager->persist($newOrganization);
                $feedBack = '"' . $newOrganization->getName() . '" created';
                $output->writeln($feedBack);
            }
        }
        $this->manager->flush();
        $io->success('Project set up');

        return Command::SUCCESS;
    }
}
