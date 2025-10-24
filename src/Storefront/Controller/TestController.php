<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Storefront\Controller;

use Dio\OffertaOfferta\Service\LowestPriceService;
use Shopware\Core\Framework\Context;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Custom test controller for OffertaOfferta
 *
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class TestController extends StorefrontController
{
    private LowestPriceService $lowestPriceService;

    public function __construct(LowestPriceService $lowestPriceService)
    {
        $this->lowestPriceService = $lowestPriceService;
    }

    /**
     * Simple test route — call via: http://localhost/offerta/test
     */
    #[Route(path: '/offerta/test', name: 'frontend.offerta.test', methods: ['GET'])]
    public function test(Context $context): JsonResponse
    {
        error_log('OffertaOfferta: Test Controller wurde aufgerufen!');

        // Beispiel-Product-ID (ändern auf echte UUID)
        $testProductId = '018e4b5f4d7270e8a8c6f6f8b8f8b8f8';

        // Aufruf deines Services
        $lowestPrice = $this->lowestPriceService->getLowestPriceLast30Days($testProductId);

        return new JsonResponse([
            'success' => true,
            'lowestPrice' => $lowestPrice,
            'message' => 'Route funktioniert! Check var/log/dev.log für Debug-Ausgabe.'
        ]);
    }
}
