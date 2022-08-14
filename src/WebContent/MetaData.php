<?php

namespace App\WebContent;

use App\Entity\Channel\Channel;
use App\Entity\WebPage;
use Sylius\Component\Core\Model\ShopBillingData;

class MetaData extends AbstractWebContent
{
    public function getData($webPage)
    {
        $channel = $this->manager->getRepository(Channel::class)
            ->findOneBy(array(), array(), 1);
        $shopBillingData = $this->manager->getRepository(ShopBillingData::class)
            ->findOneBy(array(), array(), 1);
        $organizationName = '';
        if ($shopBillingData !== null) {
            $organizationName = $shopBillingData->getCompany();
        }

        
        $personEmail = $channel->getContactEmail();
        //$socialOrganizationType = $this->manager->getRepository(OrganizationType::class)
        //    ->findOneBy([ 'slug' => 'lien-reseau-social' ]);

        //$category = $this->manager->getRepository(Tag::class)
        //    ->findBy([ 'slug' => 'blog' ]);


        return [
            'person' => $personEmail,
            'organization_name' => $organizationName,
            'organization' => $this->configurationService->getOrganization(),
            //'socials' => $this->manager->getRepository(Organization::class)
            //    ->findBy(['type' => $socialOrganizationType]),
            'page' => [
                'meta_title' => $webPage->getMetaTitle(),
                'meta_description' => $webPage->getMetaDescription()
            ],
            'cookies' => [],
            //'media' => 
            //    'cache_prefix' => $this->container->getParameter('cache_prefix'),
            //    'uploads_media_folder' => $this->container->getParameter('uploads.media.folder')
            //],
            //'blogArticlesCount' => $this->manager->getRepository(\App\Entity\Article::class)
            //    ->count(['category' => $category ])
        ];
    }
}