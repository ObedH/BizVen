<?php
$dbFile = '/var/www/database/mydb.sqlite';
$db = new SQLite3($dbFile);
$db->exec("PRAGMA foreign_keys = ON;");
?>
