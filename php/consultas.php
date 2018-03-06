<?php 

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and $_SESSION['tipo'] == 2))
{
	require("conexiones.php");
	$Id_usuario 		= $_SESSION['id'];
	$x=new conexiones();
	if(isset($_POST['consulta'])){
		$Tipo_consulta 		= $_POST['consulta'];
		//uso de su funcion
		$x=new conexiones();
		$x->consultarInformacionUsuario($Id_usuario);
	}
	else{
		$x=new conexiones();
		echo $x->consultarBaches($Id_usuario);
	}

}
else{
  header("Location: ../");
}
?>