<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "test_market";

try
{
  $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  define('BASE_URL', 'http://localhost/stock');
}
catch(PDOException $e)
{
  echo "Connection failed: " . $e->getMessage();
}
?>

