document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    function validateForm(event) {
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        if (!email.includes("@")) {
            alert("Please enter a valid email address.");
            event.preventDefault();
            return false;
        }

        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            event.preventDefault();
            return false;
        }
    }

    if (loginForm) loginForm.addEventListener("submit", validateForm);
    if (registerForm) registerForm.addEventListener("submit", validateForm);
});
