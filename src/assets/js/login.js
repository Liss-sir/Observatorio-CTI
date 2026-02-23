document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".tab-btn");
    const forms = document.querySelectorAll(".form-role");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {

            const role = tab.getAttribute("data-role");

            // Reset tabs
            tabs.forEach(t => {
                t.classList.remove("bg-white", "shadow-sm", "font-medium");
                t.classList.add("text-gray-500");
            });

            // Activate clicked tab
            tab.classList.add("bg-white", "shadow-sm", "font-medium");
            tab.classList.remove("text-gray-500");

            // Hide all forms
            forms.forEach(form => form.classList.add("hidden"));

            // Show selected form
            document.getElementById(`form-${role}`).classList.remove("hidden");

        });
    });

});