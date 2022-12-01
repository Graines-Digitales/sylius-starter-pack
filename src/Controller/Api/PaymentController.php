<?php

namespace App\Controller\Api;

use App\Entity\Trip;
use App\Payment\Payment;
use App\Data\Action\OrderAction;
use App\WebContent\Organization;
use App\Data\Action\PersonAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PaymentController extends AbstractController
{
    private $entityManager;

    private $organization;

    private $payment;

    private $personAction;

    private $orderAction;

    public function __construct(
        EntityManagerInterface $entityManager
        , Organization $organization
        , Payment $payment
        , PersonAction $personAction
        , OrderAction $orderAction
    ) {
        $this->entityManager = $entityManager;
        $this->organization = $organization;
        $this->payment = $payment;
        $this->personAction = $personAction;
        $this->orderAction = $orderAction;
    }

   /**
     * @Route("/api/v2/payment/create",
     *   name="payment_create",
     *   methods = { "POST" },
     * )
    */
    public function create(Request $request)//: Message
    {
        /**
         * Check Data
        **/
        // $data = $request->request->all();
        $data = json_decode($request->getContent(), true);
        // dump($args);
        dump($data);die;
        $product = $this->entityManager->getRepository(Trip::class)
            ->findOneBySlug($data['slug-product'])
        ;
        $customer = $this->personAction->create($data);
        $order = $this->orderAction->create($data, $customer, $product);
        $data = $this->mergeData($data);
        $response = $this->payment->getFormData($data);
        $response['html_form'] = $this->outputHtmlForm($response);
 
        return new JsonResponse(
            [
                'title' => 'Success',
                'message' => 'Your order has been created',
                'statutCode' => JsonResponse::HTTP_OK,
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

    private function mergeData($data)
    {
        return [
            "vads_amount" => $data['amount'],
            "vads_order_id" => "1234",
            "vads_cust_id" => "aze",
            "vads_cust_name" => $data['firstname'] . ' ' . $data['lastname'],
            "vads_cust_address" => $data['streetAddress'],
            "vads_cust_zip" => $data['postalCode'],
            "vads_cust_city" => $data['addressLocality'],
            "vads_cust_country" => $data['addressCountry'],
            "vads_cust_phone" => $data['phone'],
            "vads_cust_email" => $data['firstname'],
            "vads_url_return" => 'https://preprod.kazengarden.com/payment_confirmation',
            "vads_redirect_success_timeout" => 10
        ];
    }
}