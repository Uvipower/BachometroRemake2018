<?php

require("conexiones.php");

$apellidoPaterno 		= $_POST['apellidoPaterno'];
$apellidoMaterno 		= $_POST['apellidoMaterno'];
$nombres		 		= $_POST['nombres'];
$usuario				= $_POST['usuario'];
$correoElectronico		= $_POST['correoElectronico'];
$confirmarContrasena1	= $_POST['confirmarContrasena1'];
$confirmarContrasena	= $_POST['confirmarContrasena'];




//crear objeto
$x=new conexiones();
//uso de su funcion
$x->registro($apellido_paterno, $apellido_materno, $nombres, $usuario, $contrasena, $correo);


?>