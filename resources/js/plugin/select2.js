// resources/js/app.js বা resources/js/bootstrap.js

import $ from "jquery";
import select2 from "select2";
import "select2/dist/css/select2.min.css";

// Make sure jQuery is available globally
window.$ = window.jQuery = $;

// Initialize select2
select2($);

// Auto initialize when document is ready
$(document).ready(function() {
    // Initialize all select2 elements
    $('.select2').each(function() {
        $(this).select2({
            placeholder: $(this).data('placeholder') || 'Select...',
            allowClear: true,
            width: '100%'
        });
    });
});

// Focus on search when opened
$(document).on('select2:open', function() {
    setTimeout(() => {
        document.querySelector('.select2-search__field')?.focus();
    }, 100);
});
