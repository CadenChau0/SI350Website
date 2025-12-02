<?php
session_start();
require_once "db.php";


error_reporting(E_ALL);
ini_set('display_errors', 1);



// If logged in, get username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Betting Tracker</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* Live Search Styles */
        #search-box {
            width: 300px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        #results {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-height: 250px;
            overflow-y: auto;
            width: 600px;
            display: none;
            position: absolute;
            z-index: 10;
        }

        .results-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9em;
}

.results-table th, .results-table td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.results-table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.results-table tr:hover {
    background-color: #f9f9f9;
}
    </style>

    <!-- AJAX -->
    <script>
        function liveSearch() {
            const query = document.getElementById("search-box").value;

            if (query.length < 1) {
                document.getElementById("results").style.display = "none";
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "search_bets.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (this.status === 200) {
                    const results = document.getElementById("results");
                    results.innerHTML = this.responseText;
                    results.style.display = "block";
                }
            };

            xhr.send("query=" + encodeURIComponent(query));
        }
    </script>
</head>

<body>

<!-- NAV BAR -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="sports.php">Sports</a></li>
        <li><a href="log_bet.php">Log Bet</a></li>
        <li><a href="stat_dashboard.php">Stats Dashboard</a></li>

        <?php if (!$username): ?>
            <li style="float:right"><a href="login.php">Login</a></li>
        <?php else: ?>
            <li style="float:right"><a href="logout.php">Logout</a></li>
            <li style="float:right"><a href="profile.php"><?php echo htmlspecialchars($username); ?></a></li>
        <?php endif; ?>

        <?php if ($is_admin == 1): ?>
            <li><a href="admin.php">Admin</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container">
    <h1>Welcome to Your Betting Tracker</h1>
    <p>Search through your history of bets below.</p>

    <!-- SEARCH INPUT -->
    <input 
        type="text" 
        id="search-box" 
        placeholder="Search bets by sport, team, bet type, year..." 
        onkeyup="liveSearch()"
    >

    <!-- LIVE RESULTS AREA -->
    <div id="results"></div>
</div>

</body>
</html>
