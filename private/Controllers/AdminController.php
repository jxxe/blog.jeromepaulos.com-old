<?php

namespace Blog\Controllers;

use Blog\App\Database;
use Blog\App\RespondWith;
use DateTime;

class AdminController {

    public static function index(): void {
        $posts = Database::query('
            SELECT
                title,
                slug,
                date,
                published,
                (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comment_count
            FROM posts
            ORDER BY date DESC
        ');

        $posts = array_map(function($post) {
            $post['date'] = new DateTime($post['date']);
            return $post;
        }, $posts);

        $comments = Database::queryFirstField('SELECT COUNT(*) FROM comments');

        $posts_this_week = Database::queryFirstField('
            SELECT COUNT(*)
            FROM posts
            WHERE
                WEEK(date, 1) = WEEK(CURRENT_DATE, 1) AND
                published > 0
        ');

        view(
            'admin',
            posts: $posts,
            comments_all_time: $comments,
            posts_this_week: $posts_this_week
        );
    }

    public static function edit_post(string $slug, bool $error = false): void {
        $post = Database::queryFirstRow('SELECT * FROM posts WHERE slug = %s', $slug);
        view('edit', post: $post ?? [], error: $error);
    }

    public static function POST_save_post(string $slug): void {
        if(
            empty($_POST) ||
            !array_key_exists('title', $_POST) ||
            !array_key_exists('slug', $_POST) ||
            !array_key_exists('date', $_POST) ||
            !array_key_exists('status', $_POST) ||
            !array_key_exists('content', $_POST)
        ) {
            RespondWith::error_page(400);
        }

        if($_POST['status'] === 'delete') {
            Database::delete('comments', 'post_id = (SELECT id FROM posts WHERE slug = %s)', $slug);
            Database::delete('posts', 'slug = %s', $slug);
            RespondWith::redirect('/admin');
        } else {
            Database::insertUpdate('posts', [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'date' => strtotime($_POST['date']) ? $_POST['date'] : date('Y-m-d'),
                'tutorial' => (int) ($_POST['status'] === 'tutorial'),
                'published' => (int) ($_POST['status'] === 'published' || $_POST['status'] === 'tutorial'),
                'slug' => slugify($_POST['slug'])
            ]);

            if($slug === 'new') {
                RespondWith::redirect("/admin/{$_POST['slug']}");
            }
        }

        self::edit_post($slug);
    }

}