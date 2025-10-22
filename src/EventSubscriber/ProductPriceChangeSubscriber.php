<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\EventSubscriber;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\Event\ProductWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPriceChangeSubscriber implements EventSubscriberInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductWrittenEvent::class => 'onProductWritten'
        ];
    }

    public function onProductWritten(ProductWrittenEvent $event): void
    {
        foreach ($event->getWriteResults() as $writeResult) {
            $payload = $writeResult->getPayload();

            if (!isset($payload['price']) || !isset($payload['id'])) {
                continue; // Preis wurde nicht verändert oder ID fehlt
            }

            $productId = $payload['id'];
            $priceData = $payload['price'];

            // Wir holen den "default" Preis in EUR
            $defaultPrice = $priceData[0]['gross'] ?? null;

            if (!$defaultPrice) {
                continue;
            }

            $this->connection->insert('dio_offerta_price_history', [
                'id' => Uuid::randomBytes(),
                'product_id' => Uuid::fromHexToBytes($productId),
                'price' => $defaultPrice,
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s.v'),
            ]);
        }
    }
}
