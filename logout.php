<?php



session_start();

setcookie('userID', '',   time() - 3600);
setcookie('username', '',  time() - 3600);
setcookie('firstname', '',  time() - 3600);
setcookie('lastname', '',  time() - 3600);
setcookie('role', '',  time() - 3600);

session_destroy();

echo "<script> location.href='./login.php'; </script>";


?>