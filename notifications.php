<?php require 'inc/bootstrap.php'; need();
$rows=q('SELECT * FROM Notifications WHERE User_ID=? ORDER BY Sent_At DESC LIMIT 100',[user()['id']])->fetchAll();
q('UPDATE Notifications SET Is_Read=1 WHERE User_ID=?',[user()['id']]);
head('Notifications');?><h3>Notifications</h3><div class="card"><div class="card-body">
<?php foreach($rows as $n)echo '<p'.($n['Is_Read']?'':' class="fw-bold"').'>'.e($n['Message']).($n['Ticket_ID']?' <a href="ticket.php?id='.$n['Ticket_ID'].'">View</a>':'').'<br><small class="text-muted">'.e($n['Sent_At']).'</small></p>';
if(!$rows)echo '<p class="text-muted">Nothing yet.</p>';?></div></div><?php foot();
