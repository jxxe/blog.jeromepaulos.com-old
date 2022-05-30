<?php /**
 * @var bool $error
 */ ?>

<?php view('parts/header', title: 'Login') ?>

<main>
    <?php if($error ?? false): ?>
        <div class="banner error">
            <p>Wrong email or password</p>
        </div>
    <?php endif; ?>

    <form method="POST" class="buttons">
        <input required maxlength="100" autofocus name="email" placeholder="Email" type="email">
        <input required minlength="6" name="password" placeholder="Password" type="password">
        <button class="primary">Login</button>
    </form>
</main>

<?php view('parts/footer') ?>