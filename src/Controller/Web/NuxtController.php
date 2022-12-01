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
        $lockFilePath = $kernelProjectDir.'/fetch.txt';
        $filesystem = new Filesystem();
        if (!$filesystem->exists($lockFilePath)) {
            
            $filesystem->dumpFile($lockFilePath, 'Fetch is running');
        } else {
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
// dump($command);die;
            $process->mustRun();
            $response['success'] = $process->getOutput();
            $filesystem->remove($lockFilePath);

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_OK
            );

        } catch (ProcessFailedException $exception) {

            $response['error'] = $exception->getMessage();
            $filesystem->remove($lockFilePath);

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        
    }

    public function build(): JsonResponse
    {
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        $response = [];
        $command = $kernelProjectDir.'/build.sh';
        $lockFilePath = $kernelProjectDir.'/build.txt';

        $process = new Process(
            [$command, $kernelProjectDir]
        );
        $process->setTimeout(10800); // 3 heures

        $filesystem = new Filesystem();
        if (!$filesystem->exists($lockFilePath)) {
            $filesystem->dumpFile($lockFilePath, 'Build is running');
        } else {
            $response['error'] = 'process build is already running!';

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            $process->mustRun();
            $response['success'] = $process->getOutput();
            $filesystem->remove($lockFilePath);

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_OK
            );
        } catch (ProcessFailedException $exception) {
            $response['error'] = $exception->getMessage();
            $filesystem->remove($lockFilePath);

            return new JsonResponse(
                $response,
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
