<?php

namespace lib\shop;

use lib\Felta;
use \DateTime;

class Promotion
{

    private $sql;

    private $id;
    private $name;
    private $code;
    private $percentage;
    private $amount;

    private $startsAt;
    private $endsAt;

    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $name,
        $code,
        $percentage,
        $amount,
        $startsAt,
        $endsAt,
        $createdAt,
        $updatedAt
    ) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->percentage = $percentage;
        $this->amount = $amount;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromResult($result)
    {
        return new Promotion(
            $result["id"],
            $result["name"],
            $result["code"],
            $result["percentage"],
            $result["amount"],
            new DateTime($result["startsAt"]),
            new DateTime($result["endsAt"]),
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );
    }

    public static function all($from = 0, $amount = 20)
    {
        $results = Felta::getInstance()->getSQL()->query()->select()->from("promotion")->limit($from, $amount)->execute();
        $promotions = [];
        foreach ($results as $result) {
            $promotions[] = Promotion::fromResult($result);
        }
        return $promotions;
    }

    public static function get($id)
    {
        $result = Felta::getInstance()->getSQL()->select("*", "promotion", ["id" => $id])[0];
        if ($result == null) return null;
        return Promotion::fromResult($result);
    }

    public static function exists($id)
    {
        return Felta::getInstance()->getSQL()->exists("promotion", ["id" => $id]);
    }



    public function save()
    {
        $this->sql->insert("promotion", [
            $this->id,
            $this->name,
            $this->code,
            $this->percentage,
            $this->amount,
            $this->startsAt->format("Y-m-d H:i:s"),
            $this->endsAt->format("Y-m-d H:i:s"),
            $this->createdAt->format("Y-m-d H:i:s"),
            $this->updatedAt->format("Y-m-d H:i:s"),
        ]);
    }

    public function update()
    {
        $this->sql->update("name", "promotion", ["id" => $this->id], $this->name);
        $this->sql->update("code", "promotion", ["id" => $this->id], $this->code);
        $this->sql->update("percentage", "promotion", ["id" => $this->id], $this->percentage);
        $this->sql->update("amount", "promotion", ["id" => $this->id], $this->amount);
        $this->sql->update("startsAt", "promotion", ["id" => $this->id], $this->startsAt);
        $this->sql->update("endsAt", "promotion", ["id" => $this->id], $this->endsAt);
        $this->sql->update("createdAt", "promotion", ["id" => $this->id], $this->createdAt->format("Y-m-d H:i:s"));
        $this->sql->update("updatedAt", "promotion", ["id" => $this->id], $this->updatedAt->format("Y-m-d H:i:s"));
    }

    public function delete()
    {
        $this->sql->delete("promotion", ["id" => $this->id]);
        foreach ($this->variants as $variant) {
            $variant->delete();
        }
    }

    public function expose()
    {
        $exposed = get_object_vars($this);
        unset($exposed["sql"]);
        return $exposed;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getStartsAt()
    {
        return $this->startsAt;
    }

    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;
        return $this;
    }

    public function getEndsAt()
    {
        return $this->endsAt;
    }

    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;
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
