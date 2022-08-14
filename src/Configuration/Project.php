<?php

namespace App\Configuration;

use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Project
{
    /**
     * @var ContainerInterface
     */
    private $container;

    private $manager;

    private $configurationProject;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $manager
    ) {
        $this->container = $container;
        $this->manager = $manager;
        $this->configurationProject = $this->container->getParameter('configuration_project');
    }

    public function getOrganization()
    {
        return $this->manager->getRepository(Organization::class)
            ->findOneBy(['slug' => $this->configurationProject['slug']]);
    }

    public function getMainEntityOfPageForChoiceType()
    {
        $config = [];
        foreach ($this->configurationProject['search_action']['mainEntityOfPage'] as $key => $criteria) {
            foreach ($criteria as $type => $value) {
                $config[$value['name']] = $type . '_' . $key;
            }
        }

        return $config;
    }

    public function getCategoryTypeSlugs($slug = null)
    {
        $config = [];
        foreach ($this->configurationProject['categories'] as $key => $value) {
            if (isset($value['type']) && true == $value['type']) {
                $config[$value['slug']] = $value['slug'];
            }
        }
        return $config;
    }

    public function getLegalNoticeSlug()
    {
        return $this->configurationProject['web_pages']['legal_notice']['slug'];
    }
}
