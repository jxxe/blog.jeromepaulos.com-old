<?php /**
 * @var string $title
 * @var string $body
 */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(empty($title) ? '' : "$title — ") ?><?= $_ENV['SITE_TITLE'] ?></title>
    <link rel="icon" href="https://emojicdn.elk.sh/duck">
    <link rel="stylesheet" href="/dist/style.css">
</head>
<body class="<?= $body ?? '' ?>">

<header class="site-header">
    <a class="site-title" href="/"><?= $_ENV['SITE_TITLE'] ?></a>

    <?php if($_SESSION['logged_in'] ?? false): ?>
        <div class="buttons">
            <a href="/logout" class="button">Sign out</a>

            <?php if($_SERVER['REQUEST_URI'] === '/admin'): ?>
                <a class="button primary" href="/admin/new">New post</a>
            <?php else: ?>
                <a class="button primary" href="/admin">Edit site</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <form action="https://google.com/search" target="_blank">
            <div class="search-wrapper">
                <input type="text" name="q" placeholder="Search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M500.3 443.7 380.6 324c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723 99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9 53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0 15.606-15.64 15.606-41.04.006-56.64zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128-57.42 128-128 128-128-57.4-128-128z"/></svg>
            </div>
            <input type="hidden" name="as_sitesearch" value="<?= $_SERVER['HTTP_HOST'] ?>">
        </form>
    <?php endif; ?>
</header>