<?php
session_start();
require_once "db.php";



if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}




$username = $_SESSION['username'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? 0;



if ($is_admin != 1) {
    http_response_code(403);
    echo "Access denied. Admins only.";
    exit();
}



$sql = "SELECT 
            u.id,
            u.username,
            u.admin,
            COUNT(b.id) AS num_bets,
            COALESCE(SUM(b.amount_spent), 0) AS total_spent,
            COALESCE(SUM(b.amount_earned), 0) AS total_earned
        FROM users u
        LEFT JOIN bets b ON u.id = b.user_id
        GROUP BY u.id, u.username, u.admin
        ORDER BY u.id ASC";

$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Sports Analytics</title>
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
                        <a class="nav-link active" aria-current="page" href="profile.php">
                            <?= htmlspecialchars($username) ?> (Admin)
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>



<div class="container mt-4">
    <h3 class="mb-3">Admin Dashboard</h3>
    <p class="mb-4">
        Welcome, <strong><?= htmlspecialchars($username) ?></strong>.  
    </p>



    <h3>Users & Betting Summary</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">


            <thead class="table-light">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Admin?</th>
                    <th># Bets</th>
                    <th>Total Spent</th>
                    <th>Total Earned</th>
                    <th>Profit</th>
                </tr>
            </thead>


            <tbody>

            
                <?php foreach ($users as $u): 
                    $profit = $u['total_earned'] - $u['total_spent'];
                ?>
                    <tr>
                        <td><?= (int)$u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= $u['admin'] ? 'Yes' : 'No' ?></td>
                        <td><?= (int)$u['num_bets'] ?></td>
                        <td>$<?= number_format($u['total_spent'], 2) ?></td>
                        <td>$<?= number_format($u['total_earned'], 2) ?></td>
                        <td style="color: <?= $profit >= 0 ? 'green' : 'red' ?>;">$<?= number_format($profit, 2) ?></td>
                    </tr>


                <?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>

</body>
</html>
