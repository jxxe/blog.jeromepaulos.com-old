<?php

use Blog\App\LimitedMarkdownParser;

/**
 * @var array[] $comments
 * @var string $slug
 */

$comments_map = [];
foreach($comments as $comment) {
    $comments_map[$comment['parent_id']][] = $comment;
}

$parser = new LimitedMarkdownParser();

function get_comment_html(array $comment, array $comments_structure, LimitedMarkdownParser $parser): string {
    $content = $parser->parse($comment['content']);

    $html =
        '<div class="comment-group">' .
            '<div class="comment" id="comment-' . $comment['id'] . '">' .

                '<div class="comment-meta">' .
                    '<div class="comment-author">' .
                        '<img src="https://www.gravatar.com/avatar/' . md5($comment['email']) . '?d=mp&s=96" alt>' .
                        '<div class="comment-author-name">' .
                            '<p>' . htmlspecialchars($comment['name']) . '</p>' .
                            '<a href="#comment-' . $comment['id'] . '">' .
                                '<time datetime="' . gmdate('c', $comment['timestamp']) . '">' . gmdate('F j, Y', $comment['timestamp']) . '</time>' .
                            '</a>' .
                        '</div>' .
                    '</div>' .

                    '<button class="comment-reply has-icon" data-comment-id="' . $comment['id'] . '" data-comment-name="' . htmlspecialchars($comment['name']) . '">' .
                        'Reply' .
                    '</button>' .
                '</div>' .

                '<div class="comment-body">' .
                    $content .
                '</div>' .
            '</div>';

    foreach($comments_structure[$comment['id']] ?? [] as $subcomment) {
        $html .= get_comment_html($subcomment, $comments_structure, $parser);
    }

    return $html . '</div>';
}

?>

<div id="comments">
    <p class="reply-to hidden">
        <span>Replying to <strong></strong></span>
        <a>clear</a>
    </p>

    <noscript>
        <em>Please enable JavaScript to reply to comments</em><br><br>
        <style>.comment-reply { display: none!important; }</style>
    </noscript>

    <form method="POST" action="#comments">
        <div class="buttons">
            <input maxlength="100" name="name" placeholder="Full Name" required type="text">
            <input maxlength="100" name="email" placeholder="Email" required type="email">
        </div>

        <textarea maxlength="1000" name="content" placeholder="Leave a comment..." required></textarea>
        <input type="hidden" name="parent_id">

        <div class="buttons notice">
            <p>
                <span>Supports basic Markdown</span>
            </p>
            <button class="primary">Post comment</button>
        </div>
    </form>

    <?php if($comments_map): ?>
        <?php foreach($comments_map[null] as $comment): ?>
            <?= get_comment_html($comment, $comments_map, $parser) ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script defer src="/dist/comments.js"></script>