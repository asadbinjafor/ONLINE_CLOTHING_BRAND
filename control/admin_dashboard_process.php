<?php
include_once '../model/mydb.php';
include_once 'admin_gate.php';

requireAdmin();

$mydb   = new MyDB();
$conn   = $mydb->createConn();
$counts = $mydb->getDashboardCounts($conn);
?>
