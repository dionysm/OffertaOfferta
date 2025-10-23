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
            'product.written' => 'onProductWritten'
        ];
    }

    public function onProductWritten(EntityWrittenEvent $event): void
    {
        // DEBUG: Wird der Subscriber überhaupt aufgerufen?
        file_put_contents(__DIR__ . '/../../offerta_debug.log',
            date('Y-m-d H:i:s') . " - Subscriber wurde aufgerufen!\n",
            FILE_APPEND
        );

        foreach ($event->getPayloads() as $payload) {
            // DEBUG: Was kommt im Payload an?
            file_put_contents(__DIR__ . '/../../offerta_debug.log',
                'Payload: ' . json_encode($payload, JSON_PRETTY_PRINT) . "\n\n",
                FILE_APPEND
            );

            // Prüfe ob Preis gesetzt wurde
            if (!isset($payload['price']) || !isset($payload['id'])) {
                file_put_contents(__DIR__ . '/../../offerta_debug.log',
                    "❌ Übersprungen - kein price oder id im Payload\n\n",
                    FILE_APPEND
                );
                continue;
            }

            $productId = $payload['id'];
            $priceData = $payload['price'];

            // Hole den Bruttopreis der ersten Currency (Standard)
            if (!is_array($priceData) || !isset($priceData[0]['gross'])) {
                file_put_contents(__DIR__ . '/../../offerta_debug.log',
                    "❌ Übersprungen - Preis-Struktur falsch\n\n",
                    FILE_APPEND
                );
                continue;
            }

            $grossPrice = (float) $priceData[0]['gross'];

            file_put_contents(__DIR__ . '/../../offerta_debug.log',
                "✅ Speichere Preis: {$grossPrice} für Produkt: {$productId}\n\n",
                FILE_APPEND
            );

            // Speichere Preisänderung
            try {
                $this->connection->insert('dio_offerta_price_history', [
                    'id' => Uuid::randomBytes(),
                    'product_id' => Uuid::fromHexToBytes($productId),
                    'price' => $grossPrice,
                    'created_at' => (new \DateTime())->format('Y-m-d H:i:s.v'),
                ]);

                file_put_contents(__DIR__ . '/../../offerta_debug.log',
                    "✅ Erfolgreich in DB gespeichert!\n\n",
                    FILE_APPEND
                );
            } catch (\Exception $e) {
                file_put_contents(__DIR__ . '/../../offerta_debug.log',
                    "❌ FEHLER beim Speichern: " . $e->getMessage() . "\n\n",
                    FILE_APPEND
                );
            }
        }
    }
}