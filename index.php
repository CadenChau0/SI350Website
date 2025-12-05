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
    <link type="text/css" rel="stylesheet" href="styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

<?php if (isset($_GET['logged_out'])): ?>
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <strong>Logged out successfully!</strong> See you next time.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

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
