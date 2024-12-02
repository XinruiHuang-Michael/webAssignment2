<?php
// start session
session_start();

// connect to database
require dirname(__DIR__) . '/database/db_connection.php';

// get information from contact page
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../pages/contact.php");
        exit;
    }

    // insert into contact_messages
    $query = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // save success, redirect to contact page and display success message
        $_SESSION['success'] = "Your message has been sent successfully!";
        header("Location: ../pages/contact.php");
        exit;
    } else {
        // save unsuccess, redirect to contact page and display error message
        $_SESSION['error'] = "Failed to send your message. Please try again.";
        header("Location: ../pages/contact.php");
        exit;
    }
} else {
    header("Location: ../pages/contact.php");
    exit;
}
