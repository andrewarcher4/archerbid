<?php

session_start();
$value = $_POST['submit'];

$_SESSION['auctionID'] = $value;

echo "<script> location.href='./b-bidpage.html'; </script>";

?>

