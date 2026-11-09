<?php require 'inc/bootstrap.php'; need(); $u=user();
$f=q('SELECT a.*,t.User_ID,t.Technician_ID FROM Attachments a JOIN Tickets t USING(Ticket_ID) WHERE Attachment_ID=?',[(int)($_GET['id']??0)])->fetch();
if(!$f||($u['role']==='End User'&&$f['User_ID']!=$u['id'])||($u['role']==='Technician'&&$f['Technician_ID']!=myTech()))die('Access denied.');
$p=__DIR__.'/storage/attachments/'.basename($f['File_Path']);if(!is_file($p))die('File missing.');
header('X-Content-Type-Options: nosniff');header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.str_replace('"','',$f['File_Name']).'"');readfile($p);
