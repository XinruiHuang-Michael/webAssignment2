<?php
// start session 
session_start();
// check if the admin logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin_panel.css">
    <title>Admin Panel</title>
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <nav>
            <!-- admin page navigation -->
            <ul>
                <li><a href="menu_management.php">Menu Management</a></li>
                <li><a href="admin_account_management.php">Admin Account Management</a></li>
                <li><a href="order_details.php">Order Details</a></li>
                <li><a href="../server/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['admin_logged_in']); ?>)</a></li>
            </ul>
        </nav>
    </header>
    <!-- admin main panel -->
    <main>
        <section class="welcome">
            <h2>Welcome to the Admin Panel</h2>
            <p>Use the navigation menu above to manage the website content and administrator accounts.</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
</body>
</html>



