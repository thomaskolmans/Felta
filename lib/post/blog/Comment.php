<?php

namespace lib\post\blog;

use lib\Felta;
use \DateTime;

class Comment
{

    private $sql;

    private $id;
    private $parent;
    private $article;
    private $user;
    private $name;
    private $comment;
    private $accepted;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $parent,
        $article,
        $user = null,
        $name,
        $comment,
        $accepted = false,
        $createdAt,
        $updatedAt
    ) {
        $this->sql = Felta::getInstance()->getSQL();

        $this->id = $id;
        $this->parent = $parent;
        $this->article = $article;
        $this->user = $user;
        $this->name = $name;
        $this->comment = $comment;
        $this->accepted = $accepted;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function get($id)
    {
        $commentResult = Felta::getInstance()->getSQL()->select("*", "comment", ["id" => $id])[0];
        if ($commentResult == null) return null;
        return Comment::fromResult($commentResult);
    }

    public static function fromResult($result)
    {
        return new Comment(
            $result["id"],
            $result["parent"],
            $result["article"],
            $result["user"],
            $result["name"],
            $result["comment"],
            $result["accepted"],
            new DateTime($result["createdAt"]),
            new DateTime($result["updatedAt"])
        );
    }

    public function save()
    {
        if ($this->sql->exists("comment", ["id" => $this->id])) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function insert()
    {
        $this->sql->insert("article", [
            $this->id,
            $this->parent,
            $this->article,
            $this->user,
            $this->name,
            $this->comment,
            $this->accepted,
            $this->createdAt->format("Y-m-d H:i:s"),
            $this->updatedAt->format("Y-m-d H:i:s")
        ]);
    }

    public function update()
    {
        $this->sql->update("user", "article", ["id" => $this->id], $this->user);
        $this->sql->update("name", "article", ["id" => $this->id], $this->name);
        $this->sql->update("comment", "article", ["id" => $this->id], $this->comment);
        $this->sql->update("accepted", "article", ["id" => $this->id], $this->accepted);
        $this->sql->update("createdAt", "article", ["id" => $this->id], $this->createdAt->format("Y-m-d H:i:s"));
        $this->sql->update("updatedAt", "article", ["id" => $this->id], $this->updatedAt->format("Y-m-d H:i:s"));
    }

    public function delete()
    {
        $this->sql->delete("comment", ["id" => $this->id]);
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

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
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
