<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                        add(PriceHistoryEntity $entity)
 * @method void                        set(string $key, PriceHistoryEntity $entity)
 * @method PriceHistoryEntity[]       getIterator()
 * @method PriceHistoryEntity[]       getElements()
 * @method PriceHistoryEntity|null    get(string $key)
 * @method PriceHistoryEntity|null    first()
 * @method PriceHistoryEntity|null    last()
 */
class PriceHistoryCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PriceHistoryEntity::class;
    }
}
