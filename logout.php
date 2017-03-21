<?php 
session_start();
unset($_SESSION['Suser']);
session_destroy();
header("location:login.php");
?>
