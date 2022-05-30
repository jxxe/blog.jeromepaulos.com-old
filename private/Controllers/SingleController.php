<?php

namespace Blog\Controllers;

use Blog\App\Database;
use Blog\App\CustomMarkdownParser;
use Blog\App\RespondWith;
use DateTime;

class SingleController {

    public static function index(string $slug): void {
        $post = Database::query('
            SELECT *
            FROM posts
            WHERE
                slug = :slug AND
                date <= DATE() AND
                published > 0
        ', ['slug' => $slug]);

        if(!$post) RespondWith::error_page(404);
        $post = $post[0];
        $post['date'] = new DateTime($post['date']);
        $parser = new CustomMarkdownParser();
        $post['content'] = $parser->parse($post['content']);

        $comments = Database::query('
            SELECT *
            FROM comments
            WHERE post_id = :post_id
            ORDER BY timestamp DESC
        ', ['post_id' => $post['id']]);

        view('single', post: $post, comments: $comments);
    }

    public static function POST_comment(string $slug): void {
        if(
            empty($_POST) ||

            !array_key_exists('name', $_POST) ||
            strlen($_POST['name']) > 100 ||

            !array_key_exists('content', $_POST) ||
            strlen($_POST['content']) > 1000 ||

            !array_key_exists('email', $_POST) ||
            strlen($_POST['email']) > 100 ||
            !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {
            RespondWith::error_page(400);
        }

        $parent_id = empty($_POST['parent_id']) ? null : $_POST['parent_id'];
        if(!is_numeric($parent_id)) $parent_id = null;

        Database::query('
            INSERT INTO comments (post_id, parent_id, name, email, content, timestamp)
            VALUES (
                (SELECT id FROM posts WHERE slug = :slug),
                :parent_id,
                :name,
                :email,
                :content,
                :timestamp
            )
        ', [
            'slug' => $slug,
            'parent_id' => $parent_id,
            'name' => strtolower(trim($_POST['name'])),
            'email' => $_POST['email'],
            'content' => $_POST['content'],
            'timestamp' => time()
        ]);

        self::index($slug);
    }

}