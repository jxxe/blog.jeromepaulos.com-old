<?php /**
 * @var array $post
 * @var bool $error
 */ ?>

<?php view('parts/header', title: 'Edit Post') ?>

<main id="edit">
    <div class="breadcrumbs">
        <a href="/">Home</a>
        <a href="/admin">Admin</a>
        <span>Edit Post</span>
    </div>

    <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if($error ?? false): ?>
            <div class="banner error">
                <p>Something went wrong</p>
            </div>
        <?php else: ?>
            <div class="banner">
                <p>Changes saved successfully</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST">
        <input maxlength="255" name="title" placeholder="Title" required type="text" value="<?= $post['title'] ?? '' ?>">
        <input name="slug" placeholder="slug" required type="text" value="<?= $post['slug'] ?? '' ?>" <?= ($post['slug'] ?? false) ? 'disabled' : '' ?>>

        <div class="buttons">
            <input name="date" required type="date" value="<?= $post['date'] ?? date('Y-m-d') ?>">
            <select name="status">
                <option value="draft" <?= !($post['published'] ?? false) ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= ($post['published'] ?? false) && !($post['tutorial'] ?? false) ? 'selected' : '' ?>>Published</option>
                <option value="tutorial" <?= ($post['tutorial'] ?? false) && ($post['published'] ?? false) ? 'selected' : '' ?>>Tutorial</option>
                <option value="delete">Delete</option>
            </select>
            <button class="save">Save Draft</button>
        </div>

        <textarea id="markdown-editor" name="content" placeholder="Start typing..." required><?= $post['content'] ?? '' ?></textarea>
    </form>
</main>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script src="/dist/edit.js"></script>

<?php view('parts/footer') ?>
