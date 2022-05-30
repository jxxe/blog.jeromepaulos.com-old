(function(){

// Prevent form resubmission popup after POST request
window.history.replaceState(null, null, window.location.href);

// Like 40% of jQuery right here
const $$ = selector => document.querySelectorAll(selector);
const $ = selector => $$(selector)[0] ?? null;

// Wire up reply buttons
$$('#comments .comment-reply').forEach(button => {
    button.addEventListener('click', () => {
        // Set hidden reply value
        $('#comments form input[name="parent_id"]').value = button.dataset.commentId;

        // Show reply text
        $('#comments .reply-to').classList.remove('hidden');
        $('#comments .reply-to strong').innerText = button.dataset.commentName;

        // Highlight comment being replied to
        $('#comments .comment.highlighted')?.classList.remove('highlighted');
        $(`#comments .comment[id="comment-${button.dataset.commentId}"]`).classList.add('highlighted');

        // Scroll to form and focus proper input
        $('#comments').scrollTo();
        if(localStorage.name && localStorage.email) {
            $('#comments form textarea').focus();
        } else {
            $('#comments form input').focus();
        }
    });
});

$('#comments .reply-to a').addEventListener('click', () => {
    $('#comments form input[name="parent_id"]').value = '';
    $('#comments .reply-to').classList.add('hidden');
    $('#comments .comment.highlighted')?.classList.remove('highlighted');
});

// Auto-resize comment textarea
$('#comments form textarea').addEventListener('input', event => {
    event.target.style.height = '5px';
    event.target.style.height = event.target.scrollHeight + 'px';
});

// Save name and email for any future comments
$('#comments form').addEventListener('submit', event => {
    const data = new FormData(event.target);
    localStorage.name = data.get('name');
    localStorage.email = data.get('email');
});

// Load name and email, if stored
window.addEventListener('DOMContentLoaded', () => {
    if(localStorage.name && localStorage.email) {
        $('#comments form input[name="name"]').value = localStorage.name;
        $('#comments form input[name="email"]').value = localStorage.email;
    }
});

})();