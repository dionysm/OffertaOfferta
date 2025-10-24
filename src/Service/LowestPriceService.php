<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;

class LowestPriceService
{
    private const TABLE = 'dio_offerta_price_history';

    public function __construct(
        private readonly Connection $connection
    ) {
        error_log('OffertaOfferta: LowestPriceService Constructor wurde aufgerufen!');
    }

    public function getLowestPriceLast30Days(string $productId): ?float
    {
        error_log('OffertaOfferta: getLowestPriceLast30Days wurde aufgerufen für: ' . $productId);

        $sql = '
            SELECT MIN(price) as lowest_price
            FROM ' . self::TABLE . '
            WHERE product_id = :productId
              AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ';

        $result = $this->connection->fetchOne($sql, [
            'productId' => Uuid::fromHexToBytes($productId),
        ]);

        error_log('OffertaOfferta: SQL Result: ' . var_export($result, true));

        return $result !== false ? (float) $result : null;
    }
}