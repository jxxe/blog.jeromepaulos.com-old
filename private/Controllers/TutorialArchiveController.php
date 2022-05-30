<?php

namespace Blog\Controllers;

use Blog\App\Database;
use DateTime;

class TutorialArchiveController {

    public static function index(): void {
        $posts = Database::query('
            SELECT title, slug, date
            FROM posts
            WHERE
                date <= CURRENT_DATE AND
                published > 0 AND
                tutorial > 0
            ORDER BY date DESC
        ');

        $posts = array_map(function($post) {
            $post['date'] = new DateTime($post['date']);
            return $post;
        }, $posts);

        view('archive', title: 'Tutorials', posts: $posts);
    }

}