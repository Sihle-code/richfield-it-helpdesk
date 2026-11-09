<?php require 'inc/bootstrap.php'; need(); $u=user();
$w=$u['role']==='End User'?'t.User_ID='.(int)$u['id']:($u['role']==='Technician'?'t.Technician_ID='.(int)myTech():'1=1');
$n=fn($s)=>q("SELECT COUNT(*) FROM Tickets t WHERE $w AND t.Status IN($s)")->fetchColumn();
$recent=q(TSQL." WHERE $w AND t.Status<>'Closed' ORDER BY FIELD(p.Priority_Name,'Critical','High','Medium','Low'),t.Created_At LIMIT 8")->fetchAll();
head('Dashboard');?>
<h3>Dashboard</h3><div class="row g-3 mb-3">
<?php foreach(['Open'=>"'Open'",'In progress'=>"'Assigned','In Progress'",'Resolved'=>"'Resolved'",'Closed'=>"'Closed'"] as $l=>$s):?>
<div class="col-6 col-md-3"><div class="card"><div class="card-body"><div class="fs-2"><?=$n($s)?></div><?=$l?></div></div></div><?php endforeach;
if($u['role']==='Administrator'):?><div class="col-6 col-md-3"><div class="card"><div class="card-body"><div class="fs-2"><?=q("SELECT COUNT(*) FROM Tickets WHERE Technician_ID IS NULL AND Status='Open'")->fetchColumn()?></div>Unassigned</div></div></div><?php endif;?></div>
<div class="card"><div class="card-body"><h5><?=$u['role']==='Technician'?'Your queue (by priority)':'Active tickets'?></h5>
<?php include 'inc/table.php';?></div></div><?php foot();
