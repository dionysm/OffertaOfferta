<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Content\Product\ProductDefinition;

class PriceHistoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'dio_offerta_price_history';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return PriceHistoryCollection::class;
    }

    public function getEntityClass(): string
    {
        return PriceHistoryEntity::class;
    }

    public function defineFields(): FieldCollection
    {
        return new FieldCollection([
            new IdField('id', 'id'),
            new FkField('product_id', 'productId', ProductDefinition::class),
            new FloatField('price', 'price'),
            new DateTimeField('created_at', 'createdAt'),

            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false),
        ]);
    }
}
