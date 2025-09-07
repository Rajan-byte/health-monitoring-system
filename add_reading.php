<?php
require 'config.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$users = $pdo->query("SELECT id, first_name, last_name FROM users ORDER BY first_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST'){
    $stmt = $pdo->prepare("INSERT INTO readings(user_id,recorded_at,systolic,diastolic,heart_rate,glucose,weight,notes) VALUES(?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['user_id'],
        $_POST['recorded_at'] ?: date('Y-m-d H:i:s'),
        $_POST['systolic'] ?: null,
        $_POST['diastolic'] ?: null,
        $_POST['heart_rate'] ?: null,
        $_POST['glucose'] ?: null,
        $_POST['weight'] ?: null,
        $_POST['notes'] ?: null
    ]);
    header("Location: add_reading.php?added=1");
    exit;
}
?>

<div class="card">
<h2>Add Reading</h2>
<?php if(isset($_GET['added'])): ?><p class="small" style="color:green;">✅ Reading added successfully.</p><?php endif; ?>

<form method="post">
<div class="form-row"><label>User</label>
<select name="user_id" required>
<?php foreach($users as $u): ?>
<option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></option>
<?php endforeach; ?>
</select></div>

<div class="form-row"><label>Recorded at</label><input type="datetime-local" name="recorded_at"></div>
<div class="form-row"><label>Systolic</label><input type="number" name="systolic" min="50" max="250"></div>
<div class="form-row"><label>Diastolic</label><input type="number" name="diastolic" min="30" max="150"></div>
<div class="form-row"><label>Heart rate</label><input type="number" name="heart_rate" min="30" max="220"></div>
<div class="form-row"><label>Glucose (mg/dL)</label><input type="number" step="0.1" name="glucose"></div>
<div class="form-row"><label>Weight (kg)</label><input type="number" step="0.1" name="weight"></div>
<div class="form-row"><label>Notes</label><textarea name="notes"></textarea></div>
<button type="submit">Save reading</button>
</form>
</div>

<?php require 'footer.php'; ?>
