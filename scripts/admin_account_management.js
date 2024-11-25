document.addEventListener("DOMContentLoaded", () => {
    // ====================
    // Add Admin Account 验证
    // ====================
    const adminAddForm = document.getElementById("admin-add-form");

    adminAddForm.addEventListener("submit", (e) => {
        // 阻止表单提交，直到验证完成
        e.preventDefault();

        // 获取表单输入值
        const usernameInput = document.getElementById("username");
        const passwordInput = document.getElementById("password");

        // 获取错误消息元素
        const usernameError = document.getElementById("username-error");
        const passwordError = document.getElementById("password-error");

        // 清除之前的错误消息
        usernameError.textContent = "";
        passwordError.textContent = "";

        let isValid = true;

        // 验证用户名
        const usernameValue = usernameInput.value.trim();
        if (usernameValue === "") {
            usernameError.textContent = "Username is required.";
            isValid = false;
        } else if (usernameValue.length < 3) {
            usernameError.textContent = "Username must be at least 3 characters.";
            isValid = false;
        }

        // 验证密码
        const passwordValue = passwordInput.value.trim();
        if (passwordValue === "") {
            passwordError.textContent = "Password is required.";
            isValid = false;
        } else if (passwordValue.length < 6) {
            passwordError.textContent = "Password must be at least 6 characters.";
            isValid = false;
        }

        // 如果所有字段都有效，提交表单
        if (isValid) {
            adminAddForm.submit();
        }
    });

    // ========================
    // Edit Password 弹窗逻辑
    // ========================
    const modal = document.getElementById("edit-password-modal");
    const editPasswordForm = document.getElementById("edit-password-form");
    const editButtons = document.querySelectorAll(".edit-password-button");
    const closeBtn = document.querySelector(".modal .close");

    // 打开编辑密码弹窗
    editButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const username = button.dataset.username;
            document.getElementById("edit-username").value = username;
            modal.style.display = "block";
        });
    });

    // 关闭弹窗
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // 点击窗外关闭弹窗
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Edit Password 表单验证
    editPasswordForm.addEventListener("submit", (e) => {
        const newPassword = document.getElementById("new-password").value.trim();
        const confirmPassword = document.getElementById("confirm-password").value.trim();

        // 清除之前的错误消息
        const passwordError = document.getElementById("new-password-error");
        if (passwordError) passwordError.remove();

        let isValid = true;

        // 验证新密码
        if (newPassword.length < 6) {
            const error = document.createElement("p");
            error.textContent = "Password must be at least 6 characters.";
            error.id = "new-password-error";
            error.style.color = "#d9534f";
            editPasswordForm.insertBefore(error, editPasswordForm.firstChild);
            isValid = false;
        } else if (newPassword !== confirmPassword) {
            const error = document.createElement("p");
            error.textContent = "Passwords do not match.";
            error.id = "new-password-error";
            error.style.color = "#d9534f";
            editPasswordForm.insertBefore(error, editPasswordForm.firstChild);
            isValid = false;
        }

        // 如果验证失败，阻止表单提交
        if (!isValid) {
            e.preventDefault();
        }
    });
});

