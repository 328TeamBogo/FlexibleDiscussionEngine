document.addEventListener("DOMContentLoaded", function() {
    const toggleButton = document.getElementById("darkModeToggle");

    /**
     * Apply dark mode to page.
     */
    function applyDarkMode() {
        document.body.classList.add("dark-mode");
        document.querySelectorAll('.container').forEach(container => container.classList.add('dark-mode'));
        document.querySelectorAll('.login-container').forEach(container => container.classList.add('dark-mode'));
        document.querySelectorAll('.signup-container').forEach(container => container.classList.add('dark-mode'));
        document.querySelectorAll('.form-group').forEach(group => group.classList.add('dark-mode'));
        document.querySelectorAll('input[type="text"]').forEach(input => input.classList.add('dark-mode'));
        document.querySelectorAll('input[type="password"]').forEach(input => input.classList.add('dark-mode'));
        document.querySelectorAll('input[type="submit"]').forEach(button => button.classList.add('dark-mode'));
        document.querySelectorAll('hr').forEach(hr => hr.classList.add('dark-mode'));
        document.querySelectorAll('a').forEach(a => a.classList.add('dark-mode'));
        document.querySelectorAll('.alert').forEach(alert => alert.classList.add('dark-mode'));
        document.querySelectorAll('.navbar').forEach(navbar => navbar.classList.add('dark-mode'));
        document.querySelectorAll('.btn').forEach(button => button.classList.add('dark-mode'));
        document.querySelectorAll('.card').forEach(card => card.classList.add('dark-mode'));
        document.querySelectorAll('h2').forEach(h2 => h2.classList.add('dark-mode'));
        localStorage.setItem("darkMode", "enabled");
    }

    /**
     * Remove dark mode from page.
     */
    function removeDarkMode() {
        document.body.classList.remove("dark-mode");
        document.querySelectorAll('.container').forEach(container => container.classList.remove('dark-mode'));
        document.querySelectorAll('.login-container').forEach(container => container.classList.remove('dark-mode'));
        document.querySelectorAll('.signup-container').forEach(container => container.classList.remove('dark-mode'));
        document.querySelectorAll('.form-group').forEach(group => group.classList.remove('dark-mode'));
        document.querySelectorAll('input[type="text"]').forEach(input => input.classList.remove('dark-mode'));
        document.querySelectorAll('input[type="password"]').forEach(input => input.classList.remove('dark-mode'));
        document.querySelectorAll('input[type="submit"]').forEach(button => button.classList.remove('dark-mode'));
        document.querySelectorAll('hr').forEach(hr => hr.classList.remove('dark-mode'));
        document.querySelectorAll('a').forEach(a => a.classList.remove('dark-mode'));
        document.querySelectorAll('.alert').forEach(alert => alert.classList.remove('dark-mode'));
        document.querySelectorAll('.navbar').forEach(navbar => navbar.classList.remove('dark-mode'));
        document.querySelectorAll('.btn').forEach(button => button.classList.remove('dark-mode'));
        document.querySelectorAll('.card').forEach(card => card.classList.remove('dark-mode'));
        document.querySelectorAll('h2').forEach(h2 => h2.classList.remove('dark-mode'));
        localStorage.setItem("darkMode", "disabled");
    }

    // Check and apply
    if (localStorage.getItem("darkMode") === "enabled") {
        applyDarkMode();
    }

    // Toggle dark mode on button
    toggleButton.addEventListener("click", function() {
        if (document.body.classList.contains("dark-mode")) {
            removeDarkMode();
        } else {
            applyDarkMode();
        }
    });
});
