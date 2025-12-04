<?php
session_start();
?>

<!DOCTYPE html>
<!-- This is a comment!   -->
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="description" content="Sports Analytics">
        <meta name="keywords" content="HTML, CSS, JavaScript">
        <meta name="author" content="Caden Chau">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to Sports Analytics</title>
        <link type="text/css" rel="stylesheet" href="styles.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <?php if (isset($_SESSION['username'])): ?>
    <div style="background:#eee; padding:10px; text-align:center; font-size:20px; margin-bottom:15px;">
        Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
    </div>
<?php endif; ?>

    <body>
     <?php
session_start();
?>
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
                    <a class="nav-link active" aria-current="page" href="sports.php">Sports</a>
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

                <?php if ($is_admin == 1): ?>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>


        <h1 class="text-center">Sports Analytics</h1>
        <h4 class="text-center">Winnings from Each Sport</h4>

        <div class="container-fluid mt-4">
            <h5>Total Winnings Table</h5>
            <div class="table-responsive">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Total Winnings ($)</th>
                        </tr>
                    </thead>
                    <tbody id="winningsTable"></tbody>
                </table>
            </div>
        </div>

        <div class="container mt-4">
            <h5>Winnings by Sport</h5>
            <canvas id="winningsChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            let winningsData = [];

            function showWinnings() {

                var xhttp = new XMLHttpRequest();

                xhttp.onload = function () {

                    winningsData = JSON.parse(this.responseText);

                    populateTable();
                    drawChart();
                };

                xhttp.open("GET", "winnings.json");
                xhttp.send();
            }


            function populateTable() {

                var html = "";

                for (var i = 0; i < winningsData.length; i++) {
                    var row = winningsData[i];

                    var pretty = "$" + row.gain.toLocaleString("en-US", {minimumFractionDigits: 2, maximumFractionDigits: 2});

                    if (i === 0){
                        html += "<tr class=\"table-gold\">"
                        + "<td>" + row.sport + "</td>"
                        + "<td>" + pretty + "</td>"
                        + "</tr>";
                    } else if (i === 1){
                        html += "<tr class=\"table-silver\">"
                        + "<td>" + row.sport + "</td>"
                        + "<td>" + pretty + "</td>"
                        + "</tr>";
                    } else if (i === 2) {
                        html += "<tr class=\"table-bronze\">"
                        + "<td>" + row.sport + "</td>"
                        + "<td>" + pretty + "</td>"
                        + "</tr>";
                    } else {
                        html += "<tr>"
                        + "<td>" + row.sport + "</td>"
                        + "<td>" + pretty + "</td>"
                        + "</tr>";
                    }


                }

                document.getElementById("winningsTable").innerHTML = html;
            }


            function drawChart() {

                const labels = winningsData.map(item => item.sport);
                const values = winningsData.map(item => item.gain);

                const ctx = document.getElementById('winningsChart').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Total Winnings ($)",
                            data: values,
                            backgroundColor: 'rgba(194, 22, 10, 0.7)'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: {
                            x: { beginAtZero: true }
                        }
                    }
                });
            }

            function loadStats() {
                var xhr = new XMLHttpRequest();

                xhr.onload = function () {
                    var stats = JSON.parse(this.responseText);

                    // Expect keys: sports_by_profit, sports_by_volume
                    populateProfitTable(stats.sports_by_profit);
                    populateVolumeTable(stats.sports_by_volume);
                };

                xhr.open("GET", "stats.json");
                xhr.send();
            }

            function formatMoney(value) {
                return "$" + value.toLocaleString("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function formatPercent(value) {
                return (value * 100).toFixed(1) + "%";
            }

            function populateProfitTable(data) {
                let html = "";

                data.forEach(row => {
                    html += "<tr>"
                        + "<td>" + row.sport + "</td>"
                        + "<td>" + row.num_bets + "</td>"
                        + "<td>" + row.num_wins + "</td>"
                        + "<td>" + formatMoney(row.total_stake) + "</td>"
                        + "<td>" + formatMoney(row.profit) + "</td>"
                        + "<td>" + formatPercent(row.roi) + "</td>"
                        + "</tr>";
                });

        document.getElementById("profitTable").innerHTML = html;
    }

    function populateVolumeTable(data) {
        let html = "";

        data.forEach(row => {
            html += "<tr>"
                + "<td>" + row.sport + "</td>"
                + "<td>" + row.num_bets + "</td>"
                + "<td>" + row.num_wins + "</td>"
                + "<td>" + formatMoney(row.total_stake) + "</td>"
                + "<td>" + formatMoney(row.profit) + "</td>"
                + "<td>" + formatPercent(row.roi) + "</td>"
                + "</tr>";
        });

        document.getElementById("volumeTable").innerHTML = html;
    }

        document.addEventListener("DOMContentLoaded", function () {
            showWinnings();
            loadStats();
        });
        </script>


        <div class="container mt-4">
            <h5>Sports Ranked by Profit</h5>
            <div class="table-responsive">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Number Bets</th>
                            <th>Number Wins</th>
                            <th>Total Stake ($)</th>
                            <th>Profit ($)</th>
                            <th>ROI (Return on Investment)</th>
                        </tr>
                    </thead>
                    <tbody id="profitTable"></tbody>
                </table>
            </div>
        </div>

        <div class="container mt-4">
            <h5>Sports Ranked by Volume</h5>
            <div class="table-responsive">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Number Bets</th>
                            <th>Number Wins</th>
                            <th>Total Stake ($)</th>
                            <th>Profit ($)</th>
                            <th>ROI (Return on Investment)</th>
                        </tr>
                    </thead>
                    <tbody id="volumeTable"></tbody>
                </table>
            </div>
        </div>


    </body>

</html>
