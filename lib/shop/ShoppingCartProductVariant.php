<?php

namespace lib\shop;

use \DateTime;
use lib\Felta;
use lib\shop\product\ProductVariant;

class ShoppingCartProductVariant
{

    private $sql;

    private $id;
    private $quantity;
    private $shoppingCart;
    private $productVariant;


    private $createdAt;
    private $updatedAt;

    public function __constructor($id, $quantity, $shoppingCart, $productVariant, $createdAt, $updatedAt)
    {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->quantity = $quantity;
        $this->shoppingCart = $shoppingCart;
        $this->productVariant = $productVariant;

        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromResult($result)
    {
        return new ShoppingCartProductVariant(
            $result["id"],
            $result["quantity"],
            $result["shoppingCart"],
            ProductVariant::get($result["product_variant"]),
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );
    }

    public function insert()
    {
        $this->sql->insert("shop_cart_product_variant", [
            $this->getId(),
            $this->getQuantity(),
            $this->getShoppingCart(),
            $this->getProductVariant()->getId(),
            $this->getCreatedAt()->format("Y-m-d H:i:s"),
            $this->getUpdatedAt()->format("Y-m-d H:i:s")
        ]);
    }

    public function update() {
        $this->sql->update("quantity", "shop_cart_product_variant", ["id" => $this->getId()], $this->getQuantity());
        $this->sql->update("product_variant", "shop_cart_product_variant", ["id" => $this->getId()], $this->getProductVariant()->getId());
        $this->sql->update("updatedAt", "shop_cart_product_variant", ["id" => $this->getId()], $this->getUpdatedAt()->format("Y-m-d H:i:s"));
    }

    public function delete() {
        $this->sql->delete("shop_cart_product_variant", ["product_variant" => $this->getId()]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }


    public function getShoppingCart()
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart($shoppingCart)
    {
        $this->shoppingCart = $shoppingCart;
        return $this;
    }


    public function getProductVariant()
    {
        return $this->productVariant;
    }

    public function setProductVariant($productVariant)
    {
        $this->productVariant = $productVariant;
        return $this;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
