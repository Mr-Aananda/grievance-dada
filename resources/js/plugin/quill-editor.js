// resources/js/quill-global.js

// Import Quill properly for Vite
import Quill from "quill";
import "quill/dist/quill.snow.css";

// Wait for DOM to be ready
document.addEventListener("DOMContentLoaded", function () {
    initializeQuillEditors();
});

function initializeQuillEditors() {
    // Initialize elements with class 'quill-editor'
    const quillElements = document.querySelectorAll(".quill-editor");

    quillElements.forEach((element) => {
        if (element._quillInstance) return;

        try {
            // Get configuration from data attributes
            const hiddenInputId =
                element.dataset.inputId || `quill-hidden-${Date.now()}`;
            const inputName = element.dataset.inputName || "content";
            const initialContent = element.dataset.initialContent || "";
            const placeholder =
                element.dataset.placeholder || "Start writing...";

            // Create hidden input for form submission
            let hiddenInput = document.getElementById(hiddenInputId);
            if (!hiddenInput) {
                hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = inputName;
                hiddenInput.id = hiddenInputId;
                element.parentNode.insertBefore(
                    hiddenInput,
                    element.nextSibling
                );
            }

            // Initialize Quill editor
            const quill = new Quill(element, {
                theme: "snow",
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        ["blockquote", "code-block"],
                        [{ list: "ordered" }, { list: "bullet" }],
                        [{ script: "sub" }, { script: "super" }],
                        // [{ indent: "-1" }, { indent: "+1" }],
                        [{ size: ["small", false, "large", "huge"] }],
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        [{ color: [] }, { background: [] }],
                        [{ font: [] }],
                        [{ align: [] }],
                        // ["clean"],
                        ["link"],
                    ],
                },
                placeholder: placeholder,
            });

            // Set initial content
            if (initialContent) {
                quill.root.innerHTML = initialContent;
                hiddenInput.value = initialContent;
            }

            // Update hidden input when content changes
            quill.on("text-change", function () {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Store references
            element._quillInstance = quill;
            element._hiddenInput = hiddenInput;
        } catch (error) {
            console.error("Quill initialization failed:", error);
        }
    });
}

// Export for manual use
window.initializeQuillEditors = initializeQuillEditors;
