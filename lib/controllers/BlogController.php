<?php
namespace lib\controllers;

use lib\Felta;
use lib\Helpers\Input;
use lib\Helpers\UUID;
use lib\Post\blog\Blog;
use lib\Post\blog\Article;
use lib\Post\blog\Comment;

use \DateTime;

class BlogController {

    /**
     * Blog
     */

    public static function GET_ALL_BLOGS(){
        $blogs = Blog::getAll();
        $exposedBlogs = [];
        foreach($blogs as $blog) {
            $exposedBlogs[] = $blog->expose();
        }

        echo json_encode(["success" => true, "blogs" => $exposedBlogs]);
    }

    public static function GET_ALL_ACTIVE_BLOGS(){
        $blogs = Blog::getAllActive();
        $exposedBlogs = [];
        foreach($blogs as $blog) {
            $exposedBlogs[] = $blog->expose();
        }

        echo json_encode(["success" => true, "blogs" => $exposedBlogs]);    
    }

    public static function GET_BLOG($id){
        echo json_encode(["success" => true, "blog" => Blog::get($id)->expose()]);
    }

    public static function CREATE_BLOG(){
        $blog = new Blog(
            UUID::generate(15),
            Input::clean("name"),
            Input::clean("description"),
            Input::clean("active"),
            Input::clean("order"),
            new DateTime(),
            new DateTime()
        );

        $blog->save();

        echo json_encode(["success" => true, "message" => "Blog has been created!", "blog" => $blog->expose()]);
    }
    
    public static function UPDATE_BLOG(){
        $blog = Blog::get(Input::clean("id"));
        $blog->setName(Input::clean("name"));
        $blog->setDescription(Input::clean("description"));
        $blog->setActive(Input::clean("active"));
        $blog->setOrder(Input::clean("order"));
        $blog->setUpdatedAt(new DateTime());

        $blog->save();

        echo json_encode(["success" => true, "message" => "Blog has been updated!"]);

    }

    public static function DELETE_BLOG($id){
        $blog = Blog::get($id);
        if ($blog == null) {
            echo json_encode(["success" => false, "message" => "Blog does not exist."]);
        } else {
            $blog->delete();
            echo json_encode(["success" => true, "message" => "Blog has been deleted!"]);
        }
    }

    /**
     * Article
     */

    public static function GET_ARTICLE($id){
        echo json_encode(["success" => true, "blog" => Article::get($id)]);
    }

    public static function GET_ARTICLES_FROM_BLOG($blog, $from, $to){
        $articles = Article::allFromBlog($blog, $from, $to);
        $exposedArticles = [];
        foreach($articles as $article) {
            $exposedArticles[] = $article->expose(); 
        }

        echo json_encode(["success" => true, "articles" => $exposedArticles]);
    }

    public static function CREATE_ARTICLE(){
        $article = new Article(
            UUID::generate(15),
            Input::clean("blog"),
            Input::clean("title"),
            Input::clean("author"),
            Input::clean("description"),
            [],
            Input::clean("body"),
            Input::clean("active") == "true" ? true : false,
            new DateTime(Input::clean("activeFrom")),
            new DateTime(),
            new DateTime()
        );
        
        $article->save();

        echo json_encode(["success" => true, "message" => "Article has been created!", "article" => $article->expose()]);
    }
    
    public static function UPDATE_ARTICLE(){
        $article = Article::get(Input::clean("id"));
        $article->setTitle(Input::clean("title"));
        $article->setDescription(Input::clean("description"));
        $article->setBody(Input::clean("body"));
        $article->setActive(Input::clean("active"));
        $article->setActiveFrom(new DateTime(Input::clean("activeFrom")));

        $article->save();
        
        echo json_encode(["success" => true, "message" => "Article has been created!"]);
    }

    public static function DELETE_ARTICLE($id){
        $article = Article::get($id);
        $article->delete();

        echo json_encode(["success" => true, "message" => "Article has been deleted!"]);
    }


    /**
     * Comment
     */
    public static function CREATE_ARTICLE_COMMENT(){
        $comment = new Comment(
            UUID::generate(15),
            isEmpty(Input::clean("parent")) ? null : Input::clean("parent"),
            isEmpty(Input::clean("article")) ? null : Input::clean("article"),
            isEmpty(Input::clean("user")) ? null : Input::clean("user"),
            Input::clean("name"),
            Input::clean("comment"),
            Input::clean("accepted"),
            new DateTime(),
            new DateTime()
        );

        $comment->save();

        echo json_encode(["success" => true, "message" => "Comment has been created!"]);
    }
    
    public static function UPDATE_ARTICLE_COMMENT(){
        $comment = Comment::get(Input::clean("id"));

        $comment->setName(Input::clean("name"));
        $comment->setComment(Input::clean("comment"));
        $comment->setUpdatedAt(new DateTime());

        $comment->save();

        echo json_encode(["success" => true, "message" => "Comment has been updated!"]);

    }

    public static function DELETE_ARTICLE_COMMENT($id){
        $comment = Comment::get($id);
        $comment->delete();

        echo json_encode(["success" => true, "message" => "Comment has been deleted!"]);
    }
    
}
