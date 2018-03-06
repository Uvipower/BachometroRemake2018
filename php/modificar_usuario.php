<?php

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and $_SESSION['tipo'] == 2))
{


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" type="image/png" href="../img/favicon.ico" />
    <title>Bachometro</title>
    <link href="../css/yeti.css" rel="stylesheet" />
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="well">
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#barra_opciones" aria-expanded="false"> 
          	<span class="sr-only">Toggle navigation</span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>             
          </button>
          <a class="navbar-brand" href="#">Bachometro</a>
        </div>
        <div class="collapse navbar-collapse" id="barra_opciones">
          <ul class="nav navbar-nav">
            <li><a href="../usuarios/index.php" aria-expanded="true">Inicio <span class="sr-only">(current)</span></a></li>
            <li><a href="reportes.php" aria-expanded="false">Reportes</a></li>
            <!--<li><a class="reportes_hechos" href="#reportes_hechos" data-toggle="tab" aria-expanded="false">Reportes hechos</a></li>-->
            <li class="active"><a aria-expanded="false">Modificar usuario</a></li>
            <li><a id="cerrar_sesion" href="#">Cerrar sesion</a></li>
          </ul>
        </div>
      </div><!-- /.container-fluid -->
    </nav>


        <div class="jumbotron">
          <div class="container">
          
            <p>Modificar información del usuario</p>
            <form id="form_modificar_usuario" class="form-horizontal">
              <div class="row center-block">
                <div class="col-xs-5 col-sm-3">
                  <div class="form-group">
                    <label for="apellido_paterno">Apellido Paterno</label>
                    <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" placeholder="Apellido Paterno" required />
                  </div>
                  </div>
                  <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-5 col-sm-3"> 
                  <div class="form-group">
                    <label for="apellido_materno">Apellido Materno</label>
                    <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" placeholder="Apellido Materno" required />
                  </div>
                </div>
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-5 col-sm-3">
                  <div class="form-group">
                  <label for="nombres">Nombres</label>
                  <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombres" required />
                  </div>
                </div>
              </div>
              <div class="row center-block">
                <div class="col-xs-15 col-sm-11"> 
                  <div class="form-group">
                  <label for="email">Correo</label>
                  <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo" required />
                  </div>
                </div>
              </div>
              <div class="row center-block">
                <div class="col-xs-7 col-sm-5"> 
                  <div class="form-group">
                  <label for="anterior">Contraseña anterior</label>
                  <input type="password" class="form-control" name="anterior_password" id="anterior_password" placeholder="Clave de acceso anterior" required />
                  </div>
                </div>
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-7 col-sm-5">
                  <div class="form-group">
                  <label for="password">Nueva contraseña</label>
                  <input type="password" class="form-control" name="nueva_password" id="nueva_password" placeholder="Confirmar contraseña" required />
                  </div>
                </div>
              </div>
              <div class="alert alert-dismissible" id="alerta_actualizar" hidden="true">
                <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                <p id="alerta_actualizar_servidor"></p>
              </div>
              <button type="submit" id="btn_modificar_usuario" class="btn btn-primary btn-block">Actualizar datos</button>
            </form> 
          </div>
        </div>

    
    <nav class="navbar navbar-default navbar-fixed-bottom">
      <div class="container-fluid">
        <p class="navbar-text navbar-right">UT Chetumal 2017</p>
      </div>
    </nav>


    <script src="../js/jquery-3.1.1.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/compartidos.js"></script>
    <script src="../js/modificar_usuario.js"></script>

    <script src="../js/jquery.validate.js"></script>
    <script src="../js/additional-methods.js"></script>
    

  </body>
</html>

<?php

}

else{
  header("Location: ../");
}


?>