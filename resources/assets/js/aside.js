// Utility function to toggle class for multiple elements
function toggleClasses(elements, className) {
    elements.forEach(element => element.classList.toggle(className));
}

// Function to expand the aside section
function expandFunction() {
    const pageAside = document.getElementById("left-aside");
    pageAside.classList.remove("from-mouse-enter");
    expand();
}

// Function to handle mouse hover expansion
function mouseHoverExpand() {
    expand();
}

// Core function to handle expansion of the aside and related elements
function expand() {
    const pageAside = document.getElementById("left-aside");
    const asideLayer = document.getElementById("aside-layer");
    const mainBar = document.getElementById("main-bar");

    toggleClasses([pageAside, asideLayer, mainBar], 'expand');
    asideLayer.classList.toggle("show");
    mainBar.classList.toggle("main-bar-expand");
}

// Function to handle mouse enter event
function mouseEnter() {
    if (window.innerWidth >= 1400) {
        const pageAside = document.getElementById("left-aside");
        if (pageAside.classList.contains('expand')) {
            pageAside.classList.add("from-mouse-enter");
            mouseHoverExpand();
        }
    }
}

// Function to handle mouse leave event
function mouseLeave() {
    const pageAside = document.getElementById("left-aside");
    if (pageAside.classList.contains("from-mouse-enter")) {
        mouseHoverExpand();
    }
}



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
