<?php

namespace App\WebContent;


use App\Tools\Content;
use App\Configuration\Project;
use App\Data\Action\PersonAction;
use App\Data\Action\AddressAction;
use App\Data\Action\MessageAction;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sylius\Bundle\ThemeBundle\Filesystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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

    protected $filesystem;
    
    protected $serializer;

    protected $translator;
    
    protected $configurationService;

    protected $messageAction;
    
    protected $personAction;
    
    protected $validator;

    public function __construct(
          ContainerInterface $container
        , Security $security
        , EntityManagerInterface $manager
        , SluggerInterface $slugger
        , Content $contentTools
        , FilesystemInterface $filesystem
        , SerializerInterface $serializer
        , Project $configurationService
        , TranslatorInterface $translator
        , MessageAction $messageAction
        , PersonAction $personAction
        , AddressAction $addressAction
        , ValidatorInterface $validator
    ){
        $this->container = $container;
        $this->manager = $manager;
        $this->user = $security->getUser();
        // $this->contentTools = $this->container->get('app.tools.content');
        $this->contentTools = $contentTools;
        $this->slugger = $slugger;
        $this->messageAction = $messageAction;
        $this->personAction = $personAction;
        $this->addressAction = $addressAction;
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->configurationService = $configurationService;
        $this->finder = new Finder();
    }

    protected function moreData($entity)
    {
        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }
        if (empty($entity->getTextResume())) {
            $resume  = $this->contentTools->shapeSpace_truncate_string_at_word(
                $entity->getText(), 350, ' ', ''
            );
            $entity->setTextResume(trim($resume));
        }
    }
}
