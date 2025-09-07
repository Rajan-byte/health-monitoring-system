<?php
require 'config.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if(!($_SESSION['is_admin'] ?? 0)){ header("Location:index.php"); exit; }


// Add patient
if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['first_name'])){
    $stmt = $pdo->prepare("INSERT INTO patients(first_name,last_name,email,dob,gender) VALUES(?,?,?,?,?)");
    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'] ?: null,
        $_POST['dob'] ?: null,
        $_POST['gender'] ?: 'Other'
    ]);
    header("Location: users.php?added=1");
    exit;
}

// Fetch patients
$patients = $pdo->query("SELECT * FROM patients ORDER BY created_at DESC")->fetchAll();
?>

<div class="card">
<h2>Patients</h2>
<p class="small">Add a new patient or select one to view readings.</p>

<?php if(isset($_GET['added'])): ?>
<p style="color:green;">✅ Patient added successfully.</p>
<?php endif; ?>

<table class="table">
<thead>
<tr><th>Name</th><th>Email</th><th>DOB</th><th>Added</th></tr>
</thead>
<tbody>
<?php foreach($patients as $p): ?>
<tr>
<td><a href="readings.php?user_id=<?= $p['id'] ?>"><?= htmlspecialchars($p['first_name'].' '.$p['last_name']); ?></a></td>
<td><?= htmlspecialchars($p['email'] ?: '-') ?></td>
<td><?= $p['dob'] ?: '-' ?></td>
<td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div class="card">
<h3>Add Patient</h3>
<form method="post">
<div class="form-row"><label>First name</label><input name="first_name" required></div>
<div class="form-row"><label>Last name</label><input name="last_name" required></div>
<div class="form-row"><label>Email</label><input name="email" type="email"></div>
<div class="form-row"><label>DOB</label><input name="dob" type="date"></div>
<div class="form-row"><label>Gender</label>
<select name="gender"><option>Male</option><option>Female</option><option selected>Other</option></select>
</div>
<button type="submit">Add patient</button>
</form>
</div>

<?php require 'footer.php'; ?>
