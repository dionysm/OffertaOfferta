<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1234567890CreatePriceHistoryTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1234567890; // Ändere auf aktuellen Timestamp, z.B. 1730000000
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `dio_offerta_price_history` (
                `id` BINARY(16) NOT NULL,
                `product_id` BINARY(16) NOT NULL,
                `price` DOUBLE NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_product_created` (`product_id`, `created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        // Nichts zu tun
    }
}