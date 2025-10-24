<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class StorefrontTestController extends StorefrontController
{
    /**
     * @Route("/offerta/test", name="offerta.test", methods={"GET"})
     */
    public function test(): JsonResponse
    {
        return new JsonResponse(['message' => 'Storefront test route working!']);
    }
}