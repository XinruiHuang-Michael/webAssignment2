<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require '../database/db_connection.php';

// 预定义分类
$categories = ['main', 'side', 'dessert', 'drink']; 

// 添加菜单项
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (float) $_POST['price'];
    $description = trim($_POST['description']);

    // 处理图片上传
    $image = $_FILES['image']['name'];
    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $query = "INSERT INTO menu_items (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdss", $name, $category, $price, $description, $image);
        $stmt->execute();
    } else {
        $error = "Failed to upload image.";
    }
}

// 删除菜单项
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $delete_query = "DELETE FROM menu_items WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
}

// 查询菜单项
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter_category = isset($_GET['category']) && $_GET['category'] !== '' ? $_GET['category'] : '';
$sort_by = isset($_GET['sort_by']) ? $conn->real_escape_string($_GET['sort_by']) : 'name';

$query = "SELECT * FROM menu_items WHERE name LIKE ?";
$params = [];
$search_param = "%$search%";
$params[] = $search_param;

if ($filter_category) {
    $query .= " AND category = ?";
    $params[] = $filter_category;
}

$query .= " ORDER BY $sort_by";

$stmt = $conn->prepare($query);
if ($filter_category) {
    $stmt->bind_param("ss", ...$params);
} else {
    $stmt->bind_param("s", ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/menu_management.css">
    <title>Menu Management</title>
</head>
<body>
    <header>
        <h1>Menu Management</h1>
        <nav>
            <ul>
                <li><a href="admin_page.php">Admin Panel</a></li>
                <li><a href="menu_management.php">Menu Management</a></li>
                <li><a href="admin_account_management.php">Admin Account Management</a></li>
                <li><a href="../server/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['admin_logged_in']); ?>)</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <section class="menu-list">
        <h2>Menu Items</h2>
        <form method="GET" action="menu_management.php" class="filter-form">
            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category; ?>" <?php echo $filter_category === $category ? 'selected' : ''; ?>>
                        <?php echo $category; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="sort_by">
                <option value="name" <?php echo $sort_by === 'name' ? 'selected' : ''; ?>>Sort by Name</option>
                <option value="price" <?php echo $sort_by === 'price' ? 'selected' : ''; ?>>Sort by Price</option>
            </select>
            <button type="submit" class="filter-button">Apply</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="80"></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <a href="menu_management.php?delete_id=<?php echo $row['id']; ?>" class="delete-button">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
        <section class="menu-add">
        <h2>Add Menu Item</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="menu_management.php" enctype="multipart/form-data" class="add-form">
            <input type="hidden" name="action" value="add">
            <input type="text" placeholder="Name" id="name" name="name" required>
            <select id="category" name="category" required>
                <option value="" disabled selected>Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" step="0.01" placeholder="Price" id="price" name="price" required>
            <label for="image">Upload Product Image:</label> <!-- 添加说明文字 -->
            <input type="file" id="image" name="image" accept="image/*" required>
            <textarea id="description" name="description" placeholder="Description" required></textarea>
            <button type="submit">Add</button>
        </form>
    </section>
    </main>
    <footer>
        <p>&copy; 2024 Michael Restaurant</p>
    </footer>
</body>
</html>



