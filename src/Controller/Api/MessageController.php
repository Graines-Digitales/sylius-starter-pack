<?php

namespace App\Controller\Api;

use Exception;
use GuzzleHttp\Client;
use App\Entity\Message;
use App\WebContent\Form;
use App\WebContent\Organization;
use Symfony\Component\Mime\Email;
use SendinBlue\Client\Api\ContactsApi;
use SendinBlue\Client\Model\CreateContact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use SendinBlue\Client\Configuration as SendinBlueConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class MessageController extends AbstractController
{
    private $form;

    private $organization;

    private $mailer;

    public function __construct(
        Form $form,
        Organization $organization,
        MailerInterface $mailer
    ) {
        $this->form = $form;
        $this->organization = $organization;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/api/v2/message/product/create",
     * name="message_product_create",
     * methods = { "POST" },
     *     defaults={
     *          "_api_resource_class"=Message::class,
     
     *     }
     * )
    */
    public function product(Request $request) 
    {
        /**
         * Check Data
         **/
        // $data = $request->request->all();
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email']) || empty($data['email'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'email non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['slug-product']) || empty($data['slug-product'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'slug product not found',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
        /**
         * Save form
        **/
        $data = $this->form->saveFormContact($data);
        if (isset($data['errors'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => $data['errors'],
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }
        $data = $this->form->dataFieldTranslation($data);
     
        $configurationProject = $this->getParameter('configuration_project');
        $data['headline'] = $configurationProject['forms']['contact_product']['headline'];
        $data['headline'].= ' - ' . $data['slug-product'];

        // dump($data);die;
        dump(...$this->organization->getEmails());die;

        /**
         * Send email
         **/
        $email = (new Email())
            ->from($data['email'])
            ->to(...$this->organization->getEmails())
            ->subject($data['headline'])
            ->embedFromPath($this->getParameter('kernel.project_dir') . '/public/build/app/images/admin-logo.png', 'logo')
            ->html($this->renderView(
                    '@App/web/components/email_default.html.twig',
                    ['data' => $data]
                )
            )
        ;
    
        try {
            $this->mailer->send($email);


            $data['headline'] = $configurationProject['forms']['contact_default']['headline_confirm'];
            $email = (new Email())
                ->from($this->organization->getEmail())
                ->to($data['email'])
                ->subject($data['headline'])
                ->embedFromPath($this->getParameter('kernel.project_dir') . '/public/build/app/images/admin-logo.png', 'logo')
                ->html($this->renderView(
                        '@App/web/components/email_confirm.html.twig',
                        ['data' => $data]
                    )
                )
            ;
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            
            $response = json_decode($e->getResponseBody(), true);
            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => $response['message'],
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
    
        return new JsonResponse(
            [
                'title' => 'Success',
                'message' => 'votre formulaire a été enregistré',
                'statutCode' => JsonResponse::HTTP_OK
            ]
            , JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/api/v2/message/contact/create",
     * name="message_contact_create",
     * methods = { "POST" },
     *     defaults={
     *          "_api_resource_class"=Message::class,
     
     *     }
     * )
    */
    public function contact(Request $request) 
    {
        /**
         * Check Data
         **/
        // $data = $request->request->all();
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email']) || empty($data['email'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'email non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

       
        /**
         * Save form
        **/
        $data = $this->form->saveFormContact($data);
        if (isset($data['errors'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => $data['errors'],
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }
        $data = $this->form->dataFieldTranslation($data);
        
        $configurationProject = $this->getParameter('configuration_project');
        $data['headline'] = $configurationProject['forms']['contact_default']['headline'];

        /**
         * Send email
         **/
        $email = (new Email())
            ->from($data['email'])
            ->to(...$this->organization->getEmails())
            ->subject($data['headline'])
            ->embedFromPath($this->getParameter('kernel.project_dir') . '/public/build/app/images/admin-logo.png', 'logo')
            ->html($this->renderView(
                    '@App/web/components/email_default.html.twig',
                    ['data' => $data]
                )
            )
        ;
    
        try {
            $this->mailer->send($email);

            $data['headline'] = $configurationProject['forms']['contact_default']['headline_confirm'];
            $email = (new Email())
                ->from($this->organization->getEmail())
                ->to($data['email'])
                ->subject($data['headline'])
                ->embedFromPath($this->getParameter('kernel.project_dir') . '/public/build/app/images/admin-logo.png', 'logo')
                ->html($this->renderView(
                        '@App/web/components/email_confirm.html.twig',
                        ['data' => $data]
                    )
                )
            ;
            $this->mailer->send($email);

        } catch (TransportExceptionInterface $e) {
            
            $response = json_decode($e->getResponseBody(), true);
            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => $response['message'],
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
    
        return new JsonResponse(
            [
                'title' => 'Success',
                'message' => 'votre formulaire a été enregistré',
                'statutCode' => JsonResponse::HTTP_OK
            ]
            , JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/api/v2/newsletter/create",
     * name="newsletter_create",
     * methods = { "POST" },
     *     defaults={
     *          "_api_resource_class"=Message::class,
     *     }
     * )
    */
    public function newsletter(Request $request)//: Message
    {
        /**
         * Check Data
        **/
        // $data = $request->request->all();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || empty($data['email'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'email non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
        // dump($data);die;
        if(!$this->getParameter('sendinblue_api_key')) {

            return new JsonResponse(
                [ 
                    'message' => 'api sendinblue not configured' 
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $credentials = SendinBlueConfiguration::getDefaultConfiguration()->setApiKey(
            'api-key',
            $this->getParameter('sendinblue_api_key')
        );

        $apiInstance = new ContactsApi(
            new Client(),
            $credentials
        );

        $createContact = new CreateContact([
            'email' => $data['email'],
            'updateEnabled' => true,
            'attributes' => [ 
                'isVIP'=> 'true' 
            ],     
            'listIds' =>[1, (int)$this->getParameter('sendinblue_list_newsletter_id')]
        ]);

        try {
            $response = $apiInstance->createContact($createContact);
            if(null === $response) {

                return new JsonResponse(
                    [ 
                        'title' => 'une erreur est survenue',
                        'message' => 'email already exist',
                        'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                    ]
                    , JsonResponse::HTTP_BAD_REQUEST
                );
            }
        } catch (Exception $e) {
            
            $response = json_decode($e->getResponseBody(), true);
            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => $response['message'],
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            [
                'title' => 'Success',
                'message' => 'Your email has been registered',
                'statutCode' => JsonResponse::HTTP_OK
            ]
            , JsonResponse::HTTP_OK
        );
    }
}