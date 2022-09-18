<?php

namespace App\WebContent;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;


class StructuredData extends AbstractWebContent
{
    private $host;

    public function generate($metaData, $entities)
    {
        $page = (isset($entities['WebPage']))?$entities['WebPage']:null;
        $article = (isset($entities['Article']))?$entities['Article']:null;
        $product = (isset($entities['Product']))?$entities['Product']:null;
        $category = (isset($entities['Tag']))?$entities['Tag']:null;


        /** A revoir... */
        $this->host = $this->container->get('router')->getContext()->getScheme() . '://' . $this->container->get('router')->getContext()->getHost();
        $request = Request::createFromGlobals();
        $newArrayPath = explode("/", substr($request->getpathInfo(), 1));

        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $path = $kernelProjectDir . '/config/json_ld_schema/';
        $structuredData = [];
        $i = 0;
        // dump($path);die;
        $filesystem = new Filesystem();
        if($filesystem->exists($path)) {
            $finder = new Finder();
            $finder->depth('== 0');
            $finder->files()->in($path);
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $filePath = $file->getPath();
                    $fileNameWithExtension = $file->getRelativePathname();
                    $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                    $file = basename($fileNameWithExtension);
                    $filename = basename($fileNameWithExtension, ".".$ext);
                    if('json' === $ext) {
                        $schema = json_decode(file_get_contents($absoluteFilePath), true);
                    }
                    $schemaType = (isset($schema['@type']))? $schema['@type']: null;
                    switch ($schemaType) {
                        case 'WebPage' :
                            // dump($page);die;
                            if (!empty($page)) {
                                $structuredData[$i] = $this->generatePageSchema(
                                    $page
                                    , $schema
                                );
                                $i++;
                            }
                        break;
                        case 'BreadcrumbList':
                            $structuredData[$i] = $this->generateBreadcrumbSchema(
                                $newArrayPath
                                , $this->host
                                , $schema
                            );
                            $i++;
                        break;
                        case 'Organization':
                            if (!empty($metaData['organization'])) {
                                $structuredData[$i] = $this->generateOrganizationSchema(
                                    $metaData['organization']
                                    , $schema
                                );
                                $i++;
                            }
                            
                        break;
                        case 'Article':
                            if(!empty($article)){
                                $structuredData[$i] = $this->generateArticleSchema(
                                    $article
                                    , $metaData['organization']
                                    ,$newArrayPath
                                    , $this->host
                                    ,$schema
                                );
                                $i++;
                            }
                            
                        break;
                    }
                    
                }
            }
        }

        return $structuredData;
        // return json_encode(
        //     $structuredData
        //     , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        // );
    }

    private function generateArticleSchema($article,$metaData,$newArrayPath,$host,$schema){
        $author = [];
        $upToDateSchema = [];
        foreach ($schema as $key => $item) {
            if ('@context' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('@type' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('headline' == $key) {
                $upToDateSchema[$key] = $article->getHeadline();
            }
            if ('alternativeHeadline' == $key) {
                $upToDateSchema[$key] = $article->getAlternativeHeadline();
            }
            if ('url' == $key) {
                $upToDateSchema[$key] = $host.'/'.$newArrayPath[0].'/'.$newArrayPath[1];
            }
            if ('datePublished' == $key) {
                $upToDateSchema[$key] = $article->getDatePublished()->format('Y-m-d H:i:s');
            }
            if ('dateCreated' == $key) {
                $upToDateSchema[$key] = $article->getDateCreated()->format('Y-m-d H:i:s');
            }
            if ('dateModified' == $key) {
                $upToDateSchema[$key] = $article->getDateModified()->format('Y-m-d H:i:s');
            }
            if ('description' == $key) {
                $upToDateSchema[$key] = $article->getArticleResume();
            }
            if ('author' == $key) {
                $j = 0;
                foreach ($item as $k => $itemList) {
                    if (0 == $j) {
                        $author = [
                            "@type" => "Person",
                            "name" => $metaData->getName(),
                            "url"=>$metaData->getUrl()
        
                        ];
                    }
                $j++;
                }
                $upToDateSchema[$key] = $author;                                      
            }
            if ('image' == $key) {
                $upToDateSchema[$key] = $article->getPrimaryImage()->getUrl();
            }
        }
        return $upToDateSchema;  
    }
    
    private function generateOrganizationSchema($metaData, $schema)
    {
        $addresses = [];
        $upToDateSchema = [];
        foreach ($schema as $key => $item) {
            if ('@context' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('@type' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('name' == $key) {
                $upToDateSchema[$key] = $metaData->getName();
            }
            if ('email' == $key) {
                $upToDateSchema[$key] = $metaData->getEmail();
            }
            if ('faxNumber' == $key) {
                $upToDateSchema[$key] = $metaData->getPhone();
            }
            if ('telephone' == $key) {
                $upToDateSchema[$key] = $metaData->getPhone();
            }
            if ('image' == $key) {
                $upToDateSchema[$key] = $metaData->getPrimaryImage();
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
                $upToDateSchema[$key] = $addresses;                                      
            }
            // dump($metaData);die;
            if ('openingHoursSpecification' == $key) {
                // $upToDateSchema[$key] = $metaData->getOpeningHours();
            }
        }
        return $upToDateSchema;
    }

    private function generatePageSchema($page, $schema)
    {
        $upToDateSchema = [];

        foreach ($schema as $key => $item) {
            if ('@context' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('@type' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('name' == $key) {
                $upToDateSchema[$key] = $page->getMetaTitle();
            }
            if ('description' == $key) {
                $upToDateSchema[$key] = $page->getMetaDescription();
            }
        }

        return $upToDateSchema;
    }
    
    private function generateBreadcrumbSchema($newArrayPath,$host, $schema)
    {
        $upToDateSchema = [];
        $itemListElement = [];
        foreach ($schema as $key => $item) {
            if ('@context' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('@type' == $key) {
                $upToDateSchema[$key] = $item;
            }
            if ('itemListElement' == $key) {
                $j = 0;
                foreach ($item as $k => $itemList) {
                    if (0 == $j) {
                        $itemListElement[$k] = [
                                "@type" => "ListItem",
                                "position" => 0,
                                "item"=>
                                    [
                                        '@id' => $host,
                                        'name' => 'accueil'
                                    ]
                                
                        ];
                    } 
                    if (1 == $j && !empty($newArrayPath[0])) {
                            $itemListElement[$k] = [
                                "@type" => "ListItem",
                                    "position" => 1,
                                    "item"=>
                                        [
                                            '@id' => $host.'/'.$newArrayPath[0],
                                            'name' => $newArrayPath[0]
                                        ]
                                    
                            ];
                        }
                        if (2 == $j && isset($newArrayPath[1])) {
                            $itemListElement[$k] = [
                                "@type" => "ListItem",
                                    "position" => 2,
                                    "item"=>
                                        [
                                            '@id' => $host.'/'.$newArrayPath[0].'/'.$newArrayPath[1],
                                            'name' => $newArrayPath[1]
                                        ]
                                    
                            ];
                        }
                    $j++;
                } // fin de foreach item
                $upToDateSchema[$key] = $itemListElement;
            }
        }

        // dd($upToDateSchema);
        return $upToDateSchema;
    }

}
