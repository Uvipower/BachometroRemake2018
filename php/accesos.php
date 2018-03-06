<?php

require("conexiones.php");
//variables 
$usuario 			= $_POST['username'];
$contrasena 		= $_POST['password'];
//si quiere mantener la sesion
if (isset($_POST['mantener_sesion'])) {
	$mantener_sesion = 1;
}
else{
	$mantener_sesion = 0;
}


//crear objeto
$x=new conexiones();
//uso de su funcion
$x->login($usuario, $contrasena, $mantener_sesion);


?>