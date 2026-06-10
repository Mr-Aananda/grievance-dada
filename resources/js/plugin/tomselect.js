// Import Tom Select JS and CSS
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.bootstrap5.css";

// Single event listener for all Tom Select elements
document.addEventListener("DOMContentLoaded", function () {
    // Initialize all elements with ID containing '_tomselect'
    const tomSelectElements = document.querySelectorAll('[id*="_tomselect"]');

    tomSelectElements.forEach((element) => {
        // Default configuration
        const config = {
            create: false,
            sortField: {
                field: "text",
                direction: "asc",
            },
        };

        // Add remove_button plugin for multiple selects
        if (element.multiple) {
            config.plugins = ["remove_button"];
            config.maxItems = null;
        }

        // Initialize Tom Select
        new TomSelect(element, config);

        console.log("Tom Select initialized for:", element.id);
    });
});
