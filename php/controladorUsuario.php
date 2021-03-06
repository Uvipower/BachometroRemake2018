<?php

require("claseUsuario.php");
//si manda alguna variables
if(isset($_POST['opcion'])){
		
	$opcion = $_POST['opcion'];
	//creación del objeto
	$usuario = new claseUsuario();
	switch ($opcion){
	    case "login":
			//variables 
			$usuarioLogin 			= $_POST['usuario'];
			$contrasenaLogin 		= $_POST['contrasena'];
			//si quiere mantener la sesion
			if (isset($_POST['mantenerSesion'])) {
				$mantenerSesion = 1;
			}
			else{
				$mantenerSesion = 0;
			}
	        $usuario->login($usuarioLogin, $contrasenaLogin, $mantenerSesion);
	        break;
	    case "registro":
			//variables
			$apellidoPaterno 		= $_POST['apellidoPaterno'];
			$apellidoMaterno 		= $_POST['apellidoMaterno'];
			$nombres		 		= $_POST['nombres'];
			$usuarioRegistro		= $_POST['usuario'];
			$correoElectronico		= $_POST['correoElectronico'];
			$confirmarContrasena1	= $_POST['confirmarContrasena1'];
			$confirmarContrasena	= $_POST['confirmarContrasena'];
			$usuario->registro($apellidoPaterno, $apellidoMaterno, $nombres, $usuarioRegistro, $confirmarContrasena, $correoElectronico);
	        break;
	}
}
//sino cierra sesión
else{
	cerrarSesion();
}

function cerrarSesion(){
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
}

?>