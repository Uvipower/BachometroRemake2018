<?php

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and (($_SESSION['tipoUsuario'] == 2) or ($_SESSION['tipoUsuario'] == 1))))
{


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link rel="shortcut icon" type="image/png" href="../img/favicon.ico"/>
	<title>Bachometro &copy; Municipio de Othón P. Blanco &mdash; 2018</title>
  <!-- Bootstrap core CSS-->
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
  <!-- Custom fonts for this template-->
  <link rel="stylesheet" type="text/css" href="../css/font-awesome.css"/>
  <!-- Custom styles for this template-->
  <link rel="stylesheet" type="text/css" href="../css/sb-admin.css"/>
  <link rel="stylesheet" type="text/css" href="../css/Lobibox/lobibox.css"/>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.html">Bachometro</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Inicio">
          <a class="nav-link" href="index.php">
            <i class="fa fa-fw fa-home"></i>
            <span class="nav-link-text">Inicio</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Mapa">
          <a class="nav-link" href="mapa.php">
            <i class="fa fa-fw fa-map"></i>
            <span class="nav-link-text">Mapa</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reportes">
          <a class="nav-link" href="reportes.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Reportes</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="clusterer.php">
            <i class="fa fa-fw fa-globe"></i>
            <span class="nav-link-text">Reportes en mapa</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <div class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" id="buscarInput" type="text" placeholder="Buscar por calle">
              <span class="input-group-btn">
                <button class="btn btn-primary" id="buscarButton" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Inicio</a>
        </li>
        <li class="breadcrumb-item active">Mapa</li>
      </ol>
      <!-- Icon Cards-->
    </div>
	 
      	<div id="mapa" onselectstart="return false">
	    </div>
	  
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Bachometro &copy; Municipio de Othón P. Blanco &mdash; 2018</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Realmente quiere salir?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Selecciona "Cerrar sesión" si está listo para finalizar su sesión actual.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" href="../php/controladorUsuario.php">Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- Bootstrap core JavaScript-->
    <script src="../js/jquery-3.1.1.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../js/jquery.easing.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin.js"></script>
    <script src="../js/googleMapsClusterer.js"></script>
    <script src="../js/GoogleMaps/markerclusterer.js"></script>
	  <script src="../js/skinGoogleMaps.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5RLczzcvYGih3RsP4UOOOxqcu8gEueRg&callback=initialize" type="text/javascript"></script>
    
  </div>
</body>

</html>
<?php

}

else{
  header("Location: ../php/controladorUsuario.php");
}


?>