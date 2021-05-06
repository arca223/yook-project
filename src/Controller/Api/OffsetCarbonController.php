<?php


namespace App\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class OffsetCarbonController extends AbstractController
{

    public function __invoke()
    {
        return new JsonResponse(["test"=>"test"]);
    }
}