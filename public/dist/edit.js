(function(){

// Prevent form resubmission popup after POST request
window.history.replaceState(null, null, window.location.href);

// Like 40% of jQuery right here
const $$ = selector => document.querySelectorAll(selector);
const $ = selector => $$(selector)[0] ?? null;

const titleInput = $('#edit [name="title"]');
const getSlug = text => text.toLowerCase().replace(/[^\da-z- ]/g, '').replaceAll(' ', '-').replace(/-+/g, '-');
const slugInput = $('input[name="slug"]');

// Live update slug
titleInput.addEventListener('input', event => {
    if(slugInput.disabled) return;
    slugInput.value = getSlug(event.target.value, slugInput.value)
});

// Disable new line in title textarea
titleInput.addEventListener('keydown', event => {
    if(event.key === 'Enter') event.preventDefault();
});

// Respond to status dropdown
const statusDropdown = $('#edit select[name="status"]');
const saveButton = $('#edit button.save');
statusDropdown.addEventListener('change', setSaveButton);
window.addEventListener('DOMContentLoaded', setSaveButton);

function setSaveButton() {
    switch(statusDropdown.value) {
        case 'draft':
            saveButton.classList.remove('primary', 'destructive');
            saveButton.innerText = 'Save Draft';
            break;

        case 'published':
        case 'tutorial':
            saveButton.classList.remove('destructive');
            saveButton.classList.add('primary');
            saveButton.innerText = 'Update Post';
            break;

        case 'delete':
            saveButton.classList.remove('primary');
            saveButton.classList.add('destructive');
            saveButton.innerText = 'Delete Post';
            break;

        default:
            saveButton.classList.remove('destructive');
            saveButton.innerText = 'Save Post';
    }
}

// Content editor
new EasyMDE({
    element: document.getElementById('markdown-editor'),
    forceSync: true,
    status: false,
    hideIcons: ['guide', 'image', 'fullscreen', 'side-by-side'],
    showIcons: ['code', 'table', 'upload-image'],
    autoDownloadFontAwesome: undefined,
    uploadImage: true,
    imageUploadEndpoint: '/admin/upload',
    imagePathAbsolute: true,
    unorderedListStyle: '-',
    spellChecker: false
});

})();