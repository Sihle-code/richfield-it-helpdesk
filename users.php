<?php require 'inc/bootstrap.php'; need('Administrator');
if($_SERVER['REQUEST_METHOD']==='POST'){$a=$_POST['a']??'';$id=(int)($_POST['id']??0);
  if($a==='active'&&$id!=user()['id']){q('UPDATE Users SET Is_Active=1-Is_Active WHERE User_ID=?',[$id]);audit('user-active');flash('User updated.');}
  elseif($a==='avail'){q('UPDATE Technicians SET Is_Available=1-Is_Available WHERE User_ID=?',[$id]);audit('tech-availability');flash('Availability updated.');}
  elseif($a==='add'){$f=array_map('trim',$_POST);
    if(!$f['first']||!$f['last']||!$f['username']||!filter_var($f['email'],FILTER_VALIDATE_EMAIL))flash('Complete all fields with a valid email.',true);
    elseif(!strongPw($_POST['password']))flash('Password needs 8+ characters with letters, numbers and a symbol.',true);
    elseif(q('SELECT 1 FROM Users WHERE Username=? OR Email=?',[$f['username'],$f['email']])->fetch())flash('Username or email already in use.',true);
    else{$role=in_array($f['role'],['End User','Technician','Administrator'])?$f['role']:'End User';
      q('INSERT INTO Users(First_Name,Last_Name,Email,Username,Password_Hash,Role)VALUES(?,?,?,?,?,?)',[$f['first'],$f['last'],$f['email'],$f['username'],password_hash($_POST['password'],PASSWORD_BCRYPT),$role]);
      if($role==='Technician')q('INSERT INTO Technicians(User_ID,Specialisation)VALUES(?,?)',[$GLOBALS['pdo']->lastInsertId(),$f['spec']]);
      audit('user-add');flash('User added.');}}
  go('users.php');}
$rows=q('SELECT u.*,t.Specialisation,t.Is_Available FROM Users u LEFT JOIN Technicians t USING(User_ID) ORDER BY u.Role,u.First_Name')->fetchAll();
head('Users');?><h3>Users and technicians</h3><div class="card mb-3"><div class="card-body table-responsive"><table class="table"><tr><th>Name</th><th>Role</th><th>Speciality</th><th>Status</th><th></th></tr>
<?php foreach($rows as $x):?><tr><td><?=e($x['First_Name'].' '.$x['Last_Name'])?><br><small class="text-muted"><?=e($x['Username'])?></small></td><td><?=e($x['Role'])?></td><td><?=e($x['Specialisation']?:'-')?></td>
<td><?=$x['Is_Active']?'Active':'Disabled'?><?=$x['Role']==='Technician'?($x['Is_Available']?', available':', unavailable'):''?></td><td>
<?php if($x['User_ID']!=user()['id'])echo '<form method="post" class="d-inline">'.tok().'<input type="hidden" name="a" value="active"><input type="hidden" name="id" value="'.$x['User_ID'].'"><button class="btn btn-sm btn-outline-secondary">'.($x['Is_Active']?'Disable':'Enable').'</button></form> ';
if($x['Role']==='Technician')echo '<form method="post" class="d-inline">'.tok().'<input type="hidden" name="a" value="avail"><input type="hidden" name="id" value="'.$x['User_ID'].'"><button class="btn btn-sm btn-outline-secondary">Toggle availability</button></form>';?></td></tr><?php endforeach;?></table></div></div>
<div class="card"><div class="card-body"><h5>Register user</h5><form method="post" class="row g-2"><?=tok()?><input type="hidden" name="a" value="add">
<div class="col-md-3"><input class="form-control" name="first" placeholder="First name"></div><div class="col-md-3"><input class="form-control" name="last" placeholder="Last name"></div>
<div class="col-md-3"><input class="form-control" name="email" placeholder="Email"></div><div class="col-md-3"><input class="form-control" name="username" placeholder="Username"></div>
<div class="col-md-3"><input class="form-control" type="password" name="password" placeholder="Password"></div>
<div class="col-md-3"><select class="form-select" name="role"><option>End User</option><option>Technician</option><option>Administrator</option></select></div>
<div class="col-md-3"><select class="form-select" name="spec"><?php foreach(q('SELECT Category_Name FROM Categories')->fetchAll(PDO::FETCH_COLUMN) as $c)echo '<option>'.e($c).'</option>';?></select></div>
<div class="col-md-3"><button class="btn btn-primary w-100">Add user</button></div></form></div></div><?php foot();
