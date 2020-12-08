<?php

namespace lib\post\blog;

use lib\Felta;
use \DateTime;

class Like
{

    private $sql;

    private $id;
    private $parent;
    private $namespace;
    private $user;
    private $name;
    private $type;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $parent,
        $namespace,
        $user = null,
        $type,
        $createdAt,
        $updatedAt
    ) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->parent = $parent;
        $this->namespace = $namespace;
        $this->user = $user;
        $this->type = $type;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function get($id)
    {
        $likeResult = Felta::getInstance()->getSQL()->select("*", "like", ["id" => $id])[0];
        if ($likeResult == null) return null;
        return Like::fromResult($likeResult);
    }

    public static function fromResult($result)
    {
        return new Like(
            $result["id"],
            $result["parent"],
            $result["namespace"],
            $result["user"],
            $result["type"],
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );
    }

    public function save()
    {
        if ($this->sql->exists("type", ["id" => $this->id])) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function insert()
    {
        $this->sql->insert("`like`", [
            $this->id,
            $this->parent,
            $this->namespace,
            $this->user,
            $this->name,
            $this->type,
            $this->createdAt->format("Y-m-d H:i:s"),
            $this->updatedAt->format("Y-m-d H:i:s")
        ]);
    }

    public function update()
    {
        $this->sql->update("user", "`like`", ["id" => $this->id], $this->user);
        $this->sql->update("name", "`like`", ["id" => $this->id], $this->name);
        $this->sql->update("type", "`like`", ["id" => $this->id], $this->type);
        $this->sql->update("createdAt", "`like`", ["id" => $this->id], $this->createdAt->format("Y-m-d H:i:s"));
        $this->sql->update("updatedAt", "`like`", ["id" => $this->id], $this->updatedAt->format("Y-m-d H:i:s"));
    }

    public function delete()
    {
        $this->sql->delete("type", ["id" => $this->id]);
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

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
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
