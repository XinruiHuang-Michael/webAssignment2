<?php
require '../database/db_connection.php'; // 数据库连接
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css"> <!-- 全局样式 -->
    <link rel="stylesheet" href="../styles/menu.css"> <!-- 菜单页面特定样式 -->
    <title>Menu</title>
</head>
<body>
    <header>
        <h1>Our Menu</h1>
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
        <!-- 搜索和过滤表单 -->
        <section class="search-filter">
            <form method="GET" action="menu.php">
                <input type="text" name="search" placeholder="Search for dishes..." 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="main" <?php echo (isset($_GET['category']) && $_GET['category'] === 'main') ? 'selected' : ''; ?>>Main Courses</option>
                    <option value="side" <?php echo (isset($_GET['category']) && $_GET['category'] === 'side') ? 'selected' : ''; ?>>Side Dishes</option>
                    <option value="dessert" <?php echo (isset($_GET['category']) && $_GET['category'] === 'dessert') ? 'selected' : ''; ?>>Desserts</option>
                    <option value="drink" <?php echo (isset($_GET['category']) && $_GET['category'] === 'drink') ? 'selected' : ''; ?>>Drinks</option>
                </select>
                <input type="number" name="min_price" placeholder="Min Price" 
                    value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                <input type="number" name="max_price" placeholder="Max Price" 
                    value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                <button type="submit">Filter</button>
            </form>
        </section>

        <!-- 菜单展示 -->
        <section class="menu">
            <h2>Explore Our Dishes</h2>
            <?php
            // 获取搜索和过滤条件
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $category_filter = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
            $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
            $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;

            // 定义菜单分类
            $categories = [
                'main' => 'Main Courses',
                'side' => 'Side Dishes',
                'dessert' => 'Desserts',
                'drink' => 'Drinks'
            ];

            foreach ($categories as $key => $label) {
                // 如果用户选择了分类过滤且不匹配当前分类，跳过
                if (!empty($category_filter) && $category_filter !== $key) {
                    continue;
                }

                echo "<h3 class='menu-category'>$label</h3>";
                echo "<div class='menu-items'>";

                // 查询当前分类的菜品
                $query = "SELECT * FROM menu_items WHERE category = '$key'";
                if (!empty($search)) {
                    $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
                }
                if ($min_price > 0) {
                    $query .= " AND price >= $min_price";
                }
                if ($max_price > 0) {
                    $query .= " AND price <= $max_price";
                }

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<article class='menu-item'>";
                        echo "<img src='../images/{$row['image']}' alt='{$row['name']}'>";
                        echo "<h3>{$row['name']}</h3>";
                        echo "<p>\${$row['price']}</p>";
                        echo "<p>{$row['description']}</p>";
                        echo "<form method='POST' action='cart.php'>";
                        echo "<input type='hidden' name='item_name' value='{$row['name']}'>";
                        echo "<input type='hidden' name='item_price' value='{$row['price']}'>";
                        echo "<button type='submit'>Add to Cart</button>";
                        echo "</form>";
                        echo "</article>";
                    }
                } else {
                    echo "<p>No items available in this category.</p>";
                }

                echo "</div>";
            }
            ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Michael Restaurant</p>
    </footer>
</body>
</html>


