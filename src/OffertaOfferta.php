<?php declare(strict_types=1);

namespace Dio\OffertaOfferta;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class OffertaOfferta extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }

        // Tabelle beim Deinstallieren löschen
        $this->connection->executeStatement('DROP TABLE IF EXISTS `dio_offerta_price_history`');
    }
}