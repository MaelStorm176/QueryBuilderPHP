<?php
$db_name = dirname(__FILE__).'/Chinook';
$pdo = new PDO('sqlite:'.$db_name);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>