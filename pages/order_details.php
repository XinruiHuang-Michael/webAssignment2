<?php
// start session
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require dirname(path: __DIR__) . '/database/db_connection.php'; // connect to database

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/order_details.css">
    <title>Orders</title>
</head>

<body>
    <header>
        <h1>Order Details</h1>
        <nav>
            <!-- navigation -->
            <ul>
                <li><a href="menu_management.php">Menu Management</a></li>
                <li><a href="admin_account_management.php">Admin Account Management</a></li>
                <li><a href="../server/logout.php">Logout(<?php echo htmlspecialchars($_SESSION['admin_logged_in']); ?>)</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="order-detail">
            <?php
            // get order details from database
            $query = "SELECT orders.id, menu_items.name, quantity, delivery_option, address, phone, order_date FROM orders JOIN orders_items ON orders.id = order_id JOIN menu_items ON menu_items.id = item_id";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                echo '<table class="order-table">
                <thead>
                <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Delivery Method</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Order Date</th>
                </tr>
                </thead>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tbody>
                <tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["quantity"] . "</td>
                <td>" . $row["delivery_option"] . "</td>
                <td>" . $row["address"] . "</td>
                <td>" . $row["phone"] . "</td>
                <td>" . $row["order_date"] . "</td>
                </tr>
                </tbody>";
                }
                echo "</table>";
            } else {
                echo '<p class="empty-order-message">No order been created.</p>';
            }

            ?>
        </section>

    </main>

    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
</body>

</html>