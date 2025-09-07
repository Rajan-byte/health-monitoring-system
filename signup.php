<?php
require 'config.php';
session_start();
if(isset($_SESSION['user_id'])) header("Location:index.php");

$error = $success = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $first_name=trim($_POST['first_name']);
    $last_name=trim($_POST['last_name']);
    $email=trim($_POST['email']);
    $dob=$_POST['dob']?:null;
    $gender=$_POST['gender']??'Other';
    $password=$_POST['password'];
    $confirm=$_POST['confirm_password'];

    if($first_name && $last_name && $email && $password && $confirm){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $error="Invalid email format.";
        elseif($password!==$confirm) $error="Passwords do not match.";
        else{
            $stmt=$pdo->prepare("SELECT id FROM users WHERE email=?");
            $stmt->execute([$email]);
            if($stmt->fetch()) $error="Email already registered.";
            else{
                $hash=password_hash($password,PASSWORD_DEFAULT);
                $stmt=$pdo->prepare("INSERT INTO users(first_name,last_name,email,dob,gender,password) VALUES(?,?,?,?,?,?)");
                $stmt->execute([$first_name,$last_name,$email,$dob,$gender,$hash]);
                $success="Account created successfully. <a href='login.php'>Login here</a>.";
            }
        }
    }else $error="Please fill in all fields.";
}
require 'header.php';
?>
<div class="card">
<h2>Sign Up</h2>
<p class="small">Create your account to start using the Health Monitoring System.</p>
<?php if($error):?><p style="color:red;"><?php echo htmlspecialchars($error); ?></p><?php endif;?>
<?php if($success):?><p style="color:green;"><?php echo $success;?></p><?php endif;?>
<form method="post">
  <div class="form-row"><label>First name</label><input type="text" name="first_name" required></div>
  <div class="form-row"><label>Last name</label><input type="text" name="last_name" required></div>
  <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
  <div class="form-row"><label>DOB</label><input type="date" name="dob"></div>
  <div class="form-row"><label>Gender</label>
    <select name="gender"><option>Male</option><option>Female</option><option selected>Other</option></select>
  </div>
  <div class="form-row"><label>Password</label><input type="password" name="password" required></div>
  <div class="form-row"><label>Confirm Password</label><input type="password" name="confirm_password" required></div>
  <button type="submit">Sign Up</button>
</form>
<p class="small">Already have an account? <a href="login.php">Login here</a>.</p>
</div>
<?php require 'footer.php'; ?>
