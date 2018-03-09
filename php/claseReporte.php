<?php

//session_start();

require("claseBD.php");

class claseReporte {
    //variables
    public $idReporte;
    public $fechaDelReporte;
    public $latitud;
    public $longitud;
    public $urlMapa;
    public $referencia;
    public $comentarioCiudadano;
    public $srcImagen;
    public $idUsuario;

    public $BD;

    //constructor
    public function __construct(){
        try{
            $this->BD = new claseBD();
        }
        catch (Exception $e){
            //si falla la conexion
            echo "Ha surgido un error.<br>" . $e->getMessage();
        }
    }

    //getters y setters

    /**
     * @return mixed
     */
    public function getSrcImagen()
    {
        return $this->srcImagen;
    }

    /**
     * @param mixed $srcImagen
     */
    public function setSrcImagen($srcImagen)
    {
        $this->srcImagen = $srcImagen;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * @return mixed
     */
    public function getIdReporte()
    {
        return $this->idReporte;
    }

    /**
     * @param mixed $id_reporte
     */
    public function setIdReporte($idReporte)
    {
        $this->id_reporte = $idReporte;
    }

    /**
     * @return mixed
     */
    public function getFechaDelReporte()
    {
        return $this->fechaDelReporte;
    }

    /**
     * @param mixed $fechaDelReporte
     */
    public function setFechaDelReporte($fechaDelReporte)
    {
        $this->fechaDelReporte = $fechaDelReporte;
    }

    /**
     * @return mixed
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * @param mixed $latitud
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    /**
     * @return mixed
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * @param mixed $longitud
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    }

    /**
     * @return mixed
     */
    public function getUrlMapa()
    {
        return $this->urlMapa;
    }

    /**
     * @param mixed $urlMapa
     */
    public function setUrlMapa($urlMapa)
    {
        $this->urlMapa = $urlMapa;
    }

    /**
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param mixed $referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }

    /**
     * @return mixed
     */
    public function getComentarioCiudadano()
    {
        return $this->comentarioCiudadano;
    }

    /**
     * @param mixed $comentarioCiudadano
     */
    public function setComentarioCiudadano($comentarioCiudadano)
    {
        $this->comentarioCiudadano = $comentarioCiudadano;
    }

    //registrar reporte
    public function registrarReporte($latitud, $longitud, $referencia, $descripcion, $nombreArchivo){
        //array de respuesta
        $json_data = array();
        try {
            //obtener fecha y hora del servidor
            $fechaHoraServidor = date('Y-m-d');
            //empieza la transaccion
            $this->BD->beginTransaction();
            //registro del bache
            ////query de registro de los datos del bache
            $sentencia="INSERT INTO reportes (Fecha, Latitud, Longitud, Referencia, Comentario, Id_usuario) VALUES (?,?,?,?,?,?)";
            //preparar datos ha insertar
            $insertar = $this->BD->prepare($sentencia);
            //ejecuta e inserta variables
            $idUsuario = $_SESSION['id'];
            $insertar->execute(array($fechaHoraServidor, $latitud, $longitud, $referencia, $descripcion, $idUsuario));
            //id del registro realizado
            $idReporte = $this->BD->lastInsertId();

            //si subio la imagen
            if($nombreArchivo!=""){
                //obtener la extension de la imagen
                $extensionImagen = substr($nombreArchivo, -4);
                //nombre del nuevo archivo
                $nombreImagenNueva = "../reportes/".$idReporte.$extensionImagen;
                //renombrar imagen
                rename ($nombreArchivo, $nombreImagenNueva);
                //actualizar para poner el id del reporte como nombre de la imagen
                $sentencia="UPDATE reportes SET Imagen=? WHERE Id_reporte=?";
                //preparar datos ha insertar
                $actualizar = $this->BD->prepare($sentencia);
                $actualizar->execute(array($nombreImagenNueva, $idReporte));
            }

            //query
            $sentencia="CALL registroBacheHistorial(?,?)";
            //preparar datos ha insertar
            $insertar = $this->BD->prepare($sentencia);
            //ejecuta e inserta variables
            $insertar->execute(array($fechaHoraServidor, $idReporte));
            //toma el valor que se da de respuesta al execute
            $rows = $this->BD->commit();
            if($rows){
                //retornar mensaje de confirmación
                $json_data['success'] = true;
            }
            else{
                //rollback regresa al estado original
                $this->BD->rollBack();
                $json_data['success'] = false;
                $json_data['mensaje'] = "No es posible reportar bache, intente más tarde";
            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }
    //funcion consultar informacion funcion general
    public function consultarReportes(){
		
        $json_data = array();
		
        try {
			$idUsuario = $_SESSION['id'];
			$tipoUsuario = $_SESSION['tipoUsuario'];
			$query = "";
            //consulta de los baches registrados
            //aqui se distingue que tipo de usuario es y que informacion va a retornar
			if($tipoUsuario == 1){
				$query = "select r.Id_reporte, r.Fecha, r.Referencia, r.Comentario, rh.Estatus, rh.Fecha as Fecha2 from reportes r inner join reporte_historial rh on (r.Id_reporte=rh.Id_reporte) inner join (select MAX(Id_reporte_historial) Id_reporte_historial from reporte_historial group by Id_reporte) tmp on (rh.Id_reporte_historial=tmp.Id_reporte_historial)";
			}
			else{
				$query = "select r.Id_reporte, r.Fecha, r.Referencia, r.Comentario, rh.Estatus, rh.Fecha as Fecha2 from reportes r inner join reporte_historial rh on (r.Id_reporte=rh.Id_reporte) inner join (select MAX(Id_reporte_historial) Id_reporte_historial from reporte_historial group by Id_reporte) tmp on (rh.Id_reporte_historial=tmp.Id_reporte_historial) where r.Id_usuario = ?";
			}
            //preparar datos ha insertar
            $consulta = $this->BD->prepare($query);
            //ejecuta e inserta variables
			if($tipoUsuario == 1){
				$consulta->execute();
			}
			else{
				$consulta->execute(array($idUsuario));
			}
            //Para traer todos los registros
            $datosConsultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            //si la consulta trae informacion
            if($datosConsultados){
                //se transforma la consulta en json
                $json_data['success'] = true;
                $json_data['reportes'] = $datosConsultados;
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
                $json_data['mensaje'] = 'Error al consultar informacion';

            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //funcion consultar informacion funcion general
    public function consultarClusterers(){
        
        $json_data = array();
        
        try {
            $idUsuario = $_SESSION['id'];
            $tipoUsuario = $_SESSION['tipoUsuario'];
            $query = "";
            //consulta de los baches registrados
            if($tipoUsuario == 1){
                $query = "select Latitud, Longitud from reportes r";
            }
            else{
                $query = "select Latitud, Longitud from reportes r where r.Id_usuario = ?";
            }
            //preparar datos ha insertar
            $consulta = $this->BD->prepare($query);
            //ejecuta e inserta variables
            if($tipoUsuario == 1){
                $consulta->execute();
            }
            else{
                $consulta->execute(array($idUsuario));
            }
            //Para traer todos los registros
            $datosConsultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            //si la consulta trae informacion
            if($datosConsultados){
                //se transforma la consulta en json
                $json_data['success'] = true;
                $json_data['reportes'] = $datosConsultados;
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
                $json_data['mensaje'] = 'Error al consultar informacion';

            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //esta de prueba aun

    public function consultarClusterers2(){
        
        $json_data = array();
        
        try {
            $idUsuario = $_SESSION['id'];
            $tipoUsuario = $_SESSION['tipoUsuario'];
            $query = "";
            //consulta de los baches registrados
            if($tipoUsuario == 1){
                $query = "select r.Id_reporte, r.Fecha, r.Referencia, r.Comentario, r.Imagen rh.Estatus, rh.Fecha as Fecha2 from reportes r inner join reporte_historial rh on (r.Id_reporte=rh.Id_reporte) inner join (select MAX(Id_reporte_historial) Id_reporte_historial from reporte_historial group by Id_reporte) tmp on (rh.Id_reporte_historial=tmp.Id_reporte_historial)";
            }
            else{
                $query = "select r.Id_reporte, r.Fecha, r.Referencia, r.Comentario, r.Imagen rh.Estatus, rh.Fecha as Fecha2 from reportes r inner join reporte_historial rh on (r.Id_reporte=rh.Id_reporte) inner join (select MAX(Id_reporte_historial) Id_reporte_historial from reporte_historial group by Id_reporte) tmp on (rh.Id_reporte_historial=tmp.Id_reporte_historial) where r.Id_usuario = ?";
            }
            //preparar datos ha insertar
            $consulta = $this->BD->prepare($query);
            //ejecuta e inserta variables
            if($tipoUsuario == 1){
                $consulta->execute();
            }
            else{
                $consulta->execute(array($idUsuario));
            }
            //Para traer todos los registros
            $datosConsultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            //si la consulta trae informacion
            if($datosConsultados){
                //se transforma la consulta en json
                $json_data['success'] = true;
                $json_data['reportes'] = $datosConsultados;
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
                $json_data['mensaje'] = 'Error al consultar informacion';

            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //consultar las coordenadas del reporte
	public function coordenadasReporte($idReporte){
		
        $json_data = array();
		
        try {
			//query apra consultar las coordenadas del reporte
			$query = "select Latitud, Longitud from reportes r where r.Id_reporte = ?";
			
            //preparar datos ha insertar
            $consulta = $this->BD->prepare($query);
            //ejecuta e inserta variables
			$consulta->execute(array($idReporte));
			//Para traer todos los registros
            $datosConsultados = $consulta->fetch();
            //si la consulta trae informacion
            if($datosConsultados){
                //se transforma la consulta en json
                $json_data['success'] = true;
                $json_data['latitud'] = $datosConsultados['Latitud'];
				$json_data['longitud'] = $datosConsultados['Longitud'];
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
                $json_data['mensaje'] = 'Error al consultar informacion';

            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //consultar el src de la imagen del reporte
	
	public function imagenReporte($idReporte){
		
        $json_data = array();
		
        try {
			//query de busqueda del src de la imagen del reporte
			$query = "select Imagen from reportes r where r.Id_reporte = ?";
            //preparar datos ha insertar
            $consulta = $this->BD->prepare($query);
            //ejecuta e inserta variables
			$consulta->execute(array($idReporte));
			//Para traer todos los registros
            $datosConsultados = $consulta->fetch();
            //si la consulta trae informacion
            if($datosConsultados['Imagen'] != ""){
                //se transforma la consulta en json
                $json_data['success'] = true;
				$json_data['imagen'] = $datosConsultados['Imagen'];
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }
	

    //consultar el historial de avance del reporte
    public function historialReporte($idReporte){
        $json_data = array();
        try{
            //query de busqueda
            $query = "select Fecha, Descripcion, Estatus from reporte_historial where Id_reporte = ? order by Id_reporte_historial desc";
            //prepara los datos
            $consulta = $this->BD->prepare($query);
            //ejeculta e inserta variables
            $consulta->execute(array($idReporte));
            //obtiene la respuesta de la consulta
            $datosConsultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            //si la consulta trae informacion
            if($datosConsultados){
                //se anexa en el json registro para indicar que la consulta ha sido satisfactoria
                $json_data['success'] = true;
                //se llena json con los datos solicitados
                $json_data['historial'] = $datosConsultados;
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //consulta el comentario del ciudadano sobre el reporte
    public function comentarioReporte($idReporte){
        $json_data = array();
        try{
            //query de consulta
            $query = "select Comentario from reportes r where r.Id_reporte = ?";
            //prepara los datos
            $consulta = $this->BD->prepare($query);
            //ejeculta e inserta variables
            $consulta->execute(array($idReporte));
            //obtiene la respuesta de la consulta
            $datosConsultados = $consulta->fetch();
            //si la consulta trae informacion
            if($datosConsultados){
                //se anexa en el json registro para indicar que la consulta ha sido satisfactoria
                $json_data['success'] = true;
                //se llena json con los datos solicitados
                $json_data['comentario'] = $datosConsultados['Comentario'];
            }
            else{
                //en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
                $json_data['success'] = false;
            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //eliminar reporte o reportes
    public function eliminarReportes($idReportes){
        $json_data = array();
        try {
			$idUsuario = $_SESSION['id'];
            //eliminacion del reporte o reportes
            for($i = 0; $i < count($idReportes); $i++){
                //empieza la transaccion
                $this->BD->beginTransaction();
                $sentencia="CALL eliminarReporte(?,?)";
                //preparar datos ha eliminar
                $eliminacion = $this->BD->prepare($sentencia);
                //ejecuta e inserta variables
                $eliminacion->execute(array($idReportes[$i], $idUsuario));
                //respuesta
                //toma el valor que se da de respuesta al execute
                $rows = $this->BD->commit();
                //$rows = $insertar->fetch();
                if($rows){
                    //Si la actualizacion fue exitosa
                    $json_data['success'] = true;
                    //$json_data['mensaje'] = "Eliminación exitosa";
                }
                else{
                    //rollback regresa al estado original
                    $this->BD->rollBack();
                    $json_data['success'] = false;
                    //$json_data['mensaje'] = "Error en la eliminación del reporte";
                }
            }

        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }
}
