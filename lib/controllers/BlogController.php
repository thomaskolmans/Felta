<?php
namespace lib\controllers;

use lib\Felta;
use lib\helpers\Input;
use lib\helpers\UUID;
use lib\post\blog\Blog;
use lib\post\blog\Article;
use lib\post\blog\ArticleImage;
use lib\post\blog\Comment;

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
            Input::value("description"),
            Input::clean("active"),
            Input::clean("order"),
            new DateTime(),
            new DateTime()
        );

        $blog->save();

        echo json_encode(["success" => true, "message" => "Blog has been created!", "blog" => $blog->expose()]);
    }
    
    public static function UPDATE_BLOG(){
        parse_str(file_get_contents("php://input"),$_POST);

        $blog = Blog::get(Input::value("id"));
        if ($blog == null) {
            echo json_encode(["success" => false, "message" => "Blog does not exist."]);
        } else {
            $blog->setName(Input::clean("name"));
            $blog->setDescription(Input::value("description"));
            $blog->setActive(Input::clean("active") === "on");
            $blog->setOrder(Input::clean("order"));
            $blog->setUpdatedAt(new DateTime());
    
            $blog->save();
    
            echo json_encode(["success" => true, "message" => "Blog has been updated!"]);
        }
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
        echo json_encode(["success" => true, "article" => Article::get($id)->expose()]);
    }

    public static function GET_ARTICLES_FROM_BLOG($blog, $page = 1){
        $articles = Article::allFromBlog($blog, ($page * 20) - 20, ($page * 20));
        $exposedArticles = [];
        foreach($articles as $article) {
            $exposedArticles[] = $article->expose(); 
        }

        echo json_encode(["success" => true, "articles" => $exposedArticles]);
    }

    public static function CREATE_ARTICLE(){
        $imageUrls = array_filter(json_decode(isset($_POST["images"]) ? $_POST["images"] : "[]"));
        $images = [];
        foreach($imageUrls as $url) {
            $images[] = new ArticleImage(
                UUID::generate(10),
                Input::clean("id"),
                $url,
                "",
                new DateTime()
            );
        }

        $article = new Article(
            UUID::generate(15),
            Input::clean("blog"),
            Input::clean("title"),
            Input::clean("author"),
            Input::value("description"),
            $images,
            Input::value("body"),
            Input::clean("active") === "on",
            new DateTime(Input::clean("activeFrom")),
            new DateTime(),
            new DateTime()
        );
        
        $article->save();

        echo json_encode(["success" => true, "message" => "Article has been created!", "article" => $article->expose()]);
    }
    
    public static function UPDATE_ARTICLE(){
        parse_str(file_get_contents("php://input"),$_POST);

        $imageUrls = array_filter(json_decode(isset($_POST["images"]) ? $_POST["images"] : "[]"));
        $images = [];
        foreach($imageUrls as $url) {
            $images[] = new ArticleImage(
                UUID::generate(10),
                Input::clean("id"),
                $url,
                "",
                new DateTime()
            );
        }

        $article = Article::get(Input::value("id"));

        $article->setTitle(Input::clean("title"));
        $article->setAuthor(Input::clean("author"));
        $article->setDescription(Input::value("description"));
        $article->setBody(Input::value("body"));
        $article->setActive(Input::clean("active") === "on");
        $article->setActiveFrom(new DateTime(Input::clean("activeFrom")));
        $article->setImages($images);

        $article->save();
        
        echo json_encode(["success" => true, "message" => "Article has been updated!", "article" => $article->expose()]);
    }

    public static function DELETE_ARTICLE($id){
        $article = Article::get($id);
        if ($article == null) {
            echo json_encode(["success" => false, "message" => "Article does not exist."]);
        } else {
            $article->delete();
            echo json_encode(["success" => true, "message" => "Article has been deleted!"]);
        }
    }

    /**
     * Comment
     */
    public static function CREATE_ARTICLE_COMMENT(){
        $comment = new Comment(
            UUID::generate(15),
            empty(Input::clean("parent")) ? null : Input::clean("parent"),
            empty(Input::clean("article")) ? null : Input::clean("article"),
            empty(Input::clean("user")) ? null : Input::clean("user"),
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
