<?php
// start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css"> 
    <link rel="stylesheet" href="../styles/contact.css"> 
    <title>Contact Us</title>
</head>
<body>
    <header>
        <h1>Contact Us</h1>
        <nav>
            <ul>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="cart.php">Shopping Cart</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin_login.php" class="admin-link">Admin</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="contact-form">
            <h2>We'd love to hear from you!</h2>
            <!-- show error or success messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <!-- contact form -->
            <form action="../server/contact_process.php" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <div>
                    <button type="submit">Send</button>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
</body>
</html>


