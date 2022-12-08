<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use DateTime;
use App\Entity\TripTranslation;
use App\WebContent\Organization;
use Symfony\Component\Mime\Email;
use App\Data\Action\OrderTripAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class OrderTripController extends ResourceController
{
    public function validateAction(
        Request $request
        , OrderTripAction $orderTripAction
        , Organization $organization
        , MailerInterface $mailer
    ): Response {         
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);
        $status = $resource->getOrderStatus();
        if('en cours' === $status) {
            $orderTripAction->validate($resource);
            $email = (new Email())
                ->from($organization->getEmail())
                ->to($resource->getCustomer()->getEmail())
                ->subject('Confirmation de votre commande')
                ->embedFromPath($this->getParameter('kernel.project_dir') . '/public/build/app/images/admin-logo.png', 'logo')
                ->html($this->renderView(
                        '@App/web/components/email_order_success.html.twig',
                        [ 'data' => [ 'headline' => 'Confirmation de votre commande'] ]
                    )
                )
            ;
            try {
                $mailer->send($email);
    
            } catch (TransportExceptionInterface $e) {
                
                throw new TransportExceptionInterface($e->getMessage());
            }
        }
        else if('validé' == $status) {
            $orderTripAction->cancel($resource);
        } 
        else {
            $orderTripAction->validate($resource);
        }
        $this->manager->persist($resource);
        $this->manager->flush();

        if ($configuration->isHtmlRequest()) {
            $this->flashHelper->addSuccessFlash($configuration, ResourceActions::CREATE, $resource);
        }

        return $this->redirectHandler->redirectToReferer($configuration);
    }
   
}