<?php require 'inc/bootstrap.php'; need(); $u=user();
if($_SERVER['REQUEST_METHOD']==='POST'&&$u['role']!=='End User'){
  $t=trim($_POST['title']??'');$c=trim($_POST['content']??'');
  if(!$t||!$c)flash('Title and solution steps are required.',true);
  else{q('INSERT INTO Knowledge_Base(Category_ID,Title,Content,Created_By)VALUES(?,?,?,?)',[(int)$_POST['category'],$t,$c,$u['id']]);audit('kb-add');flash('Article saved.');}
  go('kb.php');}
$s=trim($_GET['q']??'');
$rows=q('SELECT k.*,c.Category_Name FROM Knowledge_Base k JOIN Categories c USING(Category_ID) WHERE ?="" OR k.Title LIKE ? OR k.Content LIKE ? ORDER BY k.Created_At DESC',[$s,"%$s%","%$s%"])->fetchAll();
head('Knowledge Base');?>
<h3>Knowledge base</h3><form class="card card-body mb-3"><input class="form-control" name="q" placeholder="Search by keyword" value="<?=e($s)?>"></form>
<?php foreach($rows as $r)echo '<div class="card mb-2"><div class="card-body"><h5>'.e($r['Title']).'</h5><span class="badge bg-secondary">'.e($r['Category_Name']).'</span><p class="mt-2">'.nl2br(e($r['Content'])).'</p></div></div>';
if(!$rows)echo '<p class="text-muted">No articles match. <a href="ticket_create.php">Create a ticket</a> instead.</p>';
if($u['role']!=='End User'):?><div class="card"><div class="card-body"><h5>Add article</h5><form method="post"><?=tok()?><input class="form-control" name="title" placeholder="Title">
<select class="form-select mt-2" name="category"><?php foreach(q('SELECT * FROM Categories')->fetchAll() as $c)echo "<option value='{$c['Category_ID']}'>".e($c['Category_Name']).'</option>';?></select>
<textarea class="form-control mt-2" name="content" rows="3" placeholder="Solution steps"></textarea><button class="btn btn-primary mt-2">Save article</button></form></div></div><?php endif;foot();
