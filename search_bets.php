<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
     http_response_code(403);
     echo "Not logged in.";
     exit();
}

$user_id = $_SESSION['user_id'];
$term = $_POST['query'] ?? '';
$term = trim($term);

if ($term === "") {
    echo "";
    exit();
}

// Build search query across valid columns ONLY
$sql = "
    SELECT date, bet_type, sport, amount_spent, amount_earned
    FROM bets
    WHERE user_id = :uid
      AND (
            date LIKE :t
         OR bet_type LIKE :t
         OR sport LIKE :t
         OR amount_spent LIKE :t
         OR amount_earned LIKE :t
      )
    ORDER BY date DESC
    LIMIT 20
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':uid' => $user_id,
    ':t'   => "%$term%"
]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo "<div class='result-item'>No matching bets.</div>";
    exit();
}

// START NEW: Build HTML table output
$html = "
    <table class='results-table'>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Sport</th>
                <th>Spent</th>
                <th>Earned</th>
            </tr>
        </thead>
        <tbody>
";

foreach ($rows as $row) {
    $html .= "
        <tr>
            <td>{$row['date']}</td>
            <td>{$row['bet_type']}</td>
            <td>{$row['sport']}</td>
            <td>\${$row['amount_spent']}</td>
            <td>\${$row['amount_earned']}</td>
        </tr>
    ";
}

$html .= "
        </tbody>
    </table>
";

echo $html;
?>
