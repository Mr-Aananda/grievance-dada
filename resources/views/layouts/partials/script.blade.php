@stack('script')
<script defer>
    // Print
    function printable(area) {
        var printContents = document.getElementById(area).innerHTML, // get printable content
            originalContents = document.body.innerHTML; // get document content

        // make a temporary document for printable content
        document.body.innerHTML = printContents;
        window.print(); // print the current document

        // restore the original document
        document.body.innerHTML = originalContents;
    }

    // Native Bootstrap 5 / AdminLTE 4 Dark Mode Toggle
    function themeColorChange() {
        let htmlEl = document.documentElement;
        let icon = document.getElementById("bi-moon");
        let currentTheme = htmlEl.getAttribute("data-bs-theme");

        if (currentTheme === "dark") {
            htmlEl.setAttribute("data-bs-theme", "light");
            if (icon) {
                icon.className = "bi bi-moon";
            }
            localStorage.setItem("theme", "light");
        } else {
            htmlEl.setAttribute("data-bs-theme", "dark");
            if (icon) {
                icon.className = "bi bi-brightness-high";
            }
            localStorage.setItem("theme", "dark");
        }
    }

    // Apply saved theme on page load
    document.addEventListener('DOMContentLoaded', function() {
        let htmlEl = document.documentElement;
        let icon = document.getElementById("bi-moon");
        let savedTheme = localStorage.getItem("theme") || "light";

        htmlEl.setAttribute("data-bs-theme", savedTheme);
        if (icon) {
            if (savedTheme === "dark") {
                icon.className = "bi bi-brightness-high";
            } else {
                icon.className = "bi bi-moon";
            }
        }
    });

    function previewImage(event, imagePreview, removeImg, fileInput) {
        let reader = new FileReader();
        reader.onload = function() {
            removeImg.classList.add("d-inline")
            removeImg.classList.remove("d-none")
            imagePreview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);

        removeImg.onclick = function() {
            imagePreview.src = ' '
            removeImg.classList.remove("d-inline")
            removeImg.classList.add("d-none")
            fileInput.value = null; // Clear the file input value
        };
    }
</script>
