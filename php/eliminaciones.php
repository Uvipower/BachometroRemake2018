<?php 

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and $_SESSION['tipo'] == 2))
{
	require("conexiones.php");
	$Id_usuario 		= $_SESSION['id'];
	$Id_reporte 		= $_POST['Id_reporte'];
	$src_imagen 		= $_POST['src_imagen'];
	if(!empty($src_imagen)){
		unlink($src_imagen);
	}
	
	$x=new conexiones();
	echo $x->eliminarReporte($Id_reporte, $Id_usuario);
}
else{
  header("Location: ../");
}
?>