<?php require 'inc/bootstrap.php'; need('Administrator');
$rows=q("SELECT l.*,CONCAT(u.First_Name,' ',u.Last_Name) n,t.Ticket_Number FROM Audit_Log l JOIN Users u USING(User_ID) LEFT JOIN Tickets t USING(Ticket_ID) ORDER BY l.Logged_At DESC LIMIT 200")->fetchAll();
head('Audit Log');?><h3>Audit log</h3><div class="card"><div class="card-body table-responsive"><table class="table table-sm"><tr><th>When</th><th>User</th><th>Action</th><th>Ticket</th></tr>
<?php foreach($rows as $r)echo '<tr><td>'.e($r['Logged_At']).'</td><td>'.e($r['n']).'</td><td>'.e($r['Action']).'</td><td>'.e($r['Ticket_Number']).'</td></tr>';?></table></div></div><?php foot();
