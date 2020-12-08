<?php

namespace lib\shop;

use \DateTime;

use lib\Felta;
use lib\shop\product\ProductVariant;
use lib\helpers\UUID;

class ShoppingCart
{

    private $sql;

    private $id;
    private $promotion;
    private $productVariants = [];

    private $createdAt;
    private $updatedAt;

    public function __construct($id, $promotion, $productVariants, $createdAt, $updatedAt)
    {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->promotion = $promotion;
        $this->productVariants = $productVariants;

        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromResult($result)
    {
        $productVariantResults = Felta::getInstance()->getSQL()->get("shop_cart_product_variant", ["shoppingCart" => $result["id"]]);
        $productVariants = [];
        foreach ($productVariantResults as $productVariantResult) {
            $productVariants[] = ShoppingCartProductVariant::fromResult($productVariantResult);
        }

        $shoppingCart = new ShoppingCart(
            $result["id"],
            Promotion::get($result["promotion"]),
            $productVariants,
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );

        return $shoppingCart;
    }

    public static function create()
    {
        return new ShoppingCart(UUID::generate(20), null, [], new DateTime(), new DateTime());
    }

    public static function exists($id)
    {
        return Felta::getInstance()->getSQL()->exists("shop_cart", ["id" => $id]);
    }

    public static function get($id)
    {
        $result = Felta::getInstance()->getSQL()->select("*", "shop_cart", ["id" => $id])[0];
        if ($result == null) return null;
        return ShoppingCart::fromResult($result);
    }

    public function insert()
    {
        $this->sql->insert("shop_cart", [
            $this->getId(),
            $this->getPromotion()->getId(),
            $this->getCreatedAt()->format("Y-m-d H:i:s"),
            $this->getUpdatedAt()->format("Y-m-d H:i:s")
        ]);

        foreach ($this->productVariants as $productVariant) {
            $productVariant->insert();
        }
    }

    public function set($productVariant, $quantity)
    {
        if (ProductVariant::exists($productVariant) && is_numeric($quantity)) {
            $this->productVariants[] = new ShoppingCartProductVariant(
                UUID::generate(15),
                $quantity,
                $this->getId(),
                ProductVariant::get($productVariant),
                new DateTime(),
                new DateTime()
            );
            $this->save();
        }
    }

    public function add($productVariant, $quantity)
    {
        if (ProductVariant::exists($productVariant) && is_numeric($quantity)) {
            $this->productVariants[] = new ShoppingCartProductVariant(
                UUID::generate(15),
                $quantity,
                $this->getId(),
                ProductVariant::get($productVariant),
                new DateTime(),
                new DateTime()
            );
            $this->save();
        }
    }

    public function update($productVariant, $quantity)
    {
        if (ProductVariant::exists($productVariant) && is_numeric($quantity)) {
            $this->productVariants[$productVariant] = $quantity;
            $this->save($productVariant, $quantity);
        }
    }

    public function delete($productVariant)
    {
        $productVariant->delete();
    }

    public function destroy()
    {
        $this->sql->delete("shop_cart", ["id" => $this->id]);
    }

    public function getSubTotal()
    {
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        if (boolval($settings["exclbtw"])) {
            foreach ($this->productVariants as $item => $quantity) {
                $itemv = ProductVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
            if (boolval($settings["shipping"]) && !boolval($settings["freeshipping"])) {
                $amount += $this->getShippingCost();
            }
        } else {
            foreach ($this->productVariants as $item => $quantity) {
                $itemv = ProductVariant::get($item);
                $amount += intval($itemv->getPrice()) * $quantity;
            }
            if (boolval($settings["shipping"]) && !boolval($settings["freeshipping"])) {
                $amount += $this->getShippingCost();
            }
            $amount -= $this->getBtw($amount, true);
        }

        return $amount;
    }

    public function getTotalAmount()
    {
        $amount = 0;
        $settings = Shop::getInstance()->getSettings();
        foreach ($this->productVariants as $item => $quantity) {
            $itemv = ProductVariant::get($item);
            $amount += intval($itemv->getPrice()) * $quantity;
        }
        if (boolval($settings["shipping"]) && !boolval($settings["freeshipping"])) {
            $amount += $this->getShippingCost();
        }
        if (boolval($settings["exclbtw"])) {
            $amount += $this->getBtw($amount, true);
        }
        return $amount;
    }

    public function getBtw($amount, $excl = false)
    {
        $exclBtw = boolval(Shop::getInstance()->getSettings()["exclbtw"]);
        if ($excl || !$exclBtw) {
            return $amount - Shop::doubleToInt(round(Shop::intToDouble($amount) / ((Shop::getInstance()->getSettings()["btw"] / 100) + 1), 2));
        } else {
            return Shop::doubleToInt(round(Shop::intToDouble($amount) * (Shop::getInstance()->getSettings()["btw"] / 100), 2));
        }
    }

    public function getShippingCost()
    {
        $productVariants = count($this->productVariants);
        $settings = Shop::getInstance()->getShipping();
        $price = $settings["amount"];
        $ipp = $settings["ipp"];

        $amount = $price;
        $counter = 0;
        foreach ($this->productVariants as $item => $quantity) {
            $counter += $quantity;
            if ($counter > $ipp) {
                $amount += $price;
                $counter -= $ipp;
            }
        }
        return $amount;
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

    public function getPromotion()
    {
        return $this->promotion;
    }


    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
        return $this;
    }


    public function getproductVariants()
    {
        return $this->productVariants;
    }


    public function setproductVariants($productVariants)
    {
        $this->productVariants = $productVariants;
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

