<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require_once "db.php";  // use your main DB connection file

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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- NAV BAR -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="stat_dashboard.php">Stats Dashboard</a></li>
        <li><a href="log_bet.php">Log Bet</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<h1>Log a Bet</h1>

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
    <input type="text" name="sport" placeholder="NBA, NFL, MLB..." required><br><br>

    <label>Amount Spent:</label><br>
    <input type="number" step="0.01" name="amount_spent" required><br><br>

    <label>Amount Earned:</label><br>
    <input type="number" step="0.01" name="amount_earned" required><br><br>

    <button type="submit">Submit Bet</button>
</form>

</body>
</html>
