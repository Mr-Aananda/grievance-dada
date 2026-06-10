document.addEventListener("DOMContentLoaded", function () {
    var table = document.getElementById("editable");
    if (!table) {
        console.error(
            "The element with id 'editable' was not found in the DOM."
        );
        return;
    }
    var cells = table.getElementsByClassName("show-edit-input");

    for (var i = 0; i < cells.length; i++) {
        cells[i].ondblclick = function () {
            if (this.hasAttribute("data-clicked")) {
                return;
            }
            this.setAttribute("data-clicked", "yes");
            this.setAttribute("data-text", this.innerHTML);

            var input = document.createElement("input");
            input.setAttribute("type", "text");
            input.value = this.innerHTML;
            input.classList.add("form-control");

            input.onblur = function () {
                var td = input.parentElement;
                var origText = td.getAttribute("data-text");
                var currentText = this.value;

                if (origText !== currentText) {
                    td.removeAttribute("data-clicked");
                    td.removeAttribute("data-text");
                    td.innerHTML = currentText;
                } else {
                    td.removeAttribute("data-clicked");
                    td.removeAttribute("data-text");
                    td.innerHTML = origText;
                }
            };

            this.innerHTML = "";
            this.append(input);
            input.select();
        };
    }

    // Select master & child checkboxes
    let masterCheckbox = document.getElementById("check-all"),
        childCheckbox = document.querySelectorAll('[name="records[]"]');

    if (masterCheckbox) {
        masterCheckbox.addEventListener("change", function () {
            for (var i = 0; i < childCheckbox.length; i++) {
                childCheckbox[i].checked = this.checked;
            }
        });
    }
    else {
        console.warn(
            "The master checkbox with id 'check-all' was not found in the DOM."
        );
    }
});
