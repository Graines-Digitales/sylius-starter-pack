<?php

namespace App\WebContent;

use App\Entity\ArticleTranslation;
use App\Entity\TripTranslation;
use App\Entity\WebPageTranslation;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;


class StructuredData extends AbstractWebContent
{
    private $url;

    private $cdn;

    public function generate($metaData, $entity)
    {
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');

        $this->url = $metaData['organization']->getUrl();
        if(null === $this->url) {
            throw new \Exception('Please define the URL of your organization');
        }

        $this->cdn = $this->container->getParameter('cdn_media');
        if(null === $this->cdn) {
            throw new \Exception('Please define the CDN of your organization');
        }

        $path = $kernelProjectDir . '/config/json_ld_schema/organization.json';
        $organizationSchema = json_decode(file_get_contents($path), true);
        $path = $kernelProjectDir . '/config/json_ld_schema/breadcrumb_list.json';
        $breadcrumbListSchema = json_decode(file_get_contents($path), true);

        $structuredData = [];

        if ($entity instanceof WebPageTranslation) {
            $path = $kernelProjectDir . '/config/json_ld_schema/web_page.json';
            $filesystem = new Filesystem();
            if ($filesystem->exists($path)) {
                $itemList = [
                    'home',
                    $entity->getSlug()
                ];
                array_push(
                    $structuredData,
                    $this->generateBreadcrumbSchema($itemList, $breadcrumbListSchema)
                );
                
                array_push(
                    $structuredData,
                    $this->generateOrganizationSchema(
                        $metaData['organization'],
                        $organizationSchema
                    )
                );
                $schema = json_decode(file_get_contents($path), true);
                array_push(
                    $structuredData,
                    $this->generatePageSchema($entity, $schema)
                );
            }
        }

        if($entity instanceOf ArticleTranslation) {
            $path = $kernelProjectDir . '/config/json_ld_schema/article.json';
            $filesystem = new Filesystem();
            if ($filesystem->exists($path)) {
                $itemList = [
                    'home',
                    'blog',
                    $entity->getSlug()
                ];
                array_push(
                    $structuredData,
                    $this->generateBreadcrumbSchema($itemList, $breadcrumbListSchema)
                );

                array_push(
                    $structuredData,
                    $this->generateOrganizationSchema(
                        $metaData['organization'],
                        $organizationSchema
                    )
                );
                $schema = json_decode(file_get_contents($path), true);
                array_push(
                    $structuredData,
                    $this->generateArticleSchema(
                        $entity,
                        $metaData['organization'],
                        $schema
                    )
                );
            }
        }


        if($entity instanceOf TripTranslation) {
            $path = $kernelProjectDir . '/config/json_ld_schema/trip.json';
            $filesystem = new Filesystem();
            if ($filesystem->exists($path)) {
                $itemList = [
                    'home',
                    'séjour',
                    $entity->getSlug()
                ];
                array_push(
                    $structuredData,
                    $this->generateBreadcrumbSchema($itemList, $breadcrumbListSchema)
                );
                array_push(
                    $structuredData,
                    $this->generateOrganizationSchema(
                        $metaData['organization'],
                        $organizationSchema
                    )
                );
                $schema = json_decode(file_get_contents($path), true);
                array_push(
                    $structuredData,
                    $this->generateTripSchema($entity, $schema)
                );
            }
        }

        return $structuredData;
    }

    private function generateArticleSchema($article, $organization, $referenceSchema)
    {
        $author = [];
        $schema = [];
        foreach ($referenceSchema as $key => $item) {
            if ('@context' == $key) {
                $schema[$key] = $item;
            }
            if ('@type' == $key) {
                $schema[$key] = $item;
            }
            if ('headline' == $key) {
                $schema[$key] = $article->getHeadline();
            }
            if ('alternativeHeadline' == $key) {
                $schema[$key] = $article->getAlternativeHeadline();
            }
            if ('url' == $key) {
                $schema[$key] = $this->url . '/blog/' .$article->getSlug();
            }
            if ('datePublished' == $key) {
                $schema[$key] = $article->getDatePublished()->format('Y-m-d H:i:s');
            }
            if ('dateCreated' == $key) {
                $schema[$key] = $article->getCreatedAt()->format('Y-m-d H:i:s');
            }
            if ('dateModified' == $key) {
                $schema[$key] = $article->getUpdatedAt()->format('Y-m-d H:i:s');
            }
            if ('description' == $key) {
                $schema[$key] = $article->getArticleResume();
            }
            if ('author' == $key) {
                array_push(
                    $author,
                    [
                        "@type" => "Person",
                        "name" => $organization->getName(),
                        "url"=> $organization->getUrl()
                    ]
                );
                $schema[$key] = $author;                                      
            }
            if ('image' == $key && null !== $article->getTranslatable()->getPrimaryImage()) {
                $schema[$key] = $this->cdn . '/' . $article->getTranslatable()->getPrimaryImage()->getFilename();
            }
        }

        return $schema;  
    }
    
    private function generateOrganizationSchema($metaData, $referenceSchema)
    {
        $addresses = [];
        $schema = [];
        foreach ($referenceSchema as $key => $item) {
            if ('@context' == $key) {
                $schema[$key] = $item;
            }
            if ('@type' == $key) {
                $schema[$key] = $item;
            }
            if ('name' == $key) {
                $schema[$key] = $metaData->getName();
            }
            if ('email' == $key) {
                $schema[$key] = $metaData->getEmail();
            }
            if ('faxNumber' == $key) {
                $schema[$key] = $metaData->getPhone();
            }
            if ('telephone' == $key) {
                $schema[$key] = $metaData->getPhone();
            }
            if ('url' == $key) {
                $schema[$key] = $metaData->getUrl();
            }
            if ('image' == $key && null !== $metaData->getPrimaryImage()) {
                $schema[$key] = $this->cdn . '/' . $metaData->getPrimaryImage()->getFilename();
            }
            if ('address' == $key) {
                $j = 0;
                // dump($metaData->getAddresses()[0]);die;
                if (null !== $metaData->getAddresses()[0]) {
                    foreach ($item as $k => $itemList) {
                        if (0 == $j) {
                            $addresses = [
                            "@type"=> "PostalAddress",
                            "addressLocality" => $metaData->getAddresses()[0]->getCity().', '.$metaData->getAddresses()[0]->getCountry(),
                            "postalCode"=> $metaData->getAddresses()[0]->getPostcode(),
                            "streetAddress"=> $metaData->getAddresses()[0]->getAddress()
                        ];
                        }
                        $j++;
                    }
                }
                $schema[$key] = $addresses;                                      
            }
            // dump($metaData);die;
            if ('openingHoursSpecification' == $key) {
                // $upToDateSchema[$key] = $metaData->getOpeningHours();
            }
        }
        return $schema;
    }

    private function generateTripSchema($entity, $referenceSchema)
    {
        $schema = [];

        foreach ($referenceSchema as $key => $item) {
            if ('@context' == $key) {
                $schema[$key] = $item;
            }
            if ('@type' == $key) {
                $schema[$key] = $item;
            }
            if ('name' == $key) {
                $schema[$key] = $entity->getMetaTitle();
            }
            if ('description' == $key) {
                $schema[$key] = $entity->getMetaDescription();
            }
        }

        return $schema;
    }

    private function generatePageSchema($page, $referenceSchema)
    {
        $schema = [];

        foreach ($referenceSchema as $key => $item) {
            if ('@context' == $key) {
                $schema[$key] = $item;
            }
            if ('@type' == $key) {
                $schema[$key] = $item;
            }
            if ('name' == $key) {
                $schema[$key] = $page->getMetaTitle();
            }
            if ('description' == $key) {
                $schema[$key] = $page->getMetaDescription();
            }
        }

        return $schema;
    }
    
    private function generateBreadcrumbSchema($itemList, $referenceSchema, $root = null)
    {
        $schema = [];
        foreach ($referenceSchema as $key => $item) {
            if ('@context' == $key) {
                $schema[$key] = $item;
            }
            if ('@type' == $key) {
                $schema[$key] = $item;
            }
            if ('itemListElement' == $key) {
                
                $itemListElement = [];
                $url = $this->url;
                foreach($itemList as $key=>$item) {     
                    $url.= ($item == 'home')? '/' . '': '/' . $item;
                    array_push(
                        $itemListElement,
                        [
                            "@type" => "ListItem",
                            "position" => $key,
                            "item" => [
                                '@id' => $url,
                                'name' => $item
                            ]    
                        ]
                    );
                }
                
                $schema[$key] = $itemListElement;
            }
        }

        return $schema;
    }

}
