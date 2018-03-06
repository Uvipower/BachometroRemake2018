<?php
/*
empleados 1

usuario 2 
 */

class conexiones extends PDO{

	private $sgbd				= "mysql";
	private $host				= "localhost";
	private $basedatos 			= "bachometro";
	private $user 				= "root";
	private $pass 				= "";
	private $conexion 			= "";
	//private $json_data			= array();
	//private $resultado;

	//constructor
	public function __construct(){

		try{
			//indicar moto y base de datos
			$this->conexion = $this->sgbd.":host=".$this->host.";dbname=".$this->basedatos;
			//crear conexion
	        parent::__construct($this->conexion, $this->user, $this->pass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        //echo "Conexion exitosa";
	    }
	    //excepcion
	    catch (PDOException $e){
	    	//si falla la conexion
        	echo 'Ha surgido un error y no se puede conectar a la base de datos<br>' . $e->getMessage();
        	exit;
	    }
	}

	//EMPIEZA FUNCIONES INDEX.HTML PAGINA PRINCIPAL "LOGIN"
	public function login($usuario, $contrasena, $mantener_sesion){
		try{
			$contrasena_sha = sha1($contrasena);
			//array de respuesta
			$json_data = array();
			//query de autenticacion
		    $sentencia="CALL login(:usuario, :contrasena)";
			//prepara query
			$consulta = parent::prepare($sentencia);
			//inserta variables
	        $consulta->bindParam(':usuario', $usuario);
	        $consulta->bindParam(':contrasena', $contrasena_sha);
	        //ejecuta
	        $consulta->execute();
	        //extraer variables
		    if($row = $consulta->fetch()){
		    	//crear sesiones
		    	session_start();
		    	//parametro para indicar que si se obtuvo acceso al sistema
		    	$json_data['success'] = true;
		    	$json_data['message'] = "Acceso correcto";
		    	//tipo de usuario
		    	$json_data['data']['type_user'] = $row['Tipo_usuario'];
		    	//sessiones 
		    	$_SESSION['id'] = $row['Id_usuario'];
				$_SESSION['tipo'] = $row['Tipo_usuario'];
				if ($mantener_sesion == 1) {
					//si presiono el mantener session
					//cookie
					setcookie('id', $row['Id_usuario']);
				}
		    }
		    else{
		    	//en caso de que no sea correcto el acceso
		    	$json_data['success'] = false;
		    	$json_data['message'] = "Usuario o contraseña incorrecta";
		    }
		    //regresar json forzado a objeti
		    echo json_encode($json_data, JSON_FORCE_OBJECT);
		}catch(PDOException $e){
			print $e;
		}
	}
//FUNCION REGISTRO usuario
	public function registro($apellido_paterno, $apellido_materno, $nombres, $usuario, $contrasena, $correo){
		try{

			//verificar que no este dado de alta el usuario
			$sentencia="SELECT Usuario FROM usuarios WHERE Usuario= :usuario";
			//preparar query
			$consulta = parent::prepare($sentencia);
			//insertar variables
	        $consulta->bindParam(':usuario', $usuario);
	        //ejecutar 
	        $consulta->execute();
	        //extraer variables
	    	$rows = $consulta->fetch();
			//$rows->fetchAll(PDO::FETCH_ASSOC);
			//sida positivo es que ya esta dado de alta
			if($rows){
		    	//retornar mensaje de que ya existe usuario
		    	echo "Este nombre de usuario ya esta dado de alta";
		    }
		    else{
		    	//verifica si el correo no esta dado de alta
		    	$sentencia="SELECT Usuario FROM usuarios WHERE Correo= :correo";
		    	//prepara query
		    	$consulta = parent::prepare($sentencia);
		    	//inserta variables
				$consulta->bindParam(':correo', $correo);
				//ejecutar
		        $consulta->execute();
		        //extraer variables
		    	$rows = $consulta->fetch();
		    	//si da positivo es que ya esta dado de alta
				if($rows){
			    	//retornar mensaje de que y esta este correo
			    	echo "Este correo ya esta dado de alta";
			    }
			    else{
			    	//empieza la transaccion
					parent::beginTransaction();
			    	//registro del usuario
			    	////query de registro de los datos básicos de usuario 
					$sentencia="INSERT INTO usuario_datos (Apellido_paterno, Apellido_materno, Nombres) VALUES (?,?,?)";
					//preparar el sql
					$insertar = parent::prepare($sentencia);
					//ejecutar e inserta variables
					$insertar->execute(array($apellido_paterno, $apellido_materno, $nombres));
					//id de la inserccion
					$Id_usuario_datos = parent::lastInsertId();
					//registro de los datos del usuario
					$sentencia="CALL registroUsuario(?,?,?,?)";
				    $contrasena_sha = sha1($contrasena);
				    //prepara el sql
				    $insertar = parent::prepare($sentencia);
				    //ejecutar e inserta variables
			        $insertar->execute(array($usuario, $contrasena_sha, $correo, $Id_usuario_datos));
			        //toma el valor que se da de respuesta al execute
			        $rows = parent::commit();
			        if($rows){
				    	//retornar mensaje de confirmación
				    	echo "Usuario dado de alta";
				    }
				     else{
				     	//rollback regresa al estado original
				    	parent::rollBack();
				    	echo "Error en el registro de usuario";
				    }
			    }
		    }
		}
		catch(PDOException $e){
			echo $e;
		}
	}
//funcion registrar bache
	public function regristrarBache($latitud, $longitud, $referencia, $descripcion, $nombre_archivo, $Id){
		try {
			//obtener fecha y hora del servidor
			//$x= strtotime(date('Y-m-d'));
			$fechaHoraServidor = date('Y-m-d');
			//empieza la transaccion
			parent::beginTransaction();
			//registro del bache
			////query de registro de los datos del bache 
			$sentencia="INSERT INTO reportes (Fecha, Latitud, Longitud, Referencia, Comentario, Id_usuario) VALUES (?,?,?,?,?,?)";
			//preparar datos ha insertar
			$insertar = parent::prepare($sentencia);
			//ejecuta e inserta variables
			$insertar->execute(array($fechaHoraServidor, $latitud, $longitud, $referencia, $descripcion, $Id));
			//id del registro realizado
			$Id_reporte = parent::lastInsertId();
			
			//si subio la imagen
			if($nombre_archivo!=""){
				//obtener la extension de la imagen
				$extension_imagen = substr($nombre_archivo, -4);
				//nombre del nuevo archivo
				$nombre_imagen_nueva = "../reportes/".$Id_reporte.$extension_imagen;
				//renombrar imagen
				rename ($nombre_archivo, $nombre_imagen_nueva);
				//actualizar para poner el id del reporte como nombre de la imagen
				$sentencia="UPDATE reportes SET Imagen=? WHERE Id_reporte=?";
				//preparar datos ha insertar
				$actualizar = parent::prepare($sentencia);
				$actualizar->execute(array($nombre_imagen_nueva, $Id_reporte));
			}
			
			//query
			$sentencia="CALL registroBacheHistorial(?,?)";
			//preparar datos ha insertar
			$insertar = parent::prepare($sentencia);
			//ejecuta e inserta variables
			$insertar->execute(array($fechaHoraServidor, $Id_reporte));
			//toma el valor que se da de respuesta al execute
			$rows = parent::commit();
			if($rows){
				//retornar mensaje de confirmación
				print $referencia;
			}
			else{
				//rollback regresa al estado original
				parent::rollBack();
				print "No es posible reportar bache, intente más tarde";
			}
		} catch (Exception $e) {
			print $e;
		}
	}

//funcion consultar informacion funcion general 
	public function consultarInformacionUsuario($Id_usuario){
		//array que se convertira en json
		$json_data = array();
		try {
			//consulta utilizando innerjoin
			$sentencia="CALL informacionUsuario(?)";
			//preparar datos ha insertar
			$consulta = parent::prepare($sentencia);
			//ejecuta e inserta variables
			$consulta->execute(array($Id_usuario));
			//obtiene la respuesta de la ejecucion, unicamente el primer registro
			$datosConsultados = $consulta->fetch(PDO::FETCH_ASSOC);
			//si la consulta trae informacion
			if($datosConsultados){
				//se anexa en el json registro para indicar que la consulta ha sido satisfactoria
				$json_data['success'] = true;
				//se llena json con los datos solicitados
       			$json_data['usuario'] = $datosConsultados;
		    }
		    else{
		    	//en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
		    	$json_data['success'] = false;
        		$json_data['mensaje'] = 'Error al consultar informacion';
		    }
		    
		}
		catch (Exception $e) {
			$json_data['success'] = false;
			$json_data['message'] = $e;
		}
		//json como respuesta, se fuerzaa que sea un objeto tipo json
		echo json_encode($json_data, JSON_FORCE_OBJECT);
	}
	//funcion consultar informacion funcion general 
	public function consultarBaches($Id_usuario){
		try {
			//$json_data = array();
			//consulta de los baches registrados
			//array que se convertira en json
			$sentencia="CALL consultarReportes(?)";
			//preparar datos ha insertar
			$consulta = parent::prepare($sentencia);
			//ejecuta e inserta variables
			$consulta->execute(array($Id_usuario));
			//Para traer todos los registros
			$datosConsultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
			//si la consulta trae informacion
			if($datosConsultados){
				//se transforma la consulta en json
       			$json_data['reportes'] = $datosConsultados;
		    }
		    else{
		    	//en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
		    	$json_data['success'] = false;
        		$json_data['data']['message'] = 'Error al consultar informacion';

		    }
		    //json como respuesta, se fuerzaa que sea un objeto tipo json
		    echo json_encode($json_data);
		} catch (Exception $e) {
			print $e;
		}
	}
	public function consultarReporteDetalles($Id_reporte){
		try{
			
			$sentencia = "CALL consultarReporteDetalles(?)";
			$consulta = parent::prepare($sentencia);
			$consulta->execute(array($Id_reporte));
			//ejeculta e inserta variables	
			//respuesta
			//obtiene la respuesta de la consulta
			$datosConsultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
			//si la consulta trae informacion
			if($datosConsultados){
				//se anexa en el json registro para indicar que la consulta ha sido satisfactoria
				//$json_data['success'] = true;
				//se llena json con los datos solicitados
       			$json_data['historial'] = $datosConsultados;
		    }
		    else{
		    	//en caso de fallar, se manda registro indicando que no se ha podido extraer informacion
		    	$json_data['success'] = false;
        		$json_data['data']['message'] = 'Error al consultar informacion';
		    }
		    echo json_encode($json_data);
		}
		catch (Exception $e) {
			print $e;
		}
	}
	//funcion modificar informacion usuario 
	public function modificarInformacionUsuario($Apellido_paterno, $Apellido_materno, $Nombres, $Correo, $Anterior_password, $Nueva_password, $Id_usuario){
		try {
			
			
			$sentencia="SELECT Id_usuario_datos FROM usuarios WHERE Id_usuario = ?;";
			//preparar datos ha insertar
			$actualizar = parent::prepare($sentencia);
			//ejecuta e inserta variables
			$actualizar->execute(array($Id_usuario));
			//obtiene la respuesta de la consulta
			$datosConsultados = $actualizar->fetch(PDO::FETCH_ASSOC);

			$Id_usuario_datos = $datosConsultados["Id_usuario_datos"];
			//print_r($datosConsultados["Id_usuario_datos"]);
			//si la consulta trae informacion
			if($datosConsultados){
				//empieza la transaccion
				parent::beginTransaction();
				//update de la informacion del usuario
				
				$Anterior_password_sha = sha1($Anterior_password);
				
				$sentencia="SELECT count(Contrasena) FROM usuarios WHERE Id_usuario = ? AND Contrasena = ?;";
				//preparar datos ha insertar
				$actualizar = parent::prepare($sentencia);
				//ejecuta e inserta variables
				$actualizar->execute(array($Anterior_password_sha, $Id_usuario));
				//respuesta
				//toma el valor que se da de respuesta al execute
				$rows = parent::commit();
				//$rows = $insertar->fetch();
				if($rows){
					//empieza la transaccion
					parent::beginTransaction();
					//Si la actualizacion fue exitosa
					$sentencia="UPDATE usuarios SET Contrasena = ?, Correo = ? WHERE Id_usuario = ?;";
					$actualizar = parent::prepare($sentencia);
					//ejecuta e inserta variables
					$Nueva_password_sha = sha1($Nueva_password);
					$actualizar->execute(array($Nueva_password_sha, $Correo, $Id_usuario));
					//respuesta
					//toma el valor que se da de respuesta al execute
					$rows = parent::commit();
					//$rows = $insertar->fetch();
					if($rows){
						//empieza la transaccion
						parent::beginTransaction();
						//update de la informacion del usuario
						$sentencia="CALL modificarInformacionUsuario(?,?,?,?)";
						//preparar datos ha insertar
						$actualizar = parent::prepare($sentencia);
						//ejecuta e inserta variables
						$actualizar->execute(array($Apellido_materno, $Apellido_paterno, $Nombres, $Id_usuario_datos));
						//respuesta
						//toma el valor que se da de respuesta al execute
						$rows = parent::commit();
						//$rows = $insertar->fetch();
						if($rows){
							print "Datos modificados correctamente";
						}
						else{
							//rollback regresa al estado original
							parent::rollBack();
							print "Error en la actualizacion de los datos del usuario";
						}
					}
					else{
						//rollback regresa al estado original
						parent::rollBack();
						print "Error en la actualizacion del correo y la contraseña del usuario";
					}
				}
				else{
					//rollback regresa al estado original
					parent::rollBack();
					print "Contraseña incorrecta";
				}
			}
			else{
				print "Problemas al consultar información basica del usuario";
			}
			
			
		} catch (Exception $e) {
			print $e;
		}
	}
	//funcion consultar informacion funcion general 
	public function eliminarReporte($Id_reporte, $Id_usuario){
		try {
			//eliminacion del reporte
			//empieza la transaccion
			parent::beginTransaction();
			$sentencia="CALL eliminarReporte(?,?)";
			//preparar datos ha eliminar
			$eliminacion = parent::prepare($sentencia);
			//ejecuta e inserta variables
			$eliminacion->execute(array($Id_reporte, $Id_usuario));
			//respuesta
			//toma el valor que se da de respuesta al execute
			$rows = parent::commit();
			//$rows = $insertar->fetch();
			if($rows){
				
				//Si la actualizacion fue exitosa
				print "Eliminación exitosa";
			}
			else{
				//rollback regresa al estado original
				parent::rollBack();
				print "Error en la eliminación del reporte";
			}
		} catch (Exception $e) {
			print $e;
		}
	}
}

?>
