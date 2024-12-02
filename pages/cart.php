<?php
// start session
session_start();
// get item information from menu page
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_name']) && isset($_POST['item_price'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $found = false;

    // check if the item has already in the cart
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $item_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    // if not, add the item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => 1,
        ];
    }

    // redirect to the menu page
    header(header: "Location: menu.php");
    exit;
}

// update the number of item in the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    // Check if the 'index' and 'quantity' values are available in the session
    $index = $_POST['index'];
    // Make sure the cart exists and the index is valid
    if (isset($_SESSION['cart'][$index])) {
        // Add the quantity of the item by 1
            $_SESSION['cart'][$index]['quantity'] += 1;
    }

    // refresh
    header("Location: cart.php");
    exit;
}

// reduce the item number from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reduce') {
    // Check if the 'index' and 'quantity' values are available in the session
    $index = $_POST['index'];
    // Make sure the cart exists and the index is valid
    if (isset($_SESSION['cart'][$index])) {
        // Reduce the quantity of the item by 1
        if ($_SESSION['cart'][$index]['quantity'] > 1) {
            $_SESSION['cart'][$index]['quantity'] -= 1;
        } else {
            $_SESSION['message'] = "Cannot reduce the item to 0!";
        }
    }

    // refresh
    header("Location: cart.php");
    exit;
}

// delete the item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $index = $_POST['index'] ?? -1;

    if ($index !== -1 && isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
    }

    // refresh
    header("Location: cart.php");
    exit;
}

// Display the cart page and message (if any)
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '")</script>';
    unset($_SESSION['message']);  // Clear the message after displaying
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/cart.css">
    <title>Shopping Cart</title>
</head>

<body>
    <header>
        <h1>Your Cart</h1>
        <nav>
            <!-- navigation -->
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
        <section class="cart-container">
            <!-- shopping cart list -->
            <h2>Your Shopping Cart</h2>
            <?php
            if (!empty($_SESSION['cart'])) {
                echo '<table class="cart-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Item</th>';
                echo '<th>Quantity</th>';
                echo '<th>Price</th>';
                echo '<th>Total</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $total = 0;
                foreach ($_SESSION['cart'] as $index => $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    echo '<tr>';
                    echo '<td>' . $item['name'] . '</td>';
                    echo '<td>' . $item['quantity'] . '</td>';
                    echo '<td>$' . number_format($item['price'], decimals: 2) . '</td>';
                    echo '<td>$' . number_format($subtotal, decimals: 2) . '</td>';
                    echo '<td class="action-buttons">';
                    // Form to reduce the quantity
                    echo '<div class="action-button">';
                    echo '<form method="POST" action="cart.php">';
                    echo '<input type="hidden" name="action" value="reduce">';
                    echo '<input type="hidden" name="index" value="' . $index . '">';
                    echo '<button type="submit">Reduce</button>';
                    echo '</form>';

                    // Form to reduce the quantity
                    echo '<form method="POST" action="cart.php">';
                    echo '<input type="hidden" name="action" value="add">';
                    echo '<input type="hidden" name="index" value="' . $index . '">';
                    echo '<button type="submit">Add</button>';
                    echo '</form>';
                    echo '</div>';

                    // Form to delete the item
                    echo '<form method="POST" action="cart.php">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="index" value="' . $index . '">';
                    echo '<button type="submit">Delete</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                $tax = $total * 0.13;
                $total += $tax;
                echo '</tbody>';
                echo '</table>';
                echo '<div class="cart-price">Tax: $' . number_format($tax, 2) . '</div>';
                echo '<div class="cart-price">Total: $' . number_format($total, 2) . '</div>';
            } else {
                echo '<p class="empty-cart-message">Your cart is empty.</p>';
            }
            ?>

            <a class="cart-button" id="checkout">Proceed to Checkout</a>

        </section>
    </main>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
    <!-- check if cart have item to checkout -->
    <?php
    if (!empty($_SESSION['cart'])) {  // if have, go to checkout page
        echo '<script>';
        echo 'document.getElementById("checkout").addEventListener("click", function (event){';
        echo 'document.getElementById("checkout").setAttribute("href", "checkout.php");';
        echo '});';
        echo '</script>';
    } else { // else, give notice
        echo '<script>';
        echo 'document.getElementById("checkout").addEventListener("click", function (event){';
        echo 'alert("Please select item first!");';
        echo '});';
        echo '</script>';
    }
    ?>
</body>

</html>