<?php

namespace App\Controller\Web;

use App\WebContent\WebPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class SitemapController extends AbstractController
{
    public function index(Request $request, WebPage $webPageService)
    {
        // $this->setPages();

        $urls = [];
        $lastmod = date("Y-m-d");
        $change = 'daily';
        $webPages = $webPageService->getAllPages();
        $categoryPages = $webPageService->getAllCategoryPages();

        foreach ($webPages as $page) {
            if (method_exists($page, 'getSlug')) {
                $uri = $request->getSchemeAndHttpHost() . '/fr_FR/' . $page->getSlug();
            } else {
                $uri = $request->getSchemeAndHttpHost();
            }
            $priority = null; //$page->getPriority();
            if ($priority == null) {
                $priority = 1;
            }

            $urls[] = [
                'loc' => $uri,
                // 'lastmod' => $lastmod,
                // 'changefreq' => $change,
                'priority' => $priority
            ];
        }

        foreach ($categoryPages as $page) {
            if (method_exists($page, 'getSlug')) {
                $uri = $request->getSchemeAndHttpHost() . '/fr_FR/categorie-produits/' . $page->getSlug();
            } else {
                $uri = $request->getSchemeAndHttpHost();
            }
            $priority = null; //$page->getPriority();
            if ($priority == null) {
                $priority = 1;
            }

            $urls[] = [
                'loc' => $uri,
                // 'lastmod' => $lastmod,
                // 'changefreq' => $change,
                'priority' => $priority
            ];
        }

        return $this->render('@App/web/sitemap/sitemap.html.twig', [
            'urls' => $urls
        ]);
    }
}
