<?php

namespace App\Data;

<<<<<<< HEAD
use App\Entity\Tag;
use App\Entity\Care;
use App\Entity\Slide;
use App\Entity\Person;
use App\Entity\Address;
use App\Entity\AggregateOffer;
use App\Entity\AmenityFeature;
use App\Entity\AmenityFeatureTranslation;
use App\Entity\Article;
use App\Entity\Message;
use App\Entity\WebPage;
=======
>>>>>>> starter-pack
use App\Entity\Category;
use App\Entity\IconMediaObject;
use App\Entity\ImageMediaObject;
<<<<<<< HEAD
use App\Entity\Organization;
use App\Entity\PropertyValue;
use App\Entity\SlideMediaObject;
use App\Entity\ArticleTranslation;
use App\Entity\WebPageTranslation;
use App\Entity\CategoryTranslation;
use App\Entity\HotelTypicalDayElement;
=======
use Mni\FrontYAML\Parser;
use Symfony\Component\Finder\Finder;
>>>>>>> starter-pack
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use App\Tools\Media;


class Action
{
<<<<<<< HEAD
    protected $entityManager;

    protected $slugger;

    public function __construct(
          EntityManagerInterface $entityManager
        , SluggerInterface $slugger
    )
    {
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    public function createImageMediaObjectDemand($data = [], $locale = 'fr_FR')
    {
        $repository = $this->entityManager->getRepository(ImageMediaObject::class);

        return $repository->create($data, $locale);
    }

    public function createHotelTypicalDayElementDemand($data = [], $locale = 'fr_FR')
    {
        $repository = $this->entityManager->getRepository(HotelTypicalDayElement::class);

        return $repository->create($data, $locale);
    }
    
    public function createAggregateOfferDemand($data = [], $locale = 'fr_FR')
    {
        $repository = $this->entityManager->getRepository(AggregateOffer::class);

        return $repository->create($data, $locale);
    }

    public function createAmenityFeatureDemand($data = [], $locale = 'fr_FR')
    {
        $repository = $this->entityManager->getRepository(AmenityFeature::class);

        return $repository->create($data, $locale);
    }

    public function createCategoryDemand($data = [], $locale = 'fr_FR')
    {
        $category = new Category();
        $category->setCurrentLocale($locale);
        $categoryTranslation = new CategoryTranslation();
        $category->addTranslation($categoryTranslation); 
        $category = $this->hydrateCategoryDemand($data, $category, $locale);
=======
    private $container;
>>>>>>> starter-pack

    private $entityManager;

    private $webPageAction;

    private $articleAction;

    private $componentAction;

    private $tripAction;

    private $organizationAction;

    private $mediaService;

<<<<<<< HEAD
    public function hydrateCategoryDemand($data, $category, $locale) 
    {
        $category->getTranslation()->setLocale($locale);
        if(isset($data['name'])) {
            $category->getTranslation()->setName($data['name']);
        }
        if(isset($data['type']) && true == $data['type']) {
            $category->setType($data['slug']);
        }
        $category->getTranslation()->setTranslatable($category);
      
        return $category;
    }

    public function hydrateWebPageDemand($data, $webPage, $locale)
    {   
        $webPage->getTranslation()->setLocale($locale);
        $webPage->getTranslation()->setTranslatable($webPage);

        if (isset($data['isLocked'])) {
            $webPage->setIsLocked($data['isLocked']);
        }

        if (isset($data['metaTitle'])) {
            $webPage->getTranslation()->setMetaTitle($data['metaTitle']);
        }

        if (isset($data['metaDescription'])) {
            $webPage->getTranslation()->setMetaDescription($data['metaDescription']);
        }

        if (isset($data['headline'])) {
            $webPage->getTranslation()->setHeadline($data['headline']);
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $webPage->setPrimaryImage($data['primaryImage']);
        }

        if(isset($data['alternativeHeadline'])){
            $webPage->getTranslation()->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['text'])){
            $webPage->getTranslation()->setText($data['text']);
        }

        if (isset($data['pushForward'])) {
            $webPage->getTranslation()->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $webPage->getTranslation()->setTextResume($data['textResume']);
        }
        
        $components = '{}';
        if (isset($data['components'])) {
            $components = $data['component'];
        }
        $webPage->setComponents($components, 'fr_FR');

        return $webPage;
    }

    public function hydrateArticleDemand($data, $article, $locale)
    {
        $article->getTranslation()->setLocale($locale);
        $article->getTranslation()->setTranslatable($article);

        if(isset($data['headline'])){
            $article->getTranslation()->setHeadline($data['headline']);
        }

        if(isset($data['category'])){
            $article->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $article->addCategory($category);
            }
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $article->setPrimaryImage($data['primaryImage']);
        }

        if(isset($data['secondaryImage'])){
            $article->setSecondaryImage($data['secondaryImage']);
        }

        if(isset($data['alternativeHeadline'])){
            $article->getTranslation()->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['articleBody'])){
            $article->getTranslation()->setArticleBody($data['articleBody']);
        }

        if (isset($data['pushForward'])) {
            $article->getTranslation()->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $article->getTranslation()->setTextResume($data['textResume']);
        }

        // if (isset($data['components'])) {
        //     foreach ($data['components'] as $value) {
        //         $component = $this->createComponentDemand($value);
        //         $this->entityManager->persist($component);
        //         $this->entityManager->flush();
        //         $article->addComponent($component);
        //     }
        // }

        return $article;
    }

    public function hydratePersonDemand($data = [], $person)
    {
        $person->setLastname($data['lastname']);
        $person->setFirstname($data['firstname']);
        if(isset($data['email'])) {
            $person->setEmail($data['email']);
        }
        if(isset($data['phone'])) {
            $person->setPhone($data['phone']);
        }
        if(isset($data['entreprise'])) {
            $person->setOrganization($data['entreprise']);
        }
        if(isset($data['collaborateur'])) {
            $person->setNumberOfEmployees($data['collaborateur']);
        }
        if(isset($data['site_web'])) {
            $person->setUrl($data['site_web']);
        }
        if(isset($data['optin'])) {
            $person->setOptin($data['optin']);
        }
        if(isset($data['gender'])) {
            $person->setGender($data['gender']);
        }

        return $person;
    }

    public function hydrateAddressDemand($data = [], $address)
    {
        $address->setAddress($data['streetAddress']);
        $address->setCity($data['addressLocality']);
        $address->setPostcode($data['postalCode']);

        if(isset($data['addressCountry'])) {
            $address->setCountry($data['addressCountry']);
        }

        if(isset($data['phone'])) {
            $address->setPhone($data['phone']);
        }

        return $address;
=======
    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $entityManager
        , WebPageAction $webPageAction
        , ArticleAction $articleAction
        , CategoryAction $categoryAction
        , ComponentAction $componentAction
        , TripAction $tripAction
        , OrganizationAction $organizationAction
        , Media $mediaService
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->webPageAction = $webPageAction;
        $this->articleAction = $articleAction;
        $this->categoryAction = $categoryAction;
        $this->componentAction = $componentAction;
        $this->tripAction = $tripAction;
        $this->organizationAction = $organizationAction;
        $this->mediaService = $mediaService;
>>>>>>> starter-pack
    }

    public function importImages()
    {
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $configurationProject = $this->container->getParameter('configuration_project');
        $imageMimeTypes = $configurationProject['media_encoding_formats']['image'];

        $imagesPath = $kernelProjectDir . '/content/images';
        $svgsPath = $kernelProjectDir . '/content/svgs';

        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->files()->in($imagesPath);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $dirname = pathinfo(pathinfo($file->getRealPath(), PATHINFO_DIRNAME), PATHINFO_BASENAME);
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                $category = null;
                if('images' !== $dirname) {
                    $data['name'] = $dirname;
                    $category = $this->entityManager->getRepository(Category::class)
                        ->findOneBySlug($data['name']);
                    if(null === $category) {
                        
                        $category = $this->categoryAction->create($data);
                    }
                }
                $file = new File($absoluteFilePath);
                if (in_array($file->getMimeType(), $imageMimeTypes)) {
                    $filesystem->copy($absoluteFilePath, $kernelProjectDir .  '/public/media/image/' . $filename);
                    $entity = $this->entityManager->getRepository(ImageMediaObject::class)
                    ->findOneBy([ 'filename' => $filename ]);
                    if(null === $entity) {
                        
                        $entity = $this->mediaService->defineEntityMediaFromFile2($file, $category);
                    }
                    $this->entityManager->persist($entity);
                }
            }
            $this->entityManager->flush();
            
<<<<<<< HEAD
            $component->getTranslation($locale)->setComponents(
                json_encode($components)
            );
        }

        return $component;
    }


    public function hydrateOrganizationDemand($data = [], $addresses = [], $organization)
    {
        if(isset($data['category'])) {
            $category = $this->entityManager->getRepository(Category::class)
                ->findOneBySlug($data['category']);
            $organization->setCategory($category);
        }
        if(isset($data['name'])) {
            $organization->setName($data['name']);
        }
        if(isset($data['legal_name'])) {
            $organization->setLegalName($data['legal_name']);
        }
        if(isset($data['phone'])) {
            $organization->setPhone($data['phone']);
        }
        if(isset($data['mobile_phone'])) {
            $organization->setMobilePhone($data['mobile_phone']);
        }
        if(isset($data['url'])) {
            $organization->setUrl($data['url']);
        }
        if(isset($data['email'])) {
            $organization->setEmail($data['email']);
        }
        if(isset($data['founding_date'])) {
            $date = new \DateTime($data['founding_date']);
            $organization->setFoundingDate($data['founding_date']);
        }
        if(isset($data['number_of_employees'])) {
            $organization->setNumberOfEmployees($data['number_of_employees']);
        }
        if(isset($data['number_of_projects'])) {
            $organization->setNumberOfProjects($data['number_of_projects']);
        }

        foreach ($addresses as $address) {
            $address = $this->createAddressDemand($address);
            $organization->addAddress($address);
        }

        if(isset($data['socials'])) {
            foreach ($data['socials'] as $key => $result) {
                $slug = $this->slugger->slug($key)->lower()->toString();
                $socialLink = $this->entityManager->getRepository(Organization::class)
                    ->findOneBy(['slug' => $slug]);
                if(null !== $socialLink) {
                    $organization->addSocialLink($socialLink);
=======
        }

        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->files()->in($svgsPath);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                $file = new File($absoluteFilePath);
                $filesystem->copy($absoluteFilePath, $kernelProjectDir .  '/public/media/icon/' . $filename);
                $entity = $this->entityManager->getRepository(IconMediaObject::class)
                ->findOneBy([ 'filename' => $filename ]);
                if(null === $entity) {
                    
                    $entity = $this->mediaService->defineIconMediaFromFile($file);
>>>>>>> starter-pack
                }
                $this->entityManager->persist($entity);
            }
            $this->entityManager->flush();
        }
    }

    public function run($contentPath, $folders)
    {
        
        $locales = $this->container->get('sylius.repository.locale')->findAll();
     
        foreach($locales as $locale) {
            $localeCode = $locale->getCode();
            $locale = current(explode('_', $localeCode));
            if('fr' === $locale) {
                
                foreach($folders as $folder) {
                    $finder = new Finder();
                    $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $folder;
                    $finder->depth('== 0');
                    $finder->files()->in($path);
                    if ($finder->hasResults()) {
                        foreach ($finder as $file) {
                            $absoluteFilePath = $file->getRealPath();
                            $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                            $data = $this->extractData($absoluteFilePath, $extension);
                            $entity = $this->dataServicesDispatch($data, $folder);
                            $this->entityManager->persist($entity);
                        }
                        $this->entityManager->flush();
                    }
                }
            }
        }
    }

    public function getMainOrganization($contentPath)
    {
        $parser = new Parser();
        $filesystem = new Filesystem();
        $filepath = $contentPath . DIRECTORY_SEPARATOR . 'main_organization.md';
        if($filesystem->exists($filepath)) {
            $result = $parser->parse(file_get_contents($filepath), false);

            return $result->getYaml();
        }

        return false;
    }

    public function createMainOrganization($data) 
    {
        $organization = $this->organizationAction->create($data);
        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    public function extractData($absoluteFilePath, $extension)
    {
<<<<<<< HEAD
        $slug = $this->slugger->slug($value['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(Slide::class)
            ->findOneBy(['slug' => $slug]);
        if (null == $entity) {
            $entity = new Slide();
        }
        $entity->setName($value['name']);
        foreach ($value['items'] as $item) {
            $slideMediaObject = new SlideMediaObject();
            if(isset($item['image']) && !empty($item['image'])){
                $image = $this->entityManager->getRepository(ImageMediaObject::class)
                    ->findOneBy(['slug' => $item['image']]);
                    $slideMediaObject->setMediaObject($image);
            }
            if(isset($item['name'])) {
                $slideMediaObject->setName($item['name']);
            }
            if(isset($item['text'])) {
                $slideMediaObject->setDescription($item['text']);
            }
            if(isset($item['icon'])) {
                $slideMediaObject->setSvg($item['icon']);
            }
            if(isset($item['blockquote'])) {
                $blockquote = new Blockquote();
                $blockquote->setText($item['blockquote']);
                $slideMediaObject->addBlockquote($blockquote);
            }
            if (isset($item['externalLink'])) {
                $externalLink = new PropertyValue();
                $externalLink->setName($item['externalLink']['name']);
                $externalLink->setValue($item['externalLink']['value']);
                $slideMediaObject->setExternalLink($externalLink);
            }

            if (isset($item['internalLink'])) {
                if (isset($item['internalLink']['webPage'])) {
                    $linkedWebPage = $this->entityManager->getRepository(WebPage::class)
                        ->findOneBy(['slug' => $item['internalLink']['webPage']['slug']]);
                    if($linkedWebPage) {
                        $slideMediaObject->setInternalLinkWebPage($linkedWebPage);
                        if(isset($item['internalLink']['webPage']['label'])) {
                            $label = $item['internalLink']['webPage']['label'];
                            $slideMediaObject->setInternalLinkWebPageLabel($label);
                        }
                    }
                }

                if (isset($item['internalLink']['article'])) {
                    $linkedArticle = $this->entityManager->getRepository(Article::class)
                            ->findOneBy(['slug' => $item['internalLink']['article']['slug']]);
                    if($linkedArticle) {
                        $slideMediaObject->setInternalLinkArticle($linkedArticle);
                        if(isset($item['internalLink']['article']['label'])) {
                            $label = $item['internalLink']['article']['label'];
                            $slideMediaObject->setInternalLinkArticleLabel($label);
                        }
                    }
                }
            }


            $entity->addSlideMediaObject($slideMediaObject);
        }

        return $entity;
=======
        $data = [];
        
        switch ($extension) {
            case 'md':
                $parser = new Parser();
                $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                $data = $result->getYaml();
                $data['content'] = $result->getContent();
                
                break;
            case 'json':
                $data = json_decode(
                    file_get_contents($absoluteFilePath)
                    , true
                );
                break;
        }

        return $data;
>>>>>>> starter-pack
    }

    public function dataServicesDispatch($data, $folder)
    {
<<<<<<< HEAD
        $slug = $this->slugger->slug($value['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(Slide::class)
            ->findOneBy(['slug' => $slug]);

        if (null == $entity) {
           dump('probleme mon gros');die;
        }
        /**
         * @TODO : dev non définitif, à revoir ou pas
         */
        $articles = [];
        $webPages = [];
        $products = [];
        foreach($value['items'] as $item){
            if(isset($item['internalLink']['article'])) {
                $slug = $this->slugger->slug($item['internalLink']['article']['slug'])->lower()->toString();
                $articles[$slug] = $item;
            }
            if(isset($item['internalLink']['webPage'])) {
                $slug = $this->slugger->slug($item['internalLink']['webPage']['slug'])->lower()->toString();
                $webPages[$slug] = $item;
            }
            if(isset($item['product'])) {
                $slug = $this->slugger->slug($item['product']['name'])->lower()->toString();
                $products[$slug] = $item;
            }
        }
        foreach ($entity->getSlideMediaObjects() as $slideMediaObject) {
            $slug = $this->slugger->slug($slideMediaObject->getName())->lower()->toString();
            if (isset($webPages[$slug])) {
                $linkedWebPage = $this->entityManager->getRepository(WebPage::class)
                    ->findOneBy(['slug' => $slug]);
                if($linkedWebPage) {
                    $slideMediaObject->setInternalLinkWebPage($linkedWebPage);
                    if(isset($webPages[$slug]['internalLink']['webPage']['label'])) {
                        $label = $webPages[$slug]['internalLink']['webPage']['label'];
                        $slideMediaObject->setInternalLinkWebPageLabel($label);
                    }
                }
            }
            if (isset($articles[$slug])) {
                $linkedArticle = $this->entityManager->getRepository(Article::class)
                    ->findOneBy(['slug' => $slug]);

                if($linkedArticle) {
                    $slideMediaObject->setInternalLinkArticle($linkedArticle);
                    if(isset($articles[$slug]['internalLink']['article']['label'])) {
                        $label = $articles[$slug]['internalLink']['article']['label'];
                        $slideMediaObject->setInternalLinkArticleLabel($label);
                    }
                }
            }
            if (isset($products[$slug])) {
                $product = $this->entityManager->getRepository(Care::class)
                    ->findOneBy(['slug' => $slug]);
                $slideMediaObject->setCare($product);
            }
        }

        return $entity;
    }

    public function _createComponentDemand($value = [], $index = 0)
    {
        $slug = $this->slugger->slug($value['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(Component::class)
            ->findOneBy(['slug' => $slug]);
        if (null == $entity) {
            $entity = new Component();
        }

        $entity->setName($value['name']);
        if(isset($value['category'])){
            $slug = $this->slugger->slug($value['category']['name'])->lower()->toString();
            $category = $this->entityManager->getRepository(Tag::class)
                ->findOneBy(['slug' => $slug]);
            if(null == $category) {
                $category = new Category();
                $category->setName($value['category']['name']);
                if(isset($article['category']['headline'])){
                    $category->setHeadline($article['category']['headline']);
                }
                if(isset($article['category']['alternativeHeadline'])){
                    $category->setAlternativeHeadline($article['category']['alternativeHeadline']);
                }
                if(isset($value['category']['primaryImage']) && !empty($value['category']['primaryImage'])){
                    $primaryImage = $this->entityManager->getRepository(ImageMediaObject::class)
                        ->findOneBy(['slug' => $value['category']['primaryImage']]);
                    $category->setPrimaryImage($primaryImage);
                }
                if(isset($value['category']['description'])){
                    $category->setDescription($value['category']['description']);
                }
                $this->entityManager->persist($category);
                $this->entityManager->flush();
            }
            $entity->setCategory($category);
        }
        if(isset($value['tags'])){
            foreach ($value['tags'] as $key => $category) {
                $slug = $this->slugger->slug($category['name'])->lower()->toString();
                $tag = $this->entityManager->getRepository(Tag::class)
                    ->findOneBy(['slug' => $slug]);
                if(null == $tag) {
                    $tag = new Category();
                    $tag->setName($category['name']);
                    if(isset($category['primaryImage']) && !empty($article['category']['primaryImage'])){
                        $primaryImage = $this->entityManager->getRepository(ImageMediaObject::class)
                            ->findOneBy(['slug' => $category['primaryImage']]);
                        $tag->setPrimaryImage($primaryImage);
                    }
                    if(isset($category['description'])){
                        $tag->setDescription($category['description']);
                    }
                    if(isset($category['headline'])){
                        $tag->setHeadline($category['headline']);
                    }
                    if(isset($category['alternativeHeadline'])){
                        $tag->setAlternativeHeadline($category['alternativeHeadline']);
                    }
                    $this->entityManager->persist($category);
                    $this->entityManager->flush();
                }
            }
            $entity->addTag($tag);
        }
        if (isset($value['headline'])) {
            $entity->setHeadline($value['headline']);
        }
        if (isset($value['alternativeHeadline'])) {
            $entity->setAlternativeHeadline($value['alternativeHeadline']);
        }
        if (isset($value['pushForward'])) {
            $entity->setPushForward($value['pushForward']);
        }
        if (isset($value['text'])) {
            $entity->setText($value['text']);
        }
        if (isset($value['textResume'])) {
            $entity->setTextResume($value['textResume']);
        }
        if (isset($value['description'])) {
            $entity->setDescription($value['description']);
        }
        if (isset($value['icon'])) {
            $entity->setIcon($value['icon']);
        }
        if (isset($value['slide'])) {
            $slide = $this->entityManager->getRepository(Slide::class)
                ->findOneBy(['slug' => $value['slide']]);
            $entity->setSlide($slide);
        }
        if (isset($value['list'])) {
            $entity->setList($value['list']);
        }
        if (isset($value['blockquote'])) {
            $blockquote = new Blockquote();
            $blockquote->setText($value['blockquote']['text']);
            $entity->setBlockquote($blockquote);
        }

        if (isset($value['externalLink'])) {
            $externalLink = new PropertyValue();
            $externalLink->setName($value['externalLink']['name']);
            $externalLink->setValue($value['externalLink']['value']);
            $entity->setExternalLink($externalLink);
        }

        if (isset($value['internalLink'])) {
            if (isset($value['internalLink']['webPage'])) {
                $slug = $this->slugger->slug($value['internalLink']['webPage']['slug'])->lower()->toString();
                $linkedWebPage = $this->entityManager->getRepository(WebPage::class)
                    ->findOneBy(['slug' => $slug]);
=======
        switch ($folder) {
            case 'categories':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }
               
                $category = $this->entityManager->getRepository(Category::class)->findOneBySlug($data['slug']);
                if(null !== $category) {
                    
                    return $category;
                }

                return $this->categoryAction->create($data);
              
                break;
            case 'web_pages':
                if(isset($data['content'])) {
                    $data['text'] = $data['content'];
                    unset($data['content']);
                }
                
                return $this->webPageAction->create($data);
                
                break;
            case 'articles':
                if(isset($data['content'])) {
                    $data['articleBody'] = $data['content'];
                    unset($data['content']);
                }
>>>>>>> starter-pack

                return $this->articleAction->create($data);
                
                break;
            case 'components':
              
                return $this->componentAction->create($data);
                
                break;
            case 'social_links':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }
<<<<<<< HEAD
            }
            if (isset($value['internalLink']['article'])) {
                $linkedArticle = $this->entityManager->getRepository(Article::class)
                        ->findOneBy(['slug' => $this->slugger->slug($value['internalLink']['article']['slug'])->lower()->toString()]);
                if($linkedArticle) {
                    $entity->setInternalLinkArticle($linkedArticle);
                    if(isset($value['internalLink']['article']['label'])) {
                        $label = $value['internalLink']['article']['label'];
                        $entity->setInternalLinkArticleLabel($label);
                    }
                }
            }
        }

        if(isset($value['media'])){
            $media = $this->entityManager->getRepository(ImageMediaObject::class)
                ->findOneBy(['slug' => $this->slugger->slug($value['media'])->lower()->toString()]);
            $entity->setMedia($media);
        }

        $entity->setSortPosition($index);

        return $entity;
    }

    public function _hydrateComponentDemand($value = [], $index = 0)
    {
        $slug = $this->slugger->slug($value['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(Component::class)
            ->findOneBy(['slug' => $slug]);
        if (null == $entity) {
            $entity = new Component();
        }

        if (isset($value['internalLink'])) {
            if (isset($value['internalLink']['webPage'])) {
                $slug = $this->slugger->slug($value['internalLink']['webPage']['slug'])->lower()->toString();
                $linkedWebPage = $this->entityManager->getRepository(WebPage::class)
                    ->findOneBy(['slug' => $slug]);
                if($linkedWebPage) {

                    $entity->setInternalLinkWebPage($linkedWebPage);
                    if(isset($value['internalLink']['webPage']['label'])) {
                        $label = $value['internalLink']['webPage']['label'];
                        $entity->setInternalLinkWebPageLabel($label);
                    }
                }
            }
            if (isset($value['internalLink']['article'])) {
                $linkedArticle = $this->entityManager->getRepository(Article::class)
                        ->findOneBy(['slug' => $this->slugger->slug($value['internalLink']['article']['slug'])->lower()->toString()]);
                if($linkedArticle) {
                    $entity->setInternalLinkArticle($linkedArticle);
                    if(isset($value['internalLink']['article']['label'])) {
                        $label = $value['internalLink']['article']['label'];
                        $entity->setInternalLinkArticleLabel($label);
                    }
=======

                return $this->organizationAction->create($data);
                
                break;
            case 'travels':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
>>>>>>> starter-pack
                }

                return $this->tripAction->create($data);
                
                break;
            default:
                # code...
                break;
        }
    }
}
