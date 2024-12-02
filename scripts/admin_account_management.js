document.addEventListener("DOMContentLoaded", () => {
    // add admin
    const adminAddForm = document.getElementById("admin-add-form");

    adminAddForm.addEventListener("submit", (e) => {
        // prevent submit
        e.preventDefault();

        const usernameInput = document.getElementById("username");
        const passwordInput = document.getElementById("password");

        // get the error information
        const usernameError = document.getElementById("username-error");
        const passwordError = document.getElementById("password-error");

        // clear errror messages
        usernameError.textContent = "";
        passwordError.textContent = "";

        let isValid = true;

        // check username
        const usernameValue = usernameInput.value.trim();
        if (usernameValue === "") {
            usernameError.textContent = "Username is required.";
            isValid = false;
        } else if (usernameValue.length < 3) {
            usernameError.textContent = "Username must be at least 3 characters.";
            isValid = false;
        }

        // check password
        const passwordValue = passwordInput.value.trim();
        if (passwordValue === "") {
            passwordError.textContent = "Password is required.";
            isValid = false;
        } else if (passwordValue.length < 6) {
            passwordError.textContent = "Password must be at least 6 characters.";
            isValid = false;
        }

        // if validation success, submit form
        if (isValid) {
            adminAddForm.submit();
        }
    });

    // pop-up window
    const modal = document.getElementById("edit-password-modal");
    const editPasswordForm = document.getElementById("edit-password-form");
    const editButtons = document.querySelectorAll(".edit-password-button");
    const closeBtn = document.querySelector(".modal .close");

    // open the popup
    editButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const username = button.dataset.username;
            document.getElementById("edit-username").value = username;
            modal.style.display = "block";
        });
    });

    // close the popup
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // click the outside to close popup
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Edit Password for validation
    editPasswordForm.addEventListener("submit", (e) => {
        const newPassword = document.getElementById("new-password").value.trim();
        const confirmPassword = document.getElementById("confirm-password").value.trim();

        const passwordError = document.getElementById("new-password-error");
        if (passwordError) passwordError.remove();

        let isValid = true;

        // check new password if is over 6 characters
        if (newPassword.length < 6) {
            const error = document.createElement("p");
            error.textContent = "Password must be at least 6 characters.";
            error.id = "new-password-error";
            error.style.color = "#d9534f";
            editPasswordForm.insertBefore(error, editPasswordForm.firstChild);
            isValid = false;
        } else if (newPassword !== confirmPassword) {   // check the confirm password
            const error = document.createElement("p");
            error.textContent = "Passwords do not match.";
            error.id = "new-password-error";
            error.style.color = "#d9534f";
            editPasswordForm.insertBefore(error, editPasswordForm.firstChild);
            isValid = false;
        }

        // if the validation failed, prevent the submit
        if (!isValid) {
            e.preventDefault();
        }
    });
});

