<?php

use lib\Felta;

class ProductVariant
{

    private $sql;

    private $id;
    private $file;
    private $productVariant;

    private $createdAt;
    private $updatedAt;

    public function __construct($id, $file, $productVariant, $createdAt, $updatedAt)
    {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->file = $file;
        $this->productVariant = $productVariant;

        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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


    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
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
