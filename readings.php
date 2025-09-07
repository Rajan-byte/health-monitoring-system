<?php
require 'config.php';
require 'header.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$params = [];
$where = "";
if ($user_id>0){ $where="WHERE r.user_id=?"; $params[]=$user_id; }

$stmt = $pdo->prepare("SELECT r.*, u.first_name, u.last_name FROM readings r JOIN users u ON r.user_id=u.id $where ORDER BY r.recorded_at DESC LIMIT 200");
$stmt->execute($params);
$readings = $stmt->fetchAll();

$users = $pdo->query("SELECT id, first_name, last_name FROM users ORDER BY first_name")->fetchAll();
?>

<div class="card">
<h2>Readings</h2>

<form method="get">
<label>Filter by user</label>
<select name="user_id" onchange="this.form.submit()">
<option value="0">All users</option>
<?php foreach($users as $u): ?>
<option value="<?= $u['id'] ?>" <?= $user_id==$u['id'] ? 'selected':'' ?>><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></option>
<?php endforeach; ?>
</select>
</form>

<table class="table">
<thead><tr>
<th>User</th><th>Recorded</th><th>Systolic</th><th>Diastolic</th><th>HR</th><th>Glucose</th><th>Weight</th><th>Notes</th>
</tr></thead>
<tbody>
<?php foreach($readings as $r): ?>
<tr>
<td><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></td>
<td><?= date('M d, Y H:i', strtotime($r['recorded_at'])) ?></td>
<td><?= $r['systolic'] ?: '-' ?></td>
<td><?= $r['diastolic'] ?: '-' ?></td>
<td><?= $r['heart_rate'] ?: '-' ?></td>
<td><?= $r['glucose'] ?: '-' ?></td>
<td><?= $r['weight'] ?: '-' ?></td>
<td><?= htmlspecialchars($r['notes'] ?: '-') ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?php require 'footer.php'; ?>
