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
use Symfony\Component\Filesystem\Filesystem;

class NuxtController extends AbstractController
{
    public function fetch(): JsonResponse
    {
        $response = [];
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        $command = $kernelProjectDir . DIRECTORY_SEPARATOR . 'fetch.sh';
        $lockFilePath = $kernelProjectDir . DIRECTORY_SEPARATOR . '/fetch.txt';

        $filesystem = new Filesystem();
        if ($filesystem->exists($lockFilePath)) {
            $response['error'] = 'process fetch is already running!';
            
            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
        $process = new Process(
            [
                $command
            ]
        );
        $process->setTimeout(10800); // 3 heures

        try {

            $process->mustRun();
            $response['success'] = $process->getOutput();

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_OK
            );

        } catch (ProcessFailedException $exception) {

            $response['error'] = $exception->getMessage();

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
    }

    public function build(): JsonResponse
    {
        $response = [];
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        $command = $kernelProjectDir . DIRECTORY_SEPARATOR . '/build.sh';
        $lockFilePath = $kernelProjectDir . DIRECTORY_SEPARATOR . '/build.txt';

        $filesystem = new Filesystem();
        if ($filesystem->exists($lockFilePath)) {
            $response['error'] = 'process build is already running!';

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $process = new Process(
            [
                $command, 
                $kernelProjectDir
            ]
        );
        $process->setTimeout(10800); // 3 heures
        try {

            $process->mustRun();
            $response['success'] = $process->getOutput();
       
            return new JsonResponse(
                $response,
                JsonResponse::HTTP_OK
            );
        } catch (ProcessFailedException $exception) {
            $response['error'] = $exception->getMessage();
 
            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
