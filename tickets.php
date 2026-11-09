<?php require 'inc/bootstrap.php'; need(); $u=user();
$w=['1=1'];$p=[];
if($u['role']==='End User'){$w[]='t.User_ID=?';$p[]=$u['id'];}
if($u['role']==='Technician'){$w[]='t.Technician_ID=?';$p[]=myTech();}
$s=trim($_GET['s']??'');if($s!==''){$w[]='(t.Ticket_Number LIKE ? OR t.Title LIKE ?)';$p[]="%$s%";$p[]="%$s%";}
foreach(['status'=>'t.Status','category'=>'t.Category_ID','priority'=>'t.Priority_ID'] as $k=>$col)if(($_GET[$k]??'')!==''){$w[]="$col=?";$p[]=$_GET[$k];}
if(($_GET['from']??'')!==''){$w[]='t.Created_At>=?';$p[]=$_GET['from'];}
if(($_GET['to']??'')!==''){$w[]='t.Created_At<=?';$p[]=$_GET['to'].' 23:59:59';}
$recent=q(TSQL.' WHERE '.implode(' AND ',$w)." ORDER BY FIELD(p.Priority_Name,'Critical','High','Medium','Low'),t.Created_At",$p)->fetchAll();
head('Tickets');?>
<h3><?=['End User'=>'My tickets','Technician'=>'Assigned tickets','Administrator'=>'All tickets'][$u['role']]?></h3>
<form class="card card-body mb-3 row flex-row g-2"><div class="col-md-3"><input class="form-control" name="s" placeholder="Ticket number or title" value="<?=e($s)?>"></div>
<div class="col-md-2"><select class="form-select" name="status"><option value="">Any status</option><?php foreach(['Open','Assigned','In Progress','Resolved','Closed'] as $x)echo '<option'.(($_GET['status']??'')===$x?' selected':'').'>'.$x.'</option>';?></select></div>
<div class="col-md-2"><select class="form-select" name="category"><option value="">Any category</option><?php foreach(q('SELECT * FROM Categories')->fetchAll() as $c)echo "<option value='{$c['Category_ID']}'".(($_GET['category']??'')==$c['Category_ID']?' selected':'').'>'.e($c['Category_Name']).'</option>';?></select></div>
<div class="col-md-2"><select class="form-select" name="priority"><option value="">Any priority</option><?php foreach(q('SELECT * FROM Priorities')->fetchAll() as $c)echo "<option value='{$c['Priority_ID']}'".(($_GET['priority']??'')==$c['Priority_ID']?' selected':'').'>'.e($c['Priority_Name']).'</option>';?></select></div>
<div class="col-md-1"><input class="form-control" type="date" name="from" value="<?=e($_GET['from']??'')?>"></div><div class="col-md-1"><input class="form-control" type="date" name="to" value="<?=e($_GET['to']??'')?>"></div>
<div class="col-md-1"><button class="btn btn-primary w-100">Filter</button></div></form>
<div class="card"><div class="card-body"><?php include 'inc/table.php';?></div></div><?php foot();
