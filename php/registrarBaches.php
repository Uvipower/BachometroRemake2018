<?php

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and isset($_SESSION['tipo'])))
{

	require("conexiones.php");

	$latitud 				= $_POST['latitud'];
	$longitud 				= $_POST['longitud'];
	$referencia				= $_POST['referencia'];
	$descripcion			= $_POST['descripcion'];
	$Id_usuario 			= $_SESSION['id'];
	$carpeta	 			= "../reportes/";
	$direccion_archivo 		= "";

	if(!empty($_FILES['imagen'])){
		$nombre_imagen = $_FILES['imagen']['name'];
		$tmp_archivo = $_FILES['imagen']['tmp_name'];
		$direccion_archivo = $carpeta.$nombre_imagen;
		if (move_uploaded_file($tmp_archivo, $direccion_archivo)){
			//echo "Direccion archivo: ".$direccion_archivo;	
		}
	}
	
	//crear objeto
	$x=new conexiones();
	//uso de su funcion
	$x->regristrarBache($latitud, $longitud, $referencia, $descripcion, $direccion_archivo, $Id_usuario);

}
else{
  header("Location: ../");
}
?>