<?php

use Blog\App\Paths;

/**
 * Render a specific PHP view
 * @param string $_name The path of the view, excluding extension and preceding directories
 * @param mixed ...$_args Use named parameters to pass arguments to the view
 * @return void
 */
function view(string $_name, mixed ...$_args): void {
    extract($_args);
    require Paths::PRIVATE . '/views/' . $_name . '.php';
}

/**
 * Add an s if the number requires it
 * @param int $n
 * @return string
 */
function s(int $n): string {
    return $n != 1 ? 's' : '';
}

function slugify(string $text): string {
    $slug = strtolower($text);
    $slug = preg_replace('/[^\da-z- ]/', '-', $slug);
    $slug = str_replace(' ', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return $slug;
}

function get_url(): string {
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = 'https://';
    } else {
        $url = 'http://';
    }

    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];

    return $url;
}