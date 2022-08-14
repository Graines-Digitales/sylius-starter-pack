<?php

namespace App\Controller\Web;

use App\Entity\WebPage;
use App\WebContent\WebPage as WebContentWebPage;
use App\WebContent\MetaData;
use App\WebContent\Component;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WebPageController extends AbstractController
{
    /**
     * Modèle de page :
     * - ABOUT
     *
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function aboutPage(
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService
    ): Response
    {
        $slug = $webPageService->getPageSlug('about');
        $webpage = $webPageService->getData($slug);
        if (null === $webpage) {
            $params = ['message' => 'Webpage About Not Found'];

            return $this->render('@App/web/template_404.html.twig', $params);
        }

        $components = $componentService->getComponents($webpage);
        $metaData = $metaDataService->getData($webpage);
        $data = [
            'page' => $webpage,
            'meta_data' => $metaData,
            'components' => $components,
        ];

        return $this->render('@App/web/pages/about.html.twig', $data);
    }

    
    /**
     * Modèle de page :
     * - CONTACT
     *
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function contactPage(
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService
    ): Response
    {
        $slug = $webPageService->getPageSlug('contact');
        $webpage = $webPageService->getData($slug);
        if (null === $webpage) {
            $params = ['message' => 'Webpage Contact Not Found'];

            return $this->render('@App/web/template_404.html.twig', $params);
        }

        $components = $componentService->getComponents($webpage);
        $metaData = $metaDataService->getData($webpage);
        $data = [
            'page' => $webpage,
            'meta_data' => $metaData,
            'components' => $components,
        ];

        return $this->render('@App/web/pages/contact.html.twig', $data);
    }

    /**
     * Modèle de page :
     * - Liste des articles
     *
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function articles(
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService
    ): Response
    {
        $slug = $webPageService->getPageSlug('articles');
        $webpage = $webPageService->getData($slug);
        if (null === $webpage) {
            $params = ['message' => 'Webpage Articlea Not Found'];

            return $this->render('@App/web/template_404.html.twig', $params);
        }


        $components = $componentService->getComponents($webpage);

        $metaData = $metaDataService->getData($webpage);
        // $structuredData = $seoService->getStructuredData($metaData, $entities);
        $data = [
            // 'category' => $category,
            'page' => $webpage,
            'meta_data' => $metaData,
            'components' => $components,
            // 'articles' => $articles
        ];



        return $this->render('@App/web/pages/articles.html.twig', $data);
    }

    /**
     * Modèle de page :
     * - "FICHE" ARTICLE
     *
     * @param [type] $category
     * @param [type] $slug
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function article(
        $category,
        $slug,
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService
    ): Response
    {

        $article = $webPageService->getArticle($slug);
        if (null === $article) {
            $params = ['message' => 'Article Not Found'];

            return $this->render('@App/web/template_404.html.twig', $params);
        }

        $webpage = $webPageService->getDataFromArticle($article);
       
        $category = $webPageService->getCategory($category);
        $pageTemplateSlug = 'article';
        $pageTemplate = $webPageService->getData($pageTemplateSlug);
        $components = $componentService->getComponents($pageTemplate);
        $metaData = $metaDataService->getData($webpage);

        $data = [
            'category' => $category,
            'article' => $article,
            'page' => $webpage,
            'meta_data' => $metaData,
            'components' => $components,
        ];

        return $this->render('@App/web/pages/article.html.twig', $data);
    }


    /**
     * Modèle de page :
     * - ?LIBRE
     *
     * @param string $slug
     * @param WebContentWebPage $webPageService
     * @param Component $componentService
     * @param MetaData $metaDataService
     * @return Response
     */
    public function showPage(
        string $slug,
        WebContentWebPage $webPageService,
        Component $componentService,
        MetaData $metaDataService
    ): Response
    {
        $webpage = $webPageService->getData($slug);
        if (null === $webpage) {
            $params = ['message' => 'Webpage Not Found'];

            return $this->render('@App/web/template_404.html.twig', $params);
        }

        $components = $componentService->getComponents($webpage);

        $metaData = $metaDataService->getData($webpage);
        $data = [
            'page' => $webpage,
            'meta_data' => $metaData,
            'components' => $components,
        ];

        return $this->render('@App/web/pages/default.html.twig', $data);
    }

    /**
     * @Route("/template/email", name="web_template_email")
     */
    public function email(): Response
    {
        $data = [
            "first_name" => "fgdg",
            "company" => "",
            "last_name" => "xcvxcv",
            "collab" => "",
            "email" => "johan.remy@graines-digitales.online",
            "website" => "",
            "phone" => "+212700377087",
            "message" => "ghfdghdf"
        ];

        return $this->render('@App/web/components/email_default.html.twig', ['data' => $data]);
    }
}
