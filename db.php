<?php

//this code allows our PHP scripts to connect to the database
//will make graphing much smoother

$host = "db.cs.usna.edu";
$dbname = "m260378";
$user = "m260378";
$pass = "m260378";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
