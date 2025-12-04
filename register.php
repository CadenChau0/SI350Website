<?php
session_start();
require_once "db.php";  // your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ----------------------------------
    // Validation
    // ----------------------------------
    if (empty($username) || empty($password)) {
        die("Error: Username and password required.");
    }

    // ----------------------------------
    // Check if user already exists
    // ----------------------------------
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        die("Error: Username already taken.");
    }

    // ----------------------------------
    // Hash password
    // ----------------------------------
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ----------------------------------
    // Insert into database
    // Default admin = 0   (not admin)
    // ----------------------------------
    $insert = $pdo->prepare(
        "INSERT INTO users (username, password_hash, admin)
         VALUES (?, ?, ?)"
    );
    $insert->execute([$username, $hashedPassword, 0]);

    $newId = $pdo->lastInsertId();

    // ----------------------------------
    // Auto-login after registration
    // ----------------------------------
    $_SESSION['user_id'] = $newId;
    $_SESSION['username'] = $username;
    $_SESSION['is_admin'] = 0;    // store numeric admin flag

    header("Location: stat_dashboard.php");
    exit;
}
?>
