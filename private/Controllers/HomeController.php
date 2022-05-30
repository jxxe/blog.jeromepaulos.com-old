<?php

namespace Blog\Controllers;

use Blog\App\Database;
use DateTime;

class HomeController {

    public static function index(): void {
        /*$posts = Database2::query('
            SELECT title, slug, date, tutorial
            FROM posts
            WHERE
                date <= DATE() AND
                published > 0
            ORDER BY date DESC
        ');*/

        $posts = Database::query('
            SELECT title, slug, date, tutorial
            FROM posts
            WHERE date <= CURRENT_DATE AND published > 0
            ORDER BY date DESC
        ');

        // Convert date strings to DateTime objects
        $posts = array_map(function($post) {
            $post['date'] = new DateTime($post['date']);
            return $post;
        }, $posts);

        // Calculate streak before tutorial posts are removed
        $streak = self::calculate_streak($posts);

        // Remove tutorial posts
        $posts = array_filter($posts, fn($post) => !$post['tutorial']);

        // Show only the first four posts
        $posts = array_slice($posts, 0, 4);

        view('home', posts: $posts, streak: $streak);
    }

    /*

    is the most recent post in the curernt week?
        yes -> continue
        no -> streak = 0

    foreach post
        if current week is the same
            yes -> increment current week count
            no -> if current week count is 2+
                yes -> change current week and increment streak
                no -> break streak

    */
    public static function calculate_streak(array $posts): int {
        $posts = array_filter($posts ?? [], fn($post) => $post['published'] ?? true);
        $posts = array_values($posts);

        $streak = 0;
        $current_week = date('Y-W'); // W = current week number, starting on Monday
        $current_week_count = 0;

        if(empty($posts) || $posts[0]['date']->format('Y-W') !== $current_week) return 0;

        foreach($posts as $post) {
            $week = $post['date']->format('Y-W');

            if($week === $current_week) {
                $current_week_count++;
            } else {
                if($current_week_count >= $_ENV['POSTS_PER_WEEK']) {
                    $streak++;
                    $current_week = $week;
                } else if($current_week === date('Y-W')) {
                    return $streak;
                } else {
                    return $streak;
                }
            }
        }

        return $streak;
    }

}