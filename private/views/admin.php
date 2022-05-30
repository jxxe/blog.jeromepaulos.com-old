<?php

use Blog\Controllers\HomeController;

/**
 * @var array[] $posts
 * @var int $comments_all_time
 * @var int $posts_this_week
 */

?>

<?php view('parts/header', title: 'Admin') ?>

<div>
    <div class="stats">
        <div class="stat">
            <h1><?= $posts_this_week ?> of <?= $_ENV['POSTS_PER_WEEK'] ?></h1>
            <p>post<?= s($_ENV['POSTS_PER_WEEK']) ?> this week</p>
        </div>

        <div class="stat">
            <h1><?= HomeController::calculate_streak($posts) ?> weeks</h1>
            <p>current streak</p>
        </div>

        <div class="stat">
            <h1>— views</h1>
            <p>last week</p>
        </div>

        <div class="stat">
            <h1><?= number_format($comments_all_time) ?> comment<?= s($comments_all_time) ?></h1>
            <p>all time</p>
        </div>
    </div>
</div>

<main id="admin">
    <?php if($posts): ?>
        <div class="posts-table">
            <?php foreach($posts as $post): ?>
                <div class="post-row">
                    <div>
                        <?php if($post['published']): ?>
                            <a href="/posts/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                        <?php else: ?>
                            <span><?= htmlspecialchars($post['title']) ?></span>
                        <?php endif; ?>
                        <div class="post-meta">
                            <span><?= $post['published'] ? 'Published' : 'Draft' ?></span>
                            <span><?= $post['date']->format('F j, Y') ?></span>
                            <span><?= $post['comment_count'] ?> comment<?= s($post['comment_count']) ?></span>
                        </div>
                    </div>

                    <a href="/admin/<?= $post['slug'] ?>" class="button has-icon">Edit</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No posts found</p>
    <?php endif; ?>
</main>

<script>
    window.history.replaceState(null, null, window.location.href);
</script>

<?php view('parts/footer') ?>