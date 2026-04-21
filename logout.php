logout<?php
session_start();
session_destroy(); // Destrói a "pulseira" de entrada
header("Location: login.php"); // Manda de volta para o início
exit();
?>