<?php

namespace App\Controller\Api;

use App\Entity\Trip;
use App\Entity\Order;
use App\Payment\Payment;
use App\Data\Action\OrderAction;
use App\WebContent\Organization;
use App\Data\Action\PersonAction;
use App\WebContent\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentController extends AbstractController
{
    private $entityManager;

    private $organization;

    private $payment;

    private $personAction;

    private $orderAction;

    private $validator;

    private $form;

    public function __construct(
        EntityManagerInterface $entityManager
        , Organization $organization
        , Payment $payment
        , PersonAction $personAction
        , OrderAction $orderAction
        , ValidatorInterface $validator
        , Form $form
    ) {
        $this->entityManager = $entityManager;
        $this->organization = $organization;
        $this->payment = $payment;
        $this->personAction = $personAction;
        $this->orderAction = $orderAction;
        $this->validator = $validator;
        $this->form = $form;
    }

   /**
     * @Route("/api/v2/payment/create",
     *   name="payment_create",
     *   methods = { "POST" },
     *     defaults={
     *          "_api_resource_class"= Order::class
     *     }
     * )
    */
    public function create(Request $request)//: Message
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

        if (!isset($data['phone']) || empty($data['phone'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'téléphone non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['amount']) || empty($data['amount'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'montant non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['streetAddress']) || empty($data['streetAddress'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'adresse non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['postalCode']) || empty($data['postalCode'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'code postal non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['addressLocality']) || empty($data['addressLocality'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'ville non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['addressCountry']) || empty($data['addressCountry'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'pays non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (!isset($data['slug-product']) || empty($data['slug-product'])) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'identifiant du produit non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }

        

        $product = $this->entityManager->getRepository(Trip::class)
            ->findOneBySlug($data['slug-product'])
        ;



        if (empty($product)) {

            return new JsonResponse(
                [ 
                    'title' => 'une erreur est survenue',
                    'message' => 'produit non trouvé',
                    'statutCode' => JsonResponse::HTTP_BAD_REQUEST
                ]
                , JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
        $customer = $this->personAction->create($data);
        $order = $this->orderAction->create($data, $customer, $product);
        $data = $this->mergeData($data, $order, $customer);

        $errors = $this->validator->validate($customer);
        if (count($errors) > 0) {
            $data['errors'] = $errors[0]->getMessage();
        }
        // $errors = $this->validator->validate($message);
        // if (count($errors) > 0) {
        //     $data['errors'] = $errors[0]->getMessage();
        // }
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
        $response = $this->payment->getFormData($data);
        $response['html_form'] = $this->outputHtmlForm($response);
 
        return new JsonResponse(
            [
                'title' => 'Success',
                'message' => 'Votre commandé à bien été créer',
                'statutCode' => JsonResponse::HTTP_OK,
                'html_form' => $response['html_form'],
                'response' => $response
            ]
            , JsonResponse::HTTP_OK
        );
    }

    private function outputHtmlForm($formData, $locale = 'fr_FR')
    {
        $form = '<form action="'.$formData['form']['action'].'" method="'.$formData['form']['method'].'" accept-charset="'.$formData['form']['accept-charset'].'" id="auto-submit-form">';
        $form .= '<table class="table table-bordered">';
        foreach ($formData['fields'] as $name => $value) {

            $doc_name = (strpos($name, 'vads_') !== false) ? str_replace('_','-',$name): false;
            $doclink = ($doc_name) ? 'https://paiement.systempay.fr/doc/'.$locale.'/form-payment/standard-payment/'.$doc_name.'.html': '#';
            $form .= '<tr>';
            $form .= '<td><label for="'. $name. '"><a target="_blank" href="'.$doclink.'">'.$name.'</a></label></td>';
            $form .= '<td><input type="text" readonly="readonly"  name="'.$name.'" value="'.$value.'" /></td>';
            $form .= '</tr>';
        }
        $form .= '</table>';
        $form .= '<input type="submit" name="pay-submit" value="'.'Pay'.'" class="btn btn-primary btn-lg btn-block"/>';
        $form .= '</form>';

        return $form;
    }

    private function mergeData($data, $order, $customer)
    {

        return [
            "vads_amount" => $data['amount'],
            "vads_order_id" => $order->getId(),
            "vads_cust_id" => $customer->getId(),
            "vads_cust_name" => $data['firstname'] . ' ' . $data['lastname'],
            "vads_cust_address" => $data['streetAddress'],
            "vads_cust_zip" => $data['postalCode'],
            "vads_cust_city" => $data['addressLocality'],
            "vads_cust_country" => $data['addressCountry'],
            "vads_cust_phone" => $data['phone'],
            "vads_cust_email" => $data['email'],
            "vads_url_return" => $this->getParameter('web_host'),
            "vads_redirect_success_timeout" => 10
        ];
    }
}