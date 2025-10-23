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
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPageLoaded'
        ];
    }

    public function onProductPageLoaded(ProductPageLoadedEvent $event): void
    {
        $product = $event->getPage()->getProduct();
        $lowestPrice = $this->lowestPriceService->getLowestPriceLast30Days($product->getId());

        if ($lowestPrice !== null) {
            $product->addExtension('offerta_lowest_price', new ArrayStruct([
                'value' => $lowestPrice
            ]));
        }
    }
}