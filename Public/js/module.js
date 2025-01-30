document.addEventListener("DOMContentLoaded", function() {
    function toggleInputs(checkbox) {
        let targetIds = checkbox.getAttribute("data-toggle-targets").split(",");

        targetIds.forEach(targetId => {
            let targetInput = document.getElementById(targetId.trim());

            if (targetInput) {
                if (checkbox.checked) {
                    targetInput.removeAttribute("disabled");
                    targetInput.setAttribute("required", "required");
                } else {
                    targetInput.setAttribute("disabled", "disabled");
                    targetInput.removeAttribute("required");
                }
            }
        });
    }

    let checkboxes = document.querySelectorAll("[data-toggle-targets]");

    checkboxes.forEach(checkbox => {
        toggleInputs(checkbox); // Initialize on page load
        checkbox.addEventListener("change", function() {
            toggleInputs(this);
        });
    });
});
