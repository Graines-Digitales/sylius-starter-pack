<?php

namespace App\Controller\Web;

use App\WebContent\SEO;
use App\WebContent\WebPage;
use App\WebContent\MetaData;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NuxtController extends AbstractController
{
    public function fetch(): JsonResponse
    {
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        $response = [];
        $command = $kernelProjectDir.'/fetch.sh';
        
        $process = new Process(
            [$command, $kernelProjectDir]
        );
        $process->setTimeout(10800); // 3 heures
        try {
            $process->mustRun();
            $response['success'] = $process->getOutput();
        } catch (ProcessFailedException $exception) {
            $response['error'] = $exception->getMessage();
        }
        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK
        );
    }

    public function build(): JsonResponse
    {
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        $response = [];
        $command = $kernelProjectDir.'/build.sh';
        
        $process = new Process(
            [$command, $kernelProjectDir]
        );
        $process->setTimeout(10800); // 3 heures
        try {
            $process->mustRun();
            $response['success'] = $process->getOutput();
        } catch (ProcessFailedException $exception) {
            $response['error'] = $exception->getMessage();
        }
        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK
        );
    }
}
