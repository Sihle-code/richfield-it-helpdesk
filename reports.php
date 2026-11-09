<?php require 'inc/bootstrap.php'; need('Administrator');
if(isset($_GET['csv'])){header('Content-Type: text/csv');header('Content-Disposition: attachment; filename="tickets.csv"');$o=fopen('php://output','w');
  fputcsv($o,['Ticket','Title','Category','Priority','Status','Created','Technician']);
  foreach(q(TSQL.' ORDER BY t.Created_At')->fetchAll() as $r)fputcsv($o,[$r['Ticket_Number'],$r['Title'],$r['Category_Name'],$r['Priority_Name'],$r['Status'],$r['Created_At'],$r['Tech']]);exit;}
$r=q('SELECT AVG(TIMESTAMPDIFF(MINUTE,Created_At,Assigned_At))/60 a,AVG(TIMESTAMPDIFF(MINUTE,Created_At,Resolved_At))/60 r FROM Tickets')->fetch();
$sat=q('SELECT AVG(Rating) FROM Feedback')->fetchColumn();$f=fn($v)=>$v===null?'-':number_format($v,1);
head('Reports');?><h3>Reports</h3><div class="row g-3 mb-3">
<div class="col-md-4"><div class="card card-body"><div class="fs-2"><?=$f($r['a'])?> h</div>Average response time</div></div>
<div class="col-md-4"><div class="card card-body"><div class="fs-2"><?=$f($r['r'])?> h</div>Average resolution time</div></div>
<div class="col-md-4"><div class="card card-body"><div class="fs-2"><?=$f($sat)?> / 5</div>User satisfaction</div></div></div>
<?php foreach(['Tickets by status'=>'SELECT Status n,COUNT(*) c FROM Tickets GROUP BY Status','Tickets by category'=>'SELECT c.Category_Name n,COUNT(*) c FROM Tickets t JOIN Categories c USING(Category_ID) GROUP BY c.Category_Name',
 'Technician workload (all / open)'=>"SELECT CONCAT(u.First_Name,' ',u.Last_Name) n,CONCAT(COUNT(t.Ticket_ID),' / ',SUM(t.Status IN('Assigned','In Progress'))) c FROM Technicians te JOIN Users u USING(User_ID) LEFT JOIN Tickets t ON t.Technician_ID=te.Technician_ID GROUP BY te.Technician_ID"] as $h=>$sql):?>
<div class="card mb-3"><div class="card-body"><h5><?=$h?></h5><table class="table table-sm"><?php foreach(q($sql)->fetchAll() as $x)echo '<tr><td>'.e($x['n']).'</td><td>'.e($x['c']).'</td></tr>';?></table></div></div><?php endforeach;?>
<a class="btn btn-outline-primary" href="reports.php?csv=1">Export tickets to CSV</a><?php foot();
