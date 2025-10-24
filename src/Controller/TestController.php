<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends AbstractController
{
    public function apiTest(): JsonResponse
    {
        return new JsonResponse(['message' => 'API test route working!']);
    }

    public function storefrontTest(): JsonResponse
    {
        return new JsonResponse(['message' => 'Storefront test route working!']);
    }
}