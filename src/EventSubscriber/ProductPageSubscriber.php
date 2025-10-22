<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\EventSubscriber;

use Dio\OffertaOfferta\Service\LowestPriceService;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPageSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LowestPriceService $lowestPriceService
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded',
        ];
    }

    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        $product = $event->getPage()->getProduct();

        $lowestPrice = $this->lowestPriceService->getLowestPriceLast30Days($product->getId());

        $product->addExtension('offerta_lowest_price', new \Shopware\Core\Framework\Struct\Struct([
            'value' => $lowestPrice,
        ]));
    }
}
