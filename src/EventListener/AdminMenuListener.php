<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\KernelInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use App\Configuration\Project;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

final class AdminMenuListener
{
    /**
     * @var string
     */
    private $environment;

    private $configurationService;

    private $request;
    
    private $user;

    /**
     * Your Service constructor.
     */
    public function __construct(
        KernelInterface $kernel,
        Project $configurationService,
        RequestStack $requestStack,
        Security $security
    ) {
        $this->environment = $kernel->getEnvironment();
        $this->request = $requestStack->getCurrentRequest();
        $this->configurationService = $configurationService;
        $this->user = $security->getUser();
    }

    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {

        $menu = $event->getMenu();

        $menu->removeChild('sales');
        $menu->removeChild('marketing');
        $menu->removeChild('customers');
        $menu->removeChild('catalog');

        $cmsMenu = $menu
            ->addChild('app_cms')
            ->setLabel('Gestion de contenu');

        $cmsMenu
            ->addChild('app_pages', ['route' => 'app_admin_web_page_index'])
            ->setLabel('Pages')
            ->setLabelAttribute('icon', 'edit outline');

        // $cmsMenu
        //     ->addChild('app_category', ['route' => 'app_admin_category_index'])
        //     ->setLabel('Categories')
        //     ->setLabelAttribute('icon', 'object ungroup outline');

        $cmsMenu
            ->addChild('app_articles', ['route' => 'app_admin_article_index'])
            ->setLabel('Articles')
            ->setLabelAttribute('icon', 'newspaper outline');
        
        if (in_array('ROLE_DEV', $this->user->getRoles())) {
            $componentMenu = $menu
            ->addChild('app_component')
            ->setLabel('Components');

            // $componentMenu
            //     ->addChild('app_cms_component_menu', ['route' => 'app_admin_cms_component_menu_index'])
            //     ->setLabel('Menu')
            //     ->setLabelAttribute('icon', 'list');
        }

        $mediaMenu = $menu
            ->addChild('app_media')
            ->setLabel('Media');

        $mediaMenu
            ->addChild('app_media_image', ['route' => 'app_admin_media_image_index'])
            ->setLabel('Images')
            ->setLabelAttribute('icon', 'images outline');

        $mediaMenu
            ->addChild('app_media_video', ['route' => 'app_admin_media_video_index'])
            ->setLabel('Video')
            ->setLabelAttribute('icon', 'play circle outline');

        $mediaMenu
            ->addChild('app_media_document', ['route' => 'app_admin_media_document_index'])
            ->setLabel('Documents')
            ->setLabelAttribute('icon', 'file alternate outline');

        $mediaMenu
            ->addChild('app_media_icon', ['route' => 'app_admin_media_icon_index'])
            ->setLabel('Icon')
            ->setLabelAttribute('icon', 'hand peace outline');

        $formMenu = $menu
            ->addChild('app_form')
            ->setLabel('Formulaires');

        $formMenu
            ->addChild('app_message_contact', ['route' => 'app_admin_message_contact_index'])
            ->setLabel('Contact')
            ->setLabelAttribute('icon', 'comment alternate outline');

        $configurationMenu = $menu->getChild('configuration');
        $configurationMenu
            ->removeChild('countries')
            ->removeChild('zones')
            ->removeChild('currencies')
            ->removeChild('exchange_rates')
            ->removeChild('channels')
            // ->removeChild('locales')
            ->removeChild('payment_methods')
            ->removeChild('shipping_methods')
            ->removeChild('shipping_categories')
            ->removeChild('tax_categories')
            ->removeChild('tax_rates')
            // ->removeChild('admin_users')
        ;

        $configurationMenu
            ->addChild('app_category', ['route' => 'app_admin_category_index'])
            ->setLabel('Categories')
            ->setLabelAttribute('icon', 'object ungroup outline')
        ;

        // $configurationMenu
        //     ->addChild('app_organizations', ['route' => 'app_admin_organization_index'])
        //     ->setLabel('Organizations')
        //     ->setLabelAttribute('icon', 'building outline');

        $organization = $this->configurationService->getOrganization();
        
        // $configurationMenu
        //     ->addChild(
        //         'app_organization',
        //         [
        //             'route' => 'app_admin_organization_update',
        //             'routeParameters' => [ 'id' => $organization->getId() ]
        //         ]
        //     )
        //     ->setLabel('Organization')
        //     ->setLabelAttribute('icon', 'file')
        // ;

        $configurationMenu
            ->addChild('app_social_links', ['route' => 'app_admin_social_links_index'])
            ->setLabel('Social Links')
            ->setLabelAttribute('icon', 'facebook square');
        
        $configurationMenu
            ->addChild('app_cms_component', ['route' => 'app_admin_cms_component_index'])
            ->setLabel('Components')
            ->setLabelAttribute('icon', 'microchip');

        // $configurationMenu
        //     ->addChild('app_person', ['route' => 'app_admin_person_index'])
        //     ->setLabel('Persons')
        //     ->setLabelAttribute('icon', 'address card outline');
    }

    public function reorderMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $itemMenuList = ['app_cms'];

        if (in_array('ROLE_DEV', $this->user->getRoles())) {
            $itemMenuList[] = 'app_component';
        }

        $restItem = [
            'app_media',
            'app_form',
            // 'catalog',
            // 'sales',
            // 'customers',
            // 'marketing',
            'configuration',
        ];
        $menu->reorderChildren(array_merge($itemMenuList, $restItem));
    }
}
