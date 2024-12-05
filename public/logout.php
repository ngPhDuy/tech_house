<?php
session_start();

$_SESSION = array();

session_destroy();
setcookie("ten_dang_nhap", "", time() - 3600, "/");
setcookie("phan_loai_tk", "", time() - 3600, "/");
setcookie("ho_ten", "", time() - 3600, "/");
header('Location: ../public/login.php');

exit();