<?php

require_once("claseBD.php");

class claseUsuario
{
    public $idUsuario;
    public $idUsuarioDatos;
    public $nombres;
    public $apellidoPaterno;
    public $apellidoMaterno;
    public $correo;
    public $tipoUsuario;
    public $usuario;
    public $BD;

    public function __construct(){
        try{
            $this->BD = new claseBD();
        }
        catch (Exception $e){
            //si falla la conexion
            echo "Ha surgido un error.<br>" . $e->getMessage();
        }
    }

    /**
     * @return mixed
     */
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    /**
     * @param mixed $tipoUsuario
     */
    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = $tipoUsuario;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getIdUsuarioDatos()
    {
        return $this->idUsuarioDatos;
    }

    /**
     * @param mixed $idUsuarioDatos
     */
    public function setIdUsuarioDatos($idUsuarioDatos)
    {
        $this->idUsuarioDatos = $idUsuarioDatos;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;
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
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param mixed $nombres
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    /**
     * @return mixed
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }

    /**
     * @param mixed $apellidoPaterno
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    /**
     * @return mixed
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }

    /**
     * @param mixed $apellidoMaterno
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function login($usuario, $contrasena, $mantenerSesion){
        //array de respuesta
        $json_data = array();
        try{

            $contrasenaSha = sha1($contrasena);
            //query de autenticacion
            $sentencia="CALL login(:usuario, :contrasena)";
            //prepara query
            $consulta = $this->BD->prepare($sentencia);
            //inserta variables
            $consulta->bindParam(':usuario', $usuario);
            $consulta->bindParam(':contrasena', $contrasenaSha);
            //ejecuta
            $consulta->execute();
            //extraer variables
            if($row = $consulta->fetch()){
                //crear sesiones
                session_start();
                //parametro para indicar que si se obtuvo acceso al sistema
                $json_data['success'] = true;
                $json_data['mensaje'] = "Acceso correcto";
                //tipo de usuario
                $json_data['tipoUsuario'] = $row['Tipo_usuario'];
                //sessiones
                $_SESSION['id'] = $row['Id_usuario'];
                $_SESSION['tipoUsuario'] = $row['Tipo_usuario'];
                if ($mantenerSesion == 1) {
                    //si presiono el mantener session
                    //cookie
                    setcookie('id', $row['Id_usuario']);
                }
            }
            else{
                //en caso de que no sea correcto el acceso
                $json_data['success'] = false;
                $json_data['mensaje'] = "Usuario o contraseña incorrecta";
            }

        }
        catch(PDOException $e){
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //FUNCION REGISTRO usuario
    public function registro($apellidoPaterno, $apellidoMaterno, $nombres, $usuario, $contrasena, $correo){
        //array de respuesta
        $json_data = array();
        try{
            //verificar que no este dado de alta el usuario
            $sentencia="SELECT Usuario FROM usuarios WHERE Usuario= :usuario";
            //preparar query
            $consulta = $this->BD->prepare($sentencia);
            //insertar variables
            $consulta->bindParam(':usuario', $usuario);
            //ejecutar
            $consulta->execute();
            //extraer variables
            $rows = $consulta->fetch();
            //si da positivo es que ya esta dado de alta
            if($rows){
                //retornar mensaje de que ya existe usuario
                $json_data['success'] = false;
                $json_data['mensaje'] = "Este nombre de usuario ya esta dado de alta";
            }
            else{
                //verifica si el correo no esta dado de alta
                $sentencia="SELECT Usuario FROM usuarios WHERE Correo= :correo";
                //prepara query
                $consulta = $this->BD->prepare($sentencia);
                //inserta variables
                $consulta->bindParam(':correo', $correo);
                //ejecutar
                $consulta->execute();
                //extraer variables
                $rows = $consulta->fetch();
                //si da positivo es que ya esta dado de alta
                if($rows){
                    //retornar mensaje de que y esta este correo
                    $json_data['success'] = false;
                    $json_data['mensaje'] = "Este correo ya esta dado de alta";
                }
                else{
                    //empieza la transaccion
                    $this->BD->beginTransaction();
                    //registro del usuario
                    ////query de registro de los datos básicos de usuario
                    $sentencia="INSERT INTO usuario_datos (Apellido_paterno, Apellido_materno, Nombres) VALUES (?,?,?)";
                    //preparar el sql
                    $insertar = $this->BD->prepare($sentencia);
                    //ejecutar e inserta variables
                    $insertar->execute(array($apellidoPaterno, $apellidoMaterno, $nombres));
                    //id de la inserccion
                    $idUsuarioDatos = $this->BD->lastInsertId();
                    //registro de los datos del usuario
                    $sentencia="CALL registroUsuario(?,?,?,?)";
                    $contrasenaSha = sha1($contrasena);
                    //prepara el sql
                    $insertar = $this->BD->prepare($sentencia);
                    //ejecutar e inserta variables
                    $insertar->execute(array($usuario, $contrasenaSha, $correo, $idUsuarioDatos));
                    //toma el valor que se da de respuesta al execute
                    $rows = $this->BD->commit();
                    if($rows){
                        //retornar mensaje de confirmación
                        $json_data['success'] = true;
                        $json_data['mensaje'] = "Usuario dado de alta";
                    }
                    else{
                        //rollback regresa al estado original
                        $this->BD->rollBack();
                        $json_data['success'] = false;
                        $json_data['mensaje'] = "Error en el registro de usuario";
                    }
                }
            }
        }
        catch(PDOException $e){
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //regresar json forzado a objeto
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    public function consultarInformacionUsuario($idUsuario){
        //array que se convertira en json
        $json_data = array();
        try {
            //consulta utilizando innerjoin
            $sentencia="CALL informacionUsuario(?)";
            //preparar datos ha insertar
            $consulta = $this->BD->prepare($sentencia);
            //ejecuta e inserta variables
            $consulta->execute(array($idUsuario));
            //obtiene la respuesta de la ejecucion, unicamente el primer registro
            $datosConsultados = $consulta->fetch(PDO::FETCH_ASSOC);
            //si la consulta trae informacion
            if($datosConsultados){
                //se anexa en el json registro para indicar que la consulta ha sido satisfactoria
                $json_data['success'] = true;
                $this->setApellidoPaterno($datosConsultados['Apellido_paterno']);
                $this->setApellidoMaterno($datosConsultados['Apellido_materno']);
                $this->setNombres($datosConsultados['Nombres']);
                $this->setCorreo($datosConsultados['Correo']);
                $this->setTipoUsuario($datosConsultados['Tipo_usuario']);
                $this->setUsuario($datosConsultados['Usuario']);
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
        //json como respuesta, se fuerzaa que sea un objeto tipo json
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }

    //funcion modificar informacion usuario
    public function modificarInformacionUsuario($apellidoPaterno, $apellidoMaterno, $nombres, $correo, $oldPassword, $newPassword, $idUsuario){
        try {


            $sentencia="SELECT Id_usuario_datos FROM usuarios WHERE Id_usuario = ?;";
            //preparar datos ha insertar
            $actualizar = $this->BD->prepare($sentencia);
            //ejecuta e inserta variables
            $actualizar->execute(array($idUsuario));
            //obtiene la respuesta de la consulta
            $datosConsultados = $actualizar->fetch(PDO::FETCH_ASSOC);

            $idUsuarioDatos = $datosConsultados["Id_usuario_datos"];
            //print_r($datosConsultados["Id_usuario_datos"]);
            //si la consulta trae informacion
            if($datosConsultados){
                //empieza la transaccion
                $this->BD->beginTransaction();
                //update de la informacion del usuario

                $oldPasswordSha = sha1($oldPassword);

                $sentencia="SELECT count(Contrasena) FROM usuarios WHERE Id_usuario = ? AND Contrasena = ?;";
                //preparar datos ha insertar
                $actualizar = $this->BD->prepare($sentencia);
                //ejecuta e inserta variables
                $actualizar->execute(array($oldPasswordSha, $idUsuario));
                //respuesta
                //toma el valor que se da de respuesta al execute
                $rows = $this->BD->commit();
                //$rows = $insertar->fetch();
                if($rows){
                    //empieza la transaccion
                    $this->BD->beginTransaction();
                    //Si la actualizacion fue exitosa
                    $sentencia="UPDATE usuarios SET Contrasena = ?, Correo = ? WHERE Id_usuario = ?;";
                    $actualizar = $this->BD->prepare($sentencia);
                    //ejecuta e inserta variables
                    $newPasswordSha = sha1($newPassword);
                    $actualizar->execute(array($newPasswordSha, $correo, $idUsuario));
                    //respuesta
                    //toma el valor que se da de respuesta al execute
                    $rows = $this->BD->commit();
                    //$rows = $insertar->fetch();
                    if($rows){
                        //empieza la transaccion
                        $this->BD->beginTransaction();
                        //update de la informacion del usuario
                        $sentencia="CALL modificarInformacionUsuario(?,?,?,?)";
                        //preparar datos ha insertar
                        $actualizar = $this->BD->prepare($sentencia);
                        //ejecuta e inserta variables
                        $actualizar->execute(array($apellidoPaterno, $apellidoMaterno, $nombres, $idUsuarioDatos));
                        //respuesta
                        //toma el valor que se da de respuesta al execute
                        $rows = $this->BD->commit();
                        //$rows = $insertar->fetch();
                        if($rows){
                            $json_data['success'] = true;
                            $json_data['mensaje'] = "Datos modificados correctamente";
                        }
                        else{
                            //rollback regresa al estado original
                            $this->BD->rollBack();
                            $json_data['success'] = false;
                            $json_data['mensaje'] = "Error en la actualizacion de los datos del usuario";
                        }
                    }
                    else{
                        //rollback regresa al estado original
                        $this->BD->rollBack();
                        $json_data['success'] = false;
                        $json_data['mensaje'] = "Error en la actualizacion del correo y la contraseña del usuario";
                    }
                }
                else{
                    //rollback regresa al estado original
                    $this->BD->rollBack();
                    $json_data['success'] = false;
                    $json_data['mensaje'] = "Contraseña incorrecta";
                }
            }
            else{
                $json_data['success'] = false;
                $json_data['mensaje'] = "Problemas al consultar información basica del usuario";
            }
        }
        catch (Exception $e) {
            $json_data['success'] = false;
            $json_data['mensaje'] = $e;
        }
        //json como respuesta, se fuerzaa que sea un objeto tipo json
        echo json_encode($json_data, JSON_FORCE_OBJECT);
    }
}