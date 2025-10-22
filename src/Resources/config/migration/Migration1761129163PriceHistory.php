<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1698000000PriceHistory extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1761129163;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS dio_offerta_price_history (
                id BINARY(16) NOT NULL,
                product_id BINARY(16) NOT NULL,
                price DOUBLE PRECISION NOT NULL,
                created_at DATETIME(3) NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT `fk.dio_offerta_price_history.product_id` FOREIGN KEY (product_id)
                    REFERENCES product (id) ON DELETE CASCADE
            )
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // Destructive changes like DROP TABLE
    }
}
