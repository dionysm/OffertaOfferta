<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Core\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1698000000PriceHistory extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1698000000;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement("
            CREATE TABLE IF NOT EXISTS `dio_offerta_price_history` (
                `id` BINARY(16) NOT NULL,
                `product_id` BINARY(16) NOT NULL,
                `price` DOUBLE NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                PRIMARY KEY (`id`)
            )
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // Leave empty unless you want to drop or destructively change tables
    }
}
