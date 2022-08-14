<?php

namespace App\WebContent;

use App\Data\Action;
use App\Tools\Content;
use App\Configuration\Project;
use App\WebContent\StructuredData;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sylius\Bundle\ThemeBundle\Filesystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractWebContent
{
    /**
    * @var ContainerInterface
    */
    protected $container;
    
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var User
     */
    protected $user;

    protected $contentTools;

    protected $slugger;

    protected $action;

    protected $filesystem;
    
    protected $serializer;

    protected $translator;
    
    protected $configurationService;

    public function __construct(
          ContainerInterface $container
        , Security $security
        , EntityManagerInterface $manager
        , SluggerInterface $slugger
        , Action $dataAction
        , Content $contentTools
        , FilesystemInterface $filesystem
        , SerializerInterface $serializer
        , Project $configurationService
        , TranslatorInterface $translator

    )
    {
        $this->container = $container;
        $this->manager = $manager;
        $this->user = $security->getUser();
        // $this->contentTools = $this->container->get('app.tools.content');
        $this->contentTools = $contentTools;
        $this->slugger = $slugger;
        $this->dataAction = $dataAction;
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
        $this->translator = $translator;
        $this->configurationService = $configurationService;
        $this->finder = new Finder();
    }
}
