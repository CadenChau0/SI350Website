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
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- LINKS FOR LOGGED-IN USERS -->
            <li><a href="stat_dashboard.php">Stats Dashboard</a></li>
            <li><a href="log_bet.php">Log Bet</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <!-- LINKS FOR LOGGED-OUT USERS -->
            <li><a href="login.html">Login</a></li>
            <li><a href="registration.html">Register</a></li>
        <?php endif; ?>
    </ul>
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

            document.addEventListener("DOMContentLoaded", showWinnings);
        </script>

    </body>

</html>
