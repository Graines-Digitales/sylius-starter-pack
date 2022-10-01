<?php

namespace App\Controller\Api;

use App\Entity\Message;
use App\WebContent\Form;
use App\WebContent\Organization;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class OrganizationController extends AbstractController
{
    private $organization;

    public function __construct(Organization $organization) 
    {
        $this->organization = $organization;
    }

    /**
     * @Route("/api/v2/organization/configuration",
     * name="organization_configuration",
     * methods = { "GET" },
     *   defaults={
     *     "_api_resource_class"=Organization::class,
     *   }
     * )
    */
    public function configuration(Request $request) 
    {
        dump($request);
        dump($request->isXmlHttpRequest());
       die;
       
        return new JsonResponse(
            []
            , JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/api/v2/organization/media-encoding-formats",
     * name="organization_media_encoding_formats",
     * methods = { "GET" },
     *   defaults={
     *     "_api_resource_class"=Organization::class,
     *   }
     * )
    */
    public function mediaEncodingFormats(Request $request) 
    {
        dump($request);
        dump($request->isXmlHttpRequest());
       die;
       
        return new JsonResponse(
            []
            , JsonResponse::HTTP_OK
        );
    }
    
}