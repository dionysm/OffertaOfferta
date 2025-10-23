<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\EventSubscriber;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPriceChangeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_WRITTEN_EVENT => 'onProductWritten'
        ];
    }

    public function onProductWritten(EntityWrittenEvent $event): void
    {
        foreach ($event->getPayloads() as $payload) {
            // Prüfe ob Preis gesetzt wurde
            if (!isset($payload['price']) || !isset($payload['id'])) {
                continue;
            }

            $productId = $payload['id'];
            $priceData = $payload['price'];

            // Hole den Bruttopreis der ersten Currency (Standard)
            if (!is_array($priceData) || !isset($priceData[0]['gross'])) {
                continue;
            }

            $grossPrice = (float) $priceData[0]['gross'];

            // Speichere Preisänderung
            $this->connection->insert('dio_offerta_price_history', [
                'id' => Uuid::randomBytes(),
                'product_id' => Uuid::fromHexToBytes($productId),
                'price' => $grossPrice,
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s.v'),
            ]);
        }
    }
}