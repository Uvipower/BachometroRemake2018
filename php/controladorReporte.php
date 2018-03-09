<?php
//INICIA SESIONES ACTIVAS
session_start();
//INCLUIR LA CLASE NECESARIA
require("claseReporte.php");
//OPCION TOMADA POR POST
$opcion = $_POST['opcion'];
//CREACION DEL OBJETO
$reporte = new claseReporte();
//SWITCH DE POSIBLES
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
        //si cargaron imagen
        if(!empty($_FILES['imagen'])){
            //cambia el nombre de la imagen por el folio del reporte y la guarda en la carpeta reportes
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