<?php
$db = mysql_connect('localhost', 'root', '');
if(!@mysql_connect('localhost', 'root', '') || !@mysql_select_db('mockbids'))
	die('couldn\'t connect to database!');
?>

