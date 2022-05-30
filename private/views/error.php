<?php /**
* @var int $code
 */

use Blog\App\RespondWith; ?>

<?php view('parts/header', title: RespondWith::code_to_name($code)) ?>

<main id="error">
    <img src="https://http.cat/<?= $code ?>" alt>
</main>

<?php view('parts/footer') ?>
