<?php
ob_start();
session_start();
unset($_SESSION['stock_user_name']);
unset($_SESSION['stock_user_email']);
unset($_SESSION['stock_user_role']);
session_destroy();
header("Location: http://localhost/stock/");
exit;
?>