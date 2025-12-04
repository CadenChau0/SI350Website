<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="style.css?v=123">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <img class="img1" title="bettinglogo" src="sportsbetting.jpg" />
            <a class="navbar" href="index.php">Sports Analytics</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#nav" aria-controls="nav" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="sports.php">Sports</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="stat_dashboard.php">Statistics</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="log_bet.php">Log Bet</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="registration.html">Registration</a>
                    </li>

                    <?php if (!$username): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="stat_dashboard.php">
                                <?= htmlspecialchars($username) ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Admin link -->
                    <?php if ($is_admin == 1): ?>
                        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                    <?php endif; ?>

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
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_admin'] = $user['admin'];

    header("Location: index.php");
    exit();
}
else {
    header("Location: login.html");
    exit();
}

?>
