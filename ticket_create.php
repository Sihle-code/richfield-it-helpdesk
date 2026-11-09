<?php require 'inc/bootstrap.php'; need('End User');
if($_SERVER['REQUEST_METHOD']==='POST'){
  $ti=trim($_POST['title']??'');$de=trim($_POST['description']??'');$ca=(int)($_POST['category']??0);$pr=(int)($_POST['priority']??0);
  $fl=$_FILES['file']??null;$has=$fl&&$fl['error']!==UPLOAD_ERR_NO_FILE;$ext='';
  if(!$ti||!$de||!$ca||!$pr)flash('Complete all required fields.',true);
  elseif($has&&($fl['error']!==0||$fl['size']>MAX_UPLOAD||!in_array($ext=strtolower(pathinfo($fl['name'],PATHINFO_EXTENSION)),['png','jpg','jpeg','pdf','docx','txt'])))flash('Attachment must be PNG, JPG, PDF, DOCX or TXT up to 5 MB.',true);
  else{
    q('INSERT INTO Tickets(Ticket_Number,User_ID,Category_ID,Priority_ID,Title,Description)VALUES(?,?,?,?,?,?)',[uniqid('T'),user()['id'],$ca,$pr,$ti,$de]);
    $id=$GLOBALS['pdo']->lastInsertId();$no='RIT-'.date('Y').'-'.str_pad($id,4,'0',STR_PAD_LEFT);
    q('UPDATE Tickets SET Ticket_Number=? WHERE Ticket_ID=?',[$no,$id]);
    if($has){$p=bin2hex(random_bytes(12)).'.'.$ext;move_uploaded_file($fl['tmp_name'],__DIR__.'/storage/attachments/'.$p);
      q('INSERT INTO Attachments(Ticket_ID,File_Name,File_Path)VALUES(?,?,?)',[$id,basename($fl['name']),$p]);}
    notify(user()['id'],$id,'Ticket submitted: '.$no);
    foreach(q("SELECT User_ID FROM Users WHERE Role='Administrator' AND Is_Active=1")->fetchAll(PDO::FETCH_COLUMN) as $a)notify($a,$id,'New ticket received: '.$no);
    audit('create',$id);autoAssign($id,$ca);flash("Ticket $no created.");go("ticket.php?id=$id");}
  go('ticket_create.php');}
head('Create Ticket');?>
<h3>Create ticket</h3><p class="text-muted">Search the <a href="kb.php">Knowledge Base</a> first for a quick fix.</p>
<div class="card"><div class="card-body"><form method="post" enctype="multipart/form-data"><?=tok()?>
<label class="form-label">Title *</label><input class="form-control" name="title" maxlength="150" required>
<label class="form-label mt-2">Description *</label><textarea class="form-control" name="description" rows="4" required></textarea>
<div class="row mt-2"><div class="col"><label class="form-label">Category *</label><select class="form-select" name="category"><?php foreach(q('SELECT * FROM Categories')->fetchAll() as $c)echo "<option value='{$c['Category_ID']}'>".e($c['Category_Name'])."</option>";?></select></div>
<div class="col"><label class="form-label">Priority *</label><select class="form-select" name="priority"><?php foreach(q('SELECT * FROM Priorities ORDER BY Priority_ID')->fetchAll() as $c)echo "<option value='{$c['Priority_ID']}'>".e($c['Priority_Name'])."</option>";?></select></div></div>
<label class="form-label mt-2">Attachment (optional, 5 MB max)</label><input class="form-control" type="file" name="file">
<button class="btn btn-primary mt-3">Submit ticket</button></form></div></div><?php foot();
