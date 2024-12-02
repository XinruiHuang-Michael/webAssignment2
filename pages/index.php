<!-- <?php
// start session
session_start();
?>  -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/index.css">
    <title>Online Ordering System</title>
</head>

<body>
    <header>
        <h1>Restaurant Online Order</h1>
        <nav>
            <!-- navigation -->
            <ul>
                <li id="menu"><a href="menu.php">Menu</a></li>
                <li id="cart"><a href="cart.php">Shopping Cart</a></li>
                <li id="about"><a href="about.php">About Us</a></li>
                <li id="contact"><a href="contact.php">Contact Me</a></li>
                <li id="admin_login"><a href="admin_login.php" class="admin-link">Admin</a></li>
                <li id="log_out"><a href="logout.php">Log out</a></li>
            </ul>
        </nav>
    </header>
    <!-- index welcome banner -->
    <main>
        <section class="welcome-banner">
            <img src="../images/banner.jpg" alt="Delicious Food">
            <h2>Welcome to Online Order System</h2>
            <p>Your favorite dishes, delivered fresh!</p>
            <a href="menu.php" class="button">Order Now</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
</body>

</html>