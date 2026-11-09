<?php require 'inc/bootstrap.php'; need(); $u=user();$id=(int)($_GET['id']??0);
$t=q(TSQL.' WHERE t.Ticket_ID=?',[$id])->fetch();if(!$t)die('Ticket not found.');
$staff=$u['role']!=='End User';$owner=$t['User_ID']==$u['id'];$mine=$u['role']==='Technician'&&$t['Technician_ID']==myTech();
if(($u['role']==='End User'&&!$owner)||($u['role']==='Technician'&&!$mine))die('Access denied.');
$can=$u['role']==='Administrator'||$mine;
$techUser=$t['Technician_ID']?q('SELECT User_ID FROM Technicians WHERE Technician_ID=?',[$t['Technician_ID']])->fetchColumn():null;
if($_SERVER['REQUEST_METHOD']==='POST'){$a=$_POST['a']??'';$tx=trim($_POST['text']??'');$int=$staff&&!empty($_POST['internal'])?1:0;
  if($a==='comment'&&$tx!==''){q('INSERT INTO Updates(Ticket_ID,User_ID,Update_Text,Is_Internal)VALUES(?,?,?,?)',[$id,$u['id'],$tx,$int]);
    if(!$int)notify($owner?$techUser:$t['User_ID'],$id,'New comment on '.$t['Ticket_Number']);flash('Comment posted.');}
  elseif($a==='status'&&$can){$s=$_POST['status']??'';
    if(!in_array($s,['Open','Assigned','In Progress','Resolved','Closed']))flash('Invalid status.',true);
    elseif($s==='Resolved'&&$tx==='')flash('Add a comment before resolving.',true);
    else{q('UPDATE Tickets SET Status=?,Resolved_At=IF(?="Resolved",NOW(),Resolved_At),Closed_At=IF(?="Closed",NOW(),Closed_At) WHERE Ticket_ID=?',[$s,$s,$s,$id]);
      if($tx!=='')q('INSERT INTO Updates(Ticket_ID,User_ID,Update_Text,Is_Internal)VALUES(?,?,?,?)',[$id,$u['id'],$tx,$int]);
      if(!$int)notify($t['User_ID'],$id,$t['Ticket_Number'].' status changed to '.$s);audit('status:'.$s,$id);flash('Status updated.');}}
  elseif($a==='assign'&&$u['role']==='Administrator'){$tid=(int)$_POST['tech'];
    if(q('SELECT 1 FROM Technicians WHERE Technician_ID=? AND Is_Available=1',[$tid])->fetch()){assignTo($id,$tid,'assign');flash('Ticket assigned.');}else flash('Technician not found or unavailable.',true);}
  elseif($a==='feedback'&&$owner&&in_array($t['Status'],['Resolved','Closed'])){$r=(int)$_POST['rating'];
    if($r<1||$r>5)flash('Invalid rating.',true);
    elseif(q('SELECT 1 FROM Feedback WHERE Ticket_ID=?',[$id])->fetch())flash('Feedback already submitted.',true);
    else{q('INSERT INTO Feedback(Ticket_ID,Rating,Comments)VALUES(?,?,?)',[$id,$r,$tx?:null]);q("UPDATE Tickets SET Status='Closed',Closed_At=NOW() WHERE Ticket_ID=?",[$id]);audit('feedback',$id);flash('Thank you. Ticket closed.');}}
  go("ticket.php?id=$id");}
$ups=q('SELECT up.*,CONCAT(u.First_Name," ",u.Last_Name) AS Who FROM Updates up JOIN Users u USING(User_ID) WHERE Ticket_ID=?'.($staff?'':' AND Is_Internal=0').' ORDER BY Created_At',[$id])->fetchAll();
$files=q('SELECT * FROM Attachments WHERE Ticket_ID=?',[$id])->fetchAll();$fb=q('SELECT * FROM Feedback WHERE Ticket_ID=?',[$id])->fetch();
head($t['Ticket_Number']);?>
<a href="tickets.php" class="btn btn-outline-primary btn-sm mb-2">Back to list</a>
<div class="card mb-3"><div class="card-body"><h4><?=e($t['Ticket_Number'])?>: <?=e($t['Title'])?></h4>
<p><?=badge($t['Priority_Name'])?> <b><?=e($t['Status'])?></b> &middot; <?=e($t['Category_Name'])?></p><p><?=nl2br(e($t['Description']))?></p>
<?php foreach($files as $f)echo '<p>Attachment: <a href="download.php?id='.$f['Attachment_ID'].'">'.e($f['File_Name']).'</a></p>';?>
<small class="text-muted">Logged by <?=e($t['Owner'])?> on <?=e($t['Created_At'])?><br>Technician: <?=e($t['Tech']?:'Unassigned')?> &middot; Assigned <?=e($t['Assigned_At']?:'-')?> &middot; Resolved <?=e($t['Resolved_At']?:'-')?> &middot; Closed <?=e($t['Closed_At']?:'-')?></small></div></div>
<div class="card mb-3"><div class="card-body"><h5>Updates</h5>
<?php foreach($ups as $x)echo '<div class="border-start border-3 ps-2 mb-2"><b>'.e($x['Who']).'</b> '.($x['Is_Internal']?'<span class="badge bg-secondary">internal</span>':'').'<br>'.nl2br(e($x['Update_Text'])).'<br><small class="text-muted">'.e($x['Created_At']).'</small></div>'; if(!$ups)echo '<p class="text-muted">No updates yet.</p>';?>
<form method="post"><?=tok()?><input type="hidden" name="a" value="comment"><textarea class="form-control" name="text" rows="2" placeholder="Add a comment"></textarea>
<?php if($staff):?><label class="mt-1"><input type="checkbox" name="internal" value="1"> Internal note (staff only)</label><br><?php endif;?><button class="btn btn-primary btn-sm mt-2">Post comment</button></form></div></div>
<?php if($u['role']==='Administrator'):?><div class="card mb-3"><div class="card-body"><h5>Assign</h5><form method="post" class="row g-2"><?=tok()?><input type="hidden" name="a" value="assign"><div class="col-md-6"><select class="form-select" name="tech">
<?php foreach(q("SELECT t.Technician_ID,t.Specialisation,CONCAT(u.First_Name,' ',u.Last_Name) n FROM Technicians t JOIN Users u USING(User_ID) WHERE t.Is_Available=1 AND u.Is_Active=1")->fetchAll() as $x)echo "<option value='{$x['Technician_ID']}'>".e($x['n'].' ('.$x['Specialisation'].')').'</option>';?></select></div><div class="col"><button class="btn btn-primary">Assign ticket</button></div></form></div></div><?php endif;
if($can):?><div class="card mb-3"><div class="card-body"><h5>Update status</h5><form method="post"><?=tok()?><input type="hidden" name="a" value="status"><select class="form-select" name="status">
<?php foreach(['Open','Assigned','In Progress','Resolved','Closed'] as $s)echo '<option'.($s===$t['Status']?' selected':'').'>'.$s.'</option>';?></select>
<textarea class="form-control mt-2" name="text" rows="2" placeholder="Comment or troubleshooting notes (required to resolve)"></textarea><label class="mt-1"><input type="checkbox" name="internal" value="1"> Internal note</label><br><button class="btn btn-primary btn-sm mt-2">Save status</button></form></div></div><?php endif;
if($owner&&$t['Status']==='Resolved'&&!$fb):?><div class="card mb-3"><div class="card-body"><h5>Confirm the fix and rate</h5><form method="post"><?=tok()?><input type="hidden" name="a" value="feedback"><select class="form-select w-auto" name="rating"><?php for($i=5;$i>=1;$i--)echo "<option>$i</option>";?></select><input class="form-control mt-2" name="text" placeholder="Comment (optional)"><button class="btn btn-primary mt-2">Submit feedback and close</button></form></div></div><?php endif;
if($fb)echo '<div class="card"><div class="card-body"><h5>Feedback</h5>Rating: '.(int)$fb['Rating'].' / 5 '.e($fb['Comments']).'</div></div>';
foot();
