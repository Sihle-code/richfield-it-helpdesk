<?php require 'inc/bootstrap.php';
if($_SERVER['REQUEST_METHOD']==='POST'){check();$f=array_map('trim',$_POST);
  if(!$f['first']||!$f['last']||!$f['username']||!$f['email'])flash('Complete all required fields.',true);
  elseif(!filter_var($f['email'],FILTER_VALIDATE_EMAIL))flash('Enter a valid email address.',true);
  elseif(!strongPw($_POST['password']))flash('Password needs 8+ characters with letters, numbers and a symbol.',true);
  elseif($_POST['password']!==$_POST['confirm'])flash('Passwords do not match.',true);
  elseif(q('SELECT 1 FROM Users WHERE Username=? OR Email=?',[$f['username'],$f['email']])->fetch())flash('Username or email already in use.',true);
  else{q("INSERT INTO Users(First_Name,Last_Name,Email,Username,Password_Hash,Phone,Role)VALUES(?,?,?,?,?,?,'End User')",[$f['first'],$f['last'],$f['email'],$f['username'],password_hash($_POST['password'],PASSWORD_BCRYPT),$f['phone']?:null]);
    flash('Account created. You can now sign in.');go('login.php');}
  go('register.php');}
head('Register');?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body"><h4>Register</h4><form method="post"><?=tok()?>
<div class="row g-2"><div class="col"><input class="form-control" name="first" placeholder="First name *"></div><div class="col"><input class="form-control" name="last" placeholder="Last name *"></div></div>
<input class="form-control mt-2" name="email" placeholder="Email *"><input class="form-control mt-2" name="username" placeholder="Username *"><input class="form-control mt-2" name="phone" placeholder="Phone">
<input class="form-control mt-2" type="password" name="password" placeholder="Password *"><input class="form-control mt-2" type="password" name="confirm" placeholder="Confirm password *">
<button class="btn btn-primary mt-3">Register</button> <a href="login.php" class="btn btn-link mt-3">Back to login</a></form></div></div></div></div>
<?php foot();
