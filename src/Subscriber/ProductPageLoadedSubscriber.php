<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Subscriber;

use Dio\OffertaOfferta\Service\LowestPriceService;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPageLoadedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LowestPriceService $lowestPriceService
    ) {
        error_log('OffertaOfferta: ProductPageLoadedSubscriber Constructor wurde aufgerufen!');
    }

    public static function getSubscribedEvents(): array
    {
        error_log('OffertaOfferta: getSubscribedEvents wurde aufgerufen!');

        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded'
        ];
    }

    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        error_log('OffertaOfferta: onProductPageLoaded wurde aufgerufen!');

        $product = $event->getPage()->getProduct();
        $productId = $product->getId();

        error_log('OffertaOfferta: Product ID: ' . $productId);

        $lowestPrice = $this->lowestPriceService->getLowestPriceLast30Days($productId);

        error_log('OffertaOfferta: Lowest Price: ' . var_export($lowestPrice, true));

        if ($lowestPrice !== null) {
            $product->addExtension('offerta_lowest_price', new ArrayStruct([
                'value' => $lowestPrice
            ]));

            error_log('OffertaOfferta: Extension wurde gesetzt!');
        } else {
            error_log('OffertaOfferta: Kein niedrigster Preis gefunden!');
        }
    }
}