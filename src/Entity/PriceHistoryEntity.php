<?php declare(strict_types=1);

namespace Dio\OffertaOfferta\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Content\Product\ProductEntity;

class PriceHistoryEntity extends Entity
{
    protected string $productId;
    protected float $price;
    protected \DateTimeInterface $createdAt;

    protected ?ProductEntity $product = null;

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
    }
}
