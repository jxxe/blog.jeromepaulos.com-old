<?php  /**
 * @var array $posts
 * @var string $title
 */ ?>

<?php view('parts/header', title: $title ?? 'Archive', body: 'archive') ?>

<div class="tabs-wrapper">
    <div class="tabs">
        <a href="/posts" class="tab<?= $_SERVER['REQUEST_URI'] === '/posts' ? ' active' : '' ?>">Posts</a>
        <a href="/tutorials" class="tab<?= $_SERVER['REQUEST_URI'] === '/tutorials' ? ' active' : '' ?>">Tutorials</a>
    </div>
</div>

<main>
    <?php if($posts): ?>
        <ul class="posts">
            <?php foreach($posts as $post): ?>
                <li>
                    <a href="/posts/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                    <time datetime="<?= $post['date']->format('Y-m-d') ?>">(<?= $post['date']->format('F j, Y') ?>)</time>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No posts found</p>
    <?php endif; ?>
</main>

<?php view('parts/footer') ?>
