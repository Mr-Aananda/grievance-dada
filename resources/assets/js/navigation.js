//search-bar ==============================>
function searchFunction() {
    var searchBar = document.getElementById("search-bar")
    var searchInput = document.getElementById("search-input")
    searchBar.classList.toggle("show")
    if (searchBar.className == "search-bar show") {
        searchInput.focus()
        searchInput.value = ""
    }
}



// Dark mode function ==============================>
function themeColorChange() {
    let icon = document.getElementById("bi-moon")
    document.body.classList.toggle("dark-mode")
    icon.classList.toggle("bi-brightness-high")
    icon.classList.toggle("bi-moon")
}



// Open full screen function ==============================>
function toggleFullScreen() {
    let icon = document.getElementById("bi-fullscreen")
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
        icon.classList.remove("bi-fullscreen")
        icon.classList.add("bi-fullscreen-exit")
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
            icon.classList.add("bi-fullscreen")
            icon.classList.remove("bi-fullscreen-exit")
        }
    }
    console.log(icon);
}

