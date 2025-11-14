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
$userName = $_POST['username'];
$userPassword = $_POST['password'];
$valid = false;

$file = fopen("LOG.txt", "r");

while (($line = fgets($file)) !== false) {
    list($name, $password) = explode(":", trim($line));
    if ($userName === $name && $userPassword === $password) {
        $valid = true;
        break;
    }
}
fclose($file);


if ($valid) {
    $_SESSION['username'] = $userName;
    header("Location: index.html");
    exit();
}
else {
    header("Location: login.html");
    exit();
}

?>
