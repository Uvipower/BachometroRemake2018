<?php 

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']))){
	require("conexiones.php");

	$Id_usuario 		= $_SESSION['id'];
	$Apellido_paterno 	= $_POST['apellido_paterno'];
	$Apellido_materno 	= $_POST['apellido_materno'];
	$Nombres 			= $_POST['nombres'];
	$Correo				= $_POST['correo'];
	$Anterior_password 	= $_POST['anterior_password'];
	$Nueva_password 	= $_POST['nueva_password'];

	$x=new conexiones();

	echo $x->modificarInformacionUsuario($Apellido_paterno, $Apellido_materno, $Nombres, $Correo, $Anterior_password, $Nueva_password, $Id_usuario);
}
else{
  header("Location: ../");
}
?>