<?php
require 'config.php';
require 'header.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// fetch overall metrics
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalReadings = $pdo->query("SELECT COUNT(*) FROM readings")->fetchColumn();

// fetch data for chart (last 14 days)
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($user_id > 0) {
    $stmt = $pdo->prepare("
        SELECT DATE(recorded_at) AS dt, AVG(systolic) AS avg_sys, AVG(diastolic) AS avg_dia
        FROM readings
        WHERE user_id = ? AND recorded_at >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
        GROUP BY DATE(recorded_at)
        ORDER BY dt
    ");
    $stmt->execute([$user_id]);
} else {
    $stmt = $pdo->query("
        SELECT DATE(recorded_at) AS dt, AVG(systolic) AS avg_sys, AVG(diastolic) AS avg_dia
        FROM readings
        WHERE recorded_at >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
        GROUP BY DATE(recorded_at)
        ORDER BY dt
    ");
}

$rows = $stmt->fetchAll();

$labels = [];
$avg_sys = [];
$avg_dia = [];
foreach ($rows as $r) {
    $labels[] = $r['dt'];
    $avg_sys[] = $r['avg_sys'] !== null ? round($r['avg_sys'],1) : null;
    $avg_dia[] = $r['avg_dia'] !== null ? round($r['avg_dia'],1) : null;
}
?>

<div class="card">
  <h2>Dashboard</h2>
  <p class="small">
    Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
    <br>Total users: <strong><?php echo $totalUsers; ?></strong> | Total readings: <strong><?php echo $totalReadings; ?></strong>
  </p>

  <canvas id="bpChart" width="600" height="250"></canvas>
</div>

<script>
const labels = <?php echo json_encode($labels); ?>;
const avgSys = <?php echo json_encode($avg_sys); ?>;
const avgDia = <?php echo json_encode($avg_dia); ?>;

const ctx = document.getElementById('bpChart').getContext('2d');
const bpChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [
      { label: 'Avg Systolic', data: avgSys, fill:false, borderColor: 'rgba(75, 192, 192, 1)', tension:0.3 },
      { label: 'Avg Diastolic', data: avgDia, fill:false, borderColor: 'rgba(255, 99, 132, 1)', tension:0.3 }
    ]
  },
  options: {
    scales: { y: { beginAtZero: false } },
    plugins: { legend: { position: 'top' } }
  }
});
</script>

<?php require 'footer.php'; ?>
