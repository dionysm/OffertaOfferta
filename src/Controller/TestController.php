<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class ApiTestController extends AbstractController
{
    /**
     * @Since("6.7.3.1")
     * @Route("/api/offerta/test", name="api.offerta.test", methods={"GET"}, defaults={"auth_required"=false})
     */
    public function test(): JsonResponse
    {
        return new JsonResponse(['message' => 'API test route working!']);
    }
}