<?php
//inicia sessiones
session_start();
//declara las sessiones en nulos
unset($_COOKIE['id']);
unset($_SESSION['id']);
unset($_SESSION['tipo']);
//destruye las sesiones
session_destroy();
//redirecciona
header("Location: ../");
?>