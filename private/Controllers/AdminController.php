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

        $comments = Database::query('
            SELECT COUNT(*) AS count
            FROM comments        
        ')[0]['count'];

        $posts_this_week = Database::query('
            SELECT COUNT(*) AS count
            FROM posts
            WHERE STRFTIME(\'%W\', date) = STRFTIME(\'%W\', DATE()) 
        ')[0]['count'];

        view(
            'admin',
            posts: $posts,
            comments_all_time: $comments,
            posts_this_week: $posts_this_week
        );
    }

    public static function edit_post(string $slug, bool $error = false): void {
        $post = Database::query('
            SELECT *
            FROM posts
            WHERE slug = :slug
        ', [
            'slug' => $slug
        ]);

        view('edit', post: $post[0] ?? [], error: $error);
    }

    public static function POST_save_post(string $slug): void {
        if(
            empty($_POST) ||
            !array_key_exists('title', $_POST) ||
            !array_key_exists('date', $_POST) ||
            !array_key_exists('status', $_POST) ||
            !array_key_exists('content', $_POST)
        ) {
            RespondWith::error_page(400);
        }

        if($_POST['status'] === 'delete') {
            Database::query(
                'DELETE FROM comments WHERE post_id = (SELECT id FROM posts WHERE slug = :slug)',
                ['slug' => $slug]
            );

            Database::query(
                'DELETE FROM posts WHERE slug = :slug',
                ['slug' => $slug]
            );

            RespondWith::redirect('/admin');
        } else {
            $exists = Database::query('SELECT 1 FROM posts WHERE slug = :slug', ['slug' => $slug]);

            if($exists) {
                Database::query('
                    UPDATE posts
                    SET title = :title, content = :content, date = :date, tutorial = :tutorial, published = :published
                    WHERE slug = :slug
                ', [
                    'title' => substr($_POST['title'], 0, 255),
                    'content' => $_POST['content'],
                    'date' => strtotime($_POST['date']) ? $_POST['date'] : date('Y-m-d'),
                    'tutorial' => (int) ($_POST['status'] === 'tutorial'),
                    'published' => (int) ($_POST['status'] === 'published' || $_POST['status'] === 'tutorial'),
                    'slug' => $slug
                ]);
            } else {
                Database::query('
                    INSERT INTO posts (title, slug, content, date, tutorial, published)
                    VALUES (:title, :slug, :content, :date, :tutorial, :published)
                ', [
                    'title' => substr($_POST['title'], 0, 255),
                    'slug' => $slug = slugify($_POST['slug'] ?? $_POST['title']),
                    'content' => $_POST['content'],
                    'date' => strtotime($_POST['date']) ? $_POST['date'] : date('Y-m-d'),
                    'tutorial' => (int) ($_POST['status'] === 'tutorial'),
                    'published' => (int) ($_POST['status'] === 'published' || $_POST['status'] === 'tutorial')
                ]);

                RespondWith::redirect("/admin/$slug");
            }
        }

        self::edit_post($slug);
    }

}