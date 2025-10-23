<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\EventSubscriber;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\PriceCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\Price;
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

            // Prüfe ob Preis & ID gesetzt wurden
            if (!isset($payload['price']) || !isset($payload['id'])) {
                file_put_contents(__DIR__ . '/../../offerta_debug.log',
                    "❌ Übersprungen - kein price oder id im Payload\n\n",
                    FILE_APPEND
                );
                continue;
            }

            $productId = $payload['id'];
            $priceData = $payload['price'];

            // 👉 Debuggen, was wirklich im price-Feld steht:
            file_put_contents(__DIR__ . '/../../offerta_debug.log',
                '👉 Typ von price: ' . gettype($priceData) . "\n" .
                '👉 Inhalt von price: ' . print_r($priceData, true) . "\n\n",
                FILE_APPEND
            );

            $grossPrice = null;

            // --- Preis ermitteln, je nach Struktur ---
            if ($priceData instanceof PriceCollection) {
                /** @var Price|null $firstPrice */
                $firstPrice = $priceData->first();
                if ($firstPrice !== null) {
                    $grossPrice = $firstPrice->getGross();
                }
            } elseif (is_string($priceData)) {
                $decoded = json_decode($priceData, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Währungs-ID kann variieren, daher ersten Eintrag nehmen
                    $first = reset($decoded);
                    $grossPrice = $first['gross'] ?? null;
                }
            } elseif (is_array($priceData)) {
                // Entweder Liste oder assoziatives Array mit currencyId-Keys
                if (isset($priceData[0]['gross'])) {
                    $grossPrice = $priceData[0]['gross'];
                } else {
                    $first = reset($priceData);
                    $grossPrice = $first['gross'] ?? null;
                }
            }

            // --- Wenn kein Preis ermittelt werden konnte ---
            if ($grossPrice === null) {
                file_put_contents(__DIR__ . '/../../offerta_debug.log',
                    "❌ Konnte keinen gross-Preis ermitteln\n\n",
                    FILE_APPEND
                );
                continue;
            }

            $grossPrice = (float) $grossPrice;

            file_put_contents(__DIR__ . '/../../offerta_debug.log',
                "✅ Speichere Preis: {$grossPrice} für Produkt: {$productId}\n\n",
                FILE_APPEND
            );

            // --- Preisänderung in DB speichern ---
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
