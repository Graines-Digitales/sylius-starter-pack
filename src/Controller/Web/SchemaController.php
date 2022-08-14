<?php

namespace App\Controller\Web;

use App\Tools\Schema;
use App\WebContent\Form;
use App\WebContent\Organization;
use App\WebContent\SEO;
use App\WebContent\WebPage;
use App\WebContent\MetaData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;


class SchemaController extends AbstractController
{
    public function constraintsFromEntity(Request $request, Schema $schema)
    {
        $asserts = $schema
            ->getConstraintsFromEntity($request->get('entity'))
            ->getJqueryValidateRules()
        ;

        return new JsonResponse(
            $asserts
            , JsonResponse::HTTP_OK
        );
    }

    public function constraintsFromFormType(Request $request, Schema $schema)
    {
        $asserts = $schema
            ->getConstraintsFromFormType($request->get('form'))
            ->getJqueryValidateRules()
        ;

        return new JsonResponse(
            $asserts
            , JsonResponse::HTTP_OK
        );
    }

    /**
     * AJAX CALL
     *
     * @param Request $request
     * @param Form $form
     * @param Organization $organization
     * @param MailerInterface $mailer
     * @return JsonResponse
     */
    public function saveFormMessage(
        Request $request,
        Form $form,
        Organization $organization,
        MailerInterface $mailer

    ): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            /**
             * Check Data
             **/
            $data = $request->request->all();

            if(isset($request->files) && !empty($request->files)) {
                // foreach($request->files as $file){
                //     dump($file);
                // }
                $attachments = $request->files;
            }

            // $attachments = $files;
            if (!isset($data['email']) || empty($data['email'])) {
                return new JsonResponse([
                    'data' => [
                    ]
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

            /**
             * Save form
             **/
            // dump($attachments);die;
            $data = $form->saveFormContact($data, $attachments);
            $data = $form->dataFieldTranslation($data);
            if(isset($data['attachments'])) {
                $attachments = $data['attachments'];
                unset($data['attachments']);
            }
           

            /**
             * Send email
             **/
            $email = (new Email())
               ->from($data['email'])
               ->to(...$organization->getEmails())
               ->subject($data['objet'])
               ->html($this->renderView(
                    '@App/web/components/email_default.html.twig',
                    ['data' => $data]
                )
            );
            
            // foreach($attachments as $attachment) {
            //     $email->attachFromPath($attachment);
            // }
              
           
           $mailer->send($email);
        }

        return new JsonResponse([
            'data' => [
            ]
        ], JsonResponse::HTTP_OK);
    }


    /**
     * @Route("/structured-data", name="app_schema_structured_data")
     **/
    public function getStructuredDataSchema(
        SEO $seoService
        , MetaData $metaDataService
        , WebPage $webPageService
    ): JsonResponse
    {
        $slug = 'accueil';
        $webPage = $webPageService->getData($slug);
        $metaData = $metaDataService->getData($webPage);
        $entities = ['webPage' => $webPage];
        $structuredData = $seoService->getStructuredData($metaData, $entities);

        return new JsonResponse(
            $structuredData,
            JsonResponse::HTTP_OK
        );
    }
}
