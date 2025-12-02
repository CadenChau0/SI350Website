<?php
session_start();
require_once "db.php";

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Debug mode ON — remove later
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//---------------------------------------------------
// Fetch user's bets
//---------------------------------------------------
$stmt = $pdo->prepare("
    SELECT id, bet_type, amount_spent, amount_earned, sport, date
    FROM bets
    WHERE user_id = ?
    ORDER BY date DESC
");
$stmt->execute([$user_id]);
$raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert DB rows → normalized array for JS + PHP
$betData = [];
foreach ($raw as $row) {
    $betData[] = [
        "date" => $row["date"],
        "sport" => $row["sport"],
        "bet_type" => $row["bet_type"],
        "spent" => (float)$row["amount_spent"],
        "earned" => (float)$row["amount_earned"]
    ];
}

//---------------------------------------------------
// Totals
//---------------------------------------------------
$total_spent = array_sum(array_column($betData, 'spent'));
$total_earned = array_sum(array_column($betData, 'earned'));

//---------------------------------------------------
// Group by type
//---------------------------------------------------
$byType = [];
foreach ($betData as $b) {
    $type = $b["bet_type"];
    if (!isset($byType[$type])) {
        $byType[$type] = [
            "spent" => 0,
            "earned" => 0,
            "count" => 0
        ];
    }
    $byType[$type]["spent"] += $b["spent"];
    $byType[$type]["earned"] += $b["earned"];
    $byType[$type]["count"]++;
}

//---------------------------------------------------
// Most common bet type
//---------------------------------------------------
$most_common_type = "N/A";
$max_count = 0;

foreach ($byType as $type => $stats) {
    if ($stats["count"] > $max_count) {
        $max_count = $stats["count"];
        $most_common_type = $type;
    }
}

$avg_spent = count($betData) ? $total_spent / count($betData) : 0;
$avg_earned = count($betData) ? $total_earned / count($betData) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($username) ?>'s Betting Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link type="text/css" rel="stylesheet" href="style.css?v=123">

    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        tfoot td { font-weight: bold; background: #fafafa; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <img class="img1" src="../../images/sportsbetting.jpg" />
        <a class="navbar-brand" href="index.php">Sports Analytics</a>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="registration.html">Registration</a></li>
                <li class="nav-item"><a class="nav-link active" href="stat_dashboard.php">Statistics</a></li>
                <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<h1><?= htmlspecialchars($username) ?>'s Betting Dashboard</h1>

<!-- Dropdown -->
<label>Select Bet Type:</label>
<select id="chartTypeSelector">
    <option value="all">All Types</option>
    <?php foreach ($byType as $type => $stats): ?>
        <option value="<?= $type ?>"><?= $type ?></option>
    <?php endforeach; ?>
</select>

<canvas id="moneyChart" height="100"></canvas>

<script>
const betData = <?= json_encode($betData) ?>;

// Build chart data
function getChartData(selected) {
    let labels = [];
    let spent = [];
    let earned = [];

    betData.forEach(b => {
        if (selected === "all" || b.bet_type === selected) {
            labels.push(b.date);
            spent.push(b.spent);
            earned.push(b.earned);
        }
    });

    return { labels, spent, earned };
}

let ctx = document.getElementById("moneyChart").getContext("2d");
let chart;

function updateChart(type) {
    const d = getChartData(type);

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: d.labels,
            datasets: [
                { label: "Money Spent", data: d.spent },
                { label: "Money Earned", data: d.earned }
            ]
        }
    });
}

document.getElementById("chartTypeSelector").addEventListener("change", function() {
    updateChart(this.value);
});

updateChart("all");
</script>

<h2>Your Bet History</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Sport</th>
            <th>Type</th>
            <th>Spent</th>
            <th>Earned</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($betData as $b): ?>
        <tr>
            <td><?= $b['date'] ?></td>
            <td><?= htmlspecialchars($b['sport']) ?></td>
            <td><?= htmlspecialchars($b['bet_type']) ?></td>
            <td>$<?= number_format($b['spent'], 2) ?></td>
            <td>$<?= number_format($b['earned'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Totals</td>
            <td>$<?= number_format($total_spent, 2) ?></td>
            <td>$<?= number_format($total_earned, 2) ?></td>
        </tr>
    </tfoot>
</table>

<h2>Statistical Report</h2>
<table>
    <tr><th>Total Spent</th><td>$<?= number_format($total_spent, 2) ?></td></tr>
    <tr><th>Total Earned</th><td>$<?= number_format($total_earned, 2) ?></td></tr>
    <tr><th>Most Common Bet Type</th><td><?= $most_common_type ?></td></tr>
    <tr><th>Average Spent Per Bet</th><td>$<?= number_format($avg_spent, 2) ?></td></tr>
    <tr><th>Average Earned Per Bet</th><td>$<?= number_format($avg_earned, 2) ?></td></tr>
</table>

<h3>Totals by Bet Type</h3>
<table>
    <tr>
        <th>Type</th>
        <th>Total Spent</th>
        <th>Total Earned</th>
        <th># Bets</th>
        <th>Avg Spent</th>
        <th>Avg Earned</th>
    </tr>

    <?php foreach ($byType as $type => $s): ?>
        <tr>
            <td><?= $type ?></td>
            <td>$<?= number_format($s['spent'], 2) ?></td>
            <td>$<?= number_format($s['earned'], 2) ?></td>
            <td><?= $s['count'] ?></td>
            <td>$<?= number_format($s['spent'] / max(1, $s['count']), 2) ?></td>
            <td>$<?= number_format($s['earned'] / max(1, $s['count']), 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

