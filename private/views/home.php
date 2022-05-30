<?php /**
 * @var array $posts
 * @var int $streak
 */ ?>

<?php view('parts/header') ?>

<main id="home">

    <img src="/dist/dither.png" alt class="wide">

    <p>
        I'm a developer and multimedia journalist from Berkeley, California. I am currently a senior at Berkeley High School and will be attending Macalester College in Saint Paul, Minnesota this fall. You can find my photography <a href="https://jeromepaulos.com">here</a>.
    </p>

    <p>
        I've challenged myself to write two blog posts a week. Currently, I've written for <strong><?= $streak ?> week<?= s($streak) ?></strong> in a row. I also write <a href="/tutorials">strictly-technical posts</a> that are meant to be found when searching Google, which count towards the streak.
    </p>

    <?php if($posts): ?>
        <hr>
        <div class="posts-wrapper">
            <ul class="posts">
                <?php foreach($posts as $post): ?>
                    <li>
                        <a href="/posts/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                        <time datetime="<?= $post['date']->format('Y-m-d') ?>">(<?= $post['date']->format('F j, Y') ?>)</time>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="/posts">more</a>
        </div>
    <?php endif; ?>

</main>

<?php view('parts/footer') ?>