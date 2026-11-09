<?php
require __DIR__.'/../config.php';
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS'])]);
session_start();
$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function q($sql,$p=[]){global $pdo;$s=$pdo->prepare($sql);$s->execute($p);return $s;}
function user(){return $_SESSION['u']??null;}
function csrf(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function tok(){return '<input type="hidden" name="_t" value="'.csrf().'">';}
function check(){if($_SERVER['REQUEST_METHOD']==='POST'&&!hash_equals(csrf(),$_POST['_t']??''))die('Invalid request token.');}
function flash($m,$bad=false){$_SESSION['f']=[$m,$bad];}
function go($u){header('Location: '.$u);exit;}
function need(...$roles){
  if(!user())go('login.php');
  if(time()-($_SESSION['last']??0)>900){session_unset();flash('Signed out after 15 minutes of inactivity.',true);go('login.php');}
  $_SESSION['last']=time();
  if($roles&&!in_array(user()['role'],$roles))die('Access denied.');
  check();
}
function audit($a,$t=null){if(user())q('INSERT INTO Audit_Log(User_ID,Action,Ticket_ID)VALUES(?,?,?)',[user()['id'],$a,$t]);}
function notify($uid,$tid,$msg){
  if(!$uid)return;q('INSERT INTO Notifications(User_ID,Ticket_ID,Message)VALUES(?,?,?)',[$uid,$tid,$msg]);
  $m=q('SELECT Email FROM Users WHERE User_ID=?',[$uid])->fetchColumn();if($m)@mail($m,'Richfield IT Help Desk',$msg);}
function strongPw($p){return strlen($p)>=8&&preg_match('/[A-Za-z]/',$p)&&preg_match('/\d/',$p)&&preg_match('/[^A-Za-z0-9]/',$p);}
function assignTo($tid,$techId,$by){
  q("UPDATE Tickets SET Technician_ID=?,Status='Assigned',Assigned_At=NOW() WHERE Ticket_ID=?",[$techId,$tid]);
  $t=q('SELECT Ticket_Number,User_ID FROM Tickets WHERE Ticket_ID=?',[$tid])->fetch();
  notify(q('SELECT User_ID FROM Technicians WHERE Technician_ID=?',[$techId])->fetchColumn(),$tid,'New ticket assigned: '.$t['Ticket_Number']);
  notify($t['User_ID'],$tid,'Your ticket '.$t['Ticket_Number'].' has been assigned to a technician.');audit($by,$tid);}
function autoAssign($tid,$catId){
  $c=q("SELECT t.Technician_ID FROM Technicians t JOIN Users u ON u.User_ID=t.User_ID JOIN Categories c ON c.Category_Name=t.Specialisation
        WHERE c.Category_ID=? AND t.Is_Available=1 AND u.Is_Active=1
        ORDER BY (SELECT COUNT(*) FROM Tickets x WHERE x.Technician_ID=t.Technician_ID AND x.Status IN('Assigned','In Progress')) LIMIT 1",[$catId])->fetchColumn();
  if($c)assignTo($tid,$c,'auto-assign');}
function head($title){
  $u=user();$f=$_SESSION['f']??null;unset($_SESSION['f']);
  $M=['End User'=>['dashboard'=>'Dashboard','ticket_create'=>'Create Ticket','tickets'=>'My Tickets','kb'=>'Knowledge Base','notifications'=>'Notifications'],
   'Technician'=>['dashboard'=>'Dashboard','tickets'=>'Assigned Tickets','kb'=>'Knowledge Base','notifications'=>'Notifications'],
   'Administrator'=>['dashboard'=>'Dashboard','tickets'=>'All Tickets','kb'=>'Knowledge Base','reports'=>'Reports','users'=>'Users','audit'=>'Audit Log','notifications'=>'Notifications']];
  echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($title).' - Richfield IT Help Desk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
  <nav class="navbar navbar-dark bg-primary px-3"><span class="navbar-brand">Richfield IT Help Desk</span>';
  if($u){$n=q('SELECT COUNT(*) FROM Notifications WHERE User_ID=? AND Is_Read=0',[$u['id']])->fetchColumn();
    echo '<span class="text-white">'.e($u['name']).' ('.e($u['role']).') <a class="btn btn-sm btn-outline-light ms-2" href="logout.php">Log out</a></span></nav>
    <div class="container-fluid"><div class="row"><div class="col-md-2 py-3"><div class="list-group">';
    foreach($M[$u['role']] as $k=>$v)echo '<a class="list-group-item list-group-item-action" href="'.$k.'.php">'.$v.($k==='notifications'&&$n?" <span class='badge bg-danger'>$n</span>":'').'</a>';
    echo '</div></div><main class="col-md-10 py-3">';
  }else echo '</nav><div class="container py-4"><main>';
  if($f)echo '<div class="alert alert-'.($f[1]?'danger':'success').'">'.e($f[0]).'</div>';
}
function foot(){echo '</main></div></div></body></html>';}
function badge($p){$c=['Low'=>'success','Medium'=>'warning','High'=>'danger','Critical'=>'dark'][$p]??'secondary';return '<span class="badge bg-'.$c.'">'.e($p).'</span>';}
const TSQL="SELECT t.*,c.Category_Name,p.Priority_Name,CONCAT(u.First_Name,' ',u.Last_Name) AS Owner,
 (SELECT CONCAT(x.First_Name,' ',x.Last_Name) FROM Technicians tt JOIN Users x ON x.User_ID=tt.User_ID WHERE tt.Technician_ID=t.Technician_ID) AS Tech
 FROM Tickets t JOIN Categories c USING(Category_ID) JOIN Priorities p USING(Priority_ID) JOIN Users u ON u.User_ID=t.User_ID";
function myTech(){static $id=false;if($id===false)$id=q('SELECT Technician_ID FROM Technicians WHERE User_ID=?',[user()['id']])->fetchColumn();return $id;}
