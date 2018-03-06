<?php

session_start();

require("claseReporte.php");

$opcion = $_POST['opcion'];
//$idUsuario = $_SESSION['id'];

$reporte = new claseReporte();

switch ($opcion){
    case "consultarReportes":
        $reporte->consultarReportes();
        break;
    case "eliminarReportes":
		$idReportes = $_POST['idReportes'];
		$reporte->eliminarReportes($idReportes);
        break;
    case "historialReporte":
		$idReporte = $_POST['idReporte'];
		$reporte->historialReporte($idReporte);
        break;
    case "coordenadasReporte":
		$idReporte = $_POST['idReporte'];
		$reporte->coordenadasReporte($idReporte);
        break;
    case "imagenReporte":
		$idReporte = $_POST['idReporte'];
		$reporte->imagenReporte($idReporte);
        break;
    case "comentarioReporte":
		$idReporte = $_POST['idReporte'];
		$reporte->comentarioReporte($idReporte);
        break;
    case "registrarReporte":
        $latitud                = $_POST['latitud'];
        $longitud               = $_POST['longitud'];
        $referencia             = $_POST['referencia'];
        $descripcion            = $_POST['descripcion'];
        $carpeta                = "../reportes/";
        $direccionArchivo       = "";

        if(!empty($_FILES['imagen'])){
            $nombreImagen = $_FILES['imagen']['name'];
            $tmpArchivo = $_FILES['imagen']['tmp_name'];
            $direccionArchivo = $carpeta.$nombreImagen;
            if (move_uploaded_file($tmpArchivo, $direccionArchivo)){
                //echo "Direccion archivo: ".$direccion_archivo;    
            }
        }
        $reporte->registrarReporte($latitud, $longitud, $referencia, $descripcion, $direccionArchivo);
        break;
    case "consultarClusterers":
        $reporte->consultarClusterers();
        break;
    default:
        break;
}