<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require '../database/db_connection.php';

// default admin name
$default_username = 'admin';

// add new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // check if use repeat name
    $check_query = "SELECT * FROM admin_users WHERE username = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        $query = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $success = "Admin added successfully!";
    }
}

// delete admin
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    // check if the admin exists
    $query = "SELECT username FROM admin_users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $username_to_delete = $row['username'];

    // check if the default admin logged in
    if ($username_to_delete === $default_username) {
        echo "<script>alert('Cannot delete the default admin account.'); window.location.href='admin_account_management.php';</script>";
    } elseif ($username_to_delete === $_SESSION['admin_logged_in']) {
        session_destroy();
        echo "<script>alert('You cannot delete your own account.');</script>";
    } else {
        $delete_query = "DELETE FROM admin_users WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);
        $delete_stmt->execute();
        header("Location: admin_account_management.php");
        exit;
    }
}

// change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['new_password'])) {
    $username = trim($_POST['username']);
    $new_password = trim($_POST['new_password']);

    // update new password
    $query = "SELECT password FROM admin_users WHERE username = ?";
    $stmt_check = $conn->prepare($query);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();
    //check if the newpassword as same as the original one
    if ($check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        if ($row["password"] === $new_password) {
            echo "<script>alert('Same as the original password!')</script>";
        } else {
            $query1 = "UPDATE admin_users SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($query1);
            $stmt->bind_param("ss", $new_password, $username);
            if ($stmt->execute()) {
                echo "<script>alert('Password updated successfully.'); window.location.href='admin_account_management.php';</script>";
            } else {
                echo "<script>alert('Failed to update password. Please try again.'); window.location.href='admin_account_management.php';</script>";
            }
        }
    } else {
        echo "<script>alert('This admin does not exists!')</script>";
    }

}

// search admin account
$query = "SELECT * FROM admin_users";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin_account_management.css">
    <script src="../scripts/admin_account_management.js" defer></script>
    <title>Admin Account Management</title>
</head>

<body>
    <header>
        <h1>Admin Account Management</h1>
        <nav>
            <!-- navigation -->
            <ul>
                <li><a href="menu_management.php">Menu Management</a></li>
                <li><a href="order_details.php">Order Details</a></li>
                <li><a href="../server/logout.php">Logout
                        (<?php echo htmlspecialchars($_SESSION['admin_logged_in']); ?>)</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- add admin -->
        <section class="admin-add">
            <h2>Add Admin Account</h2>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form id="admin-add-form" method="POST" action="admin_account_management.php">
                <input type="hidden" name="action" value="add_admin">
                <input type="text" id="username" name="username" placeholder="Username">
                <span class="error-message" id="username-error"></span>
                <input type="password" id="password" name="password" placeholder="Password">
                <span class="error-message" id="password-error"></span>
                <button type="submit" class="add-button">Add Admin</button>
            </form>
        </section>

        <!-- the information of admin -->
        <section class="admin-list">
            <h2>Admin Accounts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <?php if ($_SESSION['admin_logged_in'] === 'admin'): ?>
                            <th>Password</th>
                        <?php endif; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <?php if ($_SESSION['admin_logged_in'] === 'admin'): ?>
                                <td><?php echo htmlspecialchars($row['password']); ?></td>
                            <?php endif; ?>
                            <td>
                                <a href="admin_account_management.php?delete_id=<?php echo $row['id']; ?>"
                                    class="delete-button">Delete</a>
                                <button class="edit-password-button"
                                    data-username="<?php echo htmlspecialchars($row['username']); ?>">Edit Password</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- change password -->
    <div id="edit-password-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Password</h2>
            <form id="edit-password-form" method="POST" action="admin_account_management.php">
                <input type="hidden" id="edit-username" name="username">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new_password" required>
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
                <button type="submit" class="save-password-button">Save Changes</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
</body>

</html>