<?php 

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and $_SESSION['tipo'] == 2))
{
	require("conexiones.php");
	$Id_reporte 		= $_POST['Id_reporte'];
	//uso de su funcion
	$x=new conexiones();
	$x->consultarReporteDetalles($Id_reporte);
}
else{
  header("Location: ../");
}
?>