<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="style.css?v=123">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <img class="img1" title="bettinglogo" src="../../images/sportsbetting.jpg"/>
                <a class="navbar" href="index.html">Sports Analytics</a>


                <div class="collapse navbar-collapse" id="nav">
                    <ul class="navbar-nav mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="registration.html">Registration</a></li>
                        <li class="nav-item"><a class="nav-link" href="statistics.html">Statistics</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.html">Logout</a></li>
                    </ul>

                </div>
            </div>
        </nav>
</html>

<?php
session_start();
require_once "db.php"; //connect to database

// Only allow POST access
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit();
}

//trim username and password:
$userName = trim($_POST['username']);
$userPassword = trim($_POST['password']);

$valid = false;

// ------------------------------------
// Look up user
// ------------------------------------
$stmt = $pdo->prepare("SELECT id, username, password_hash, admin 
                       FROM users 
                       WHERE username = ?");
$stmt->execute([$userName]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user not found and check password
if (!$user) {
    header("Location: login.html");
    exit();
} //now check the password
else if (!password_verify($userPassword, $user['password_hash'])) {
    header("Location: login.html");
    exit();
} else {
    $valid = true;
}


//now set the valid variable and allow login!
if ($valid) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['admin'] = $user['admin'];

    header("Location: index.html");
    exit();
}
else {
    header("Location: login.html");
    exit();
}

?>
