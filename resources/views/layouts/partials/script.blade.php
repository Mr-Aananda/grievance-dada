@stack('script')
<script defer>
    // Global state to track sidebar mode
    let isStayOpen = false;

    // Function to toggle sidebar behavior
    function toggleSidebar() {
        const pageAside = document.getElementById("left-aside");
        const asideLayer = document.getElementById("aside-layer");
        const mainBar = document.getElementById("main-bar");

        if (isStayOpen) {
            // Switch to hover mode (close details section)
            pageAside.classList.remove("stay-open", "expand");
            asideLayer.classList.remove("show");
            mainBar.classList.remove("main-bar-expand");
            isStayOpen = false;
        } else {
            // Switch to stay-open mode
            pageAside.classList.add("stay-open", "expand");
            asideLayer.classList.add("show");
            mainBar.classList.add("main-bar-expand");
            isStayOpen = true;
        }
    }

    // Function to handle mouse enter
    function handleMouseEnter() {
        if (!isStayOpen) {
            const pageAside = document.getElementById("left-aside");
            const asideLayer = document.getElementById("aside-layer");
            const mainBar = document.getElementById("main-bar");

            pageAside.classList.add("expand");
            asideLayer.classList.add("show");
            mainBar.classList.add("main-bar-expand");
        }
    }

    // Function to handle mouse leave
    function handleMouseLeave() {
        if (!isStayOpen) {
            const pageAside = document.getElementById("left-aside");
            const asideLayer = document.getElementById("aside-layer");
            const mainBar = document.getElementById("main-bar");

            pageAside.classList.remove("expand");
            asideLayer.classList.remove("show");
            mainBar.classList.remove("main-bar-expand");
        }
    }

    // Initialize sidebar as collapsed (only icons visible)
    document.addEventListener('DOMContentLoaded', function() {
        const pageAside = document.getElementById("left-aside");
        const mainBar = document.getElementById("main-bar");

        pageAside.classList.remove("expand", "stay-open");
        mainBar.classList.remove("main-bar-expand");

        // Instead, ensure it's always expanded: and close uper codes
        // const pageAside = document.getElementById("left-aside");
        // const mainBar = document.getElementById("main-bar");
        // pageAside.classList.add("expand", "stay-open");
        // mainBar.classList.add("main-bar-expand");
    });

    // aside menu active function ==============================================>
    function asideMenuActiveFunction() {
        const activeMenu = document.getElementById('is-menu-active');
        if (!activeMenu) return;

        activeMenu.classList.add("active");

        // Activate collapse elements for the active menu item and its parents
        let element = activeMenu;
        while (element) {
            const collapseElement = element.closest('.collapse');
            if (collapseElement) {
                collapseElement.classList.add('show');
                const previousSibling = collapseElement.previousElementSibling;
                if (previousSibling) previousSibling.classList.remove('collapsed');
            }
            element = collapseElement ? collapseElement.previousElementSibling : null;
        }
    }

    asideMenuActiveFunction();

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

    // Dark mode function ==============================>
    function themeColorChange() {
        let icon = document.getElementById("bi-moon")
        document.body.classList.toggle("dark-mode")
        icon.classList.toggle("bi-brightness-high")
        icon.classList.toggle("bi-moon")
    }

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
