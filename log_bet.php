<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require_once "db.php";  // use your main DB connection file

$username = $_SESSION['username'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? 0;

$message = ""; // Status message

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $bet_type = $_POST["bet_type"] ?? null;
    $sport = $_POST["sport"] ?? null;
    $amount_spent = $_POST["amount_spent"] ?? null;
    $amount_earned = $_POST["amount_earned"] ?? null;
    $user_id = $_SESSION['user_id'];

    // Auto-generate today's date (YYYY-MM-DD)
    $date = date("Y-m-d");

    if ($bet_type && $sport && $amount_spent !== null && $amount_earned !== null) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO bets (user_id, date, bet_type, sport, amount_spent, amount_earned)
                VALUES (:user_id, :date, :bet_type, :sport, :amount_spent, :amount_earned)
            ");
            $stmt->execute([
                ':user_id' => $user_id,
                ':date' => $date,
                ':bet_type' => $bet_type,
                ':sport' => $sport,
                ':amount_spent' => $amount_spent,
                ':amount_earned' => $amount_earned
            ]);

            $message = "<p style='color: green;'>Bet logged successfully!</p>";
        } catch (PDOException $e) {
            $message = "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $message = "<p style='color: red;'>Please fill in all fields.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Bet</title>
    <link rel="stylesheet" href="styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="sports.php">Sports</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="stat_dashboard.php">Statistics</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="log_bet.php">Log Bet</a>
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

                <?php if ($is_admin == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Admin</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<h3>Log a Bet</h3>

<?= $message ?>

<form action="log_bet.php" method="POST">

    <label>Bet Type:</label><br>
    <select name="bet_type" required>
        <option value="">-- Select Type --</option>
        <option value="Money Line">Money Line</option>
        <option value="Point Spread">Point Spread</option>
        <option value="Over/Under">Over/Under</option>
        <option value="Parlay">Parlay</option>
    </select><br><br>

    <label>Sport:</label><br>
    <input type="text" id="sport" name="sport" placeholder="NBA, NFL, MLB..." 
        onkeyup="showSportHint()" autocomplete="off" required><br>
    <small>Suggestions: <span id="sportHint"></span></small><br><br>

    <label>Amount Spent:</label><br>
    <input type="number" step="0.01" name="amount_spent" required><br><br>

    <label>Amount Earned:</label><br>
    <input type="number" step="0.01" name="amount_earned" required><br><br>

    <button type="submit">Submit Bet</button>
</form>

<script>
    function showSportHint() {


        const input = document.getElementById("sport").value;



        if (input.length === 0) {
            document.getElementById("sportHint").innerHTML = "";
            return;
        }



        const xhttp = new XMLHttpRequest();


        xhttp.onload = function () {
            document.getElementById("sportHint").innerHTML = this.responseText;
        };

        

        xhttp.open("GET", "get_sports.php?q=" + encodeURIComponent(input));
        xhttp.send();
    }
</script>


</body>
</html>
