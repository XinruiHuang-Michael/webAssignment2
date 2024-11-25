<?php
// 开启会话（如需要反馈消息）
session_start();

// 引入数据库连接
require dirname(__DIR__) . '/database/db_connection.php';

// 检查是否通过 POST 方法提交表单
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取表单数据并进行基本验证
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../pages/contact.php");
        exit;
    }

    // 插入数据到数据库
    $query = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // 保存成功，设置成功消息并重定向
        $_SESSION['success'] = "Your message has been sent successfully!";
        header("Location: ../pages/contact.php");
        exit;
    } else {
        // 保存失败，设置错误消息并重定向
        $_SESSION['error'] = "Failed to send your message. Please try again.";
        header("Location: ../pages/contact.php");
        exit;
    }
} else {
    // 如果访问方式不是 POST，重定向回 Contact 页面
    header("Location: ../pages/contact.php");
    exit;
}
