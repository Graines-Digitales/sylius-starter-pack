<?php

namespace App\Controller\Api;

use App\Entity\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MessageController extends AbstractController
{
    // public function __construct(
    //     // private BookPublishingHandler $bookPublishingHandler
    // ) {}

    /**
     * @Route("/api/v2/messages/save",
     * name="save_form_contact",
     * methods = { "POST" },
     *     defaults={
     *          "_api_resource_class"=Message::class,
     
     *     }
     * )
    */
    public function __invoke(Message $data)//: Message
    {
       
        return new JsonResponse(
            $data
            , JsonResponse::HTTP_OK
        );
        
        
    }
}