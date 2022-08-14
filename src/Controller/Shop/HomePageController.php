<?php

declare(strict_types=1);

namespace App\Controller\Shop;

use Twig\Environment;
use App\WebContent\SEO;
use App\WebContent\MetaData;
use App\WebContent\Component;
use Symfony\Component\HttpFoundation\Response;
use App\WebContent\WebPage as WebContentWebPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Modèle de page :
     * - HOME PAGE
     *
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function indexAction(
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService,
        SEO $seoService
    ): Response
    {
        $webpage = $webPageService->getData('accueil');
        if (null === $webpage) {
            $params = ['message' => 'Page Not Found'];

            return $this->render('@App/web/template_404.html.twig', $params);
            // return $this->render('@TwigBundle/Exception/error404.html.twig', $params);
        }

        $components = $componentService->getComponents($webpage);
        $metaData = $metaDataService->getData($webpage);
        $entities = [ 'WebPage' => $webpage ];
        $structuredData = $seoService->getStructuredData($metaData, $entities);

        $data = [
            'page' => $webpage,
            'meta_data' => $metaData,
            'components' => $components,
            'structuredData' => $structuredData,
        ];

        return new Response($this->twig->render('@App/web/pages/home.html.twig', $data));
    }

    /**
     * End point specific Landing pages
     *
     * @param [type] $slug
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function landingPage(
        $slug,
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService
    ): Response
    {
        dump($slug);
        die('this landing page end point');
    }
}
