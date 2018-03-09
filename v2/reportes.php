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
  <!-- Page level plugin CSS-->
  <link rel="stylesheet" type="text/css" href="../css/DataTables/dataTables.bootstrap4.css"/>
  <link rel="stylesheet" type="text/css" href="../css/DataTables/responsive.bootstrap4.css"/>
  <link rel="stylesheet" type="text/css" href="../css/DataTables/rowGroup.bootstrap4.css"/>
	<link rel="stylesheet" type="text/css" href="../css/DataTables/select.bootstrap4.css"/>
	<link rel="stylesheet" type="text/css" href="../css/DatePicker/bootstrap-datepicker.css"/>
  <link rel="stylesheet" type="text/css" href="../css/sb-admin.css"/>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <img src="../img/logo.png" width="42" height="30" class="d-inline-block align-top">
    <a class="navbar-brand" href="index.php">Bachometro</a>
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
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Configuracion">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-filter"></i>
            <span class="nav-link-text">Agrupar por...</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseComponents">
            <li>
      			  <a class="group-by" data-column="1">
      			  	<i class="fa fa-fw fa-calendar"></i> Agrupar por fecha
      			  </a>
            </li>
        		<li>
              <a class="group-by" data-column="2"> 
        			  <i class="fa fa-fw fa-road"></i> Agrupar por referencia
        			</a>
            </li>
            <li>
              <a class="group-by" data-column="4"> 
        		    <i class="fa fa-fw fa-heartbeat"></i> Agrupar por estatus
        			</a>
            </li>
        		<li>
              <a class="group-by" data-column="5">
        		   	<i class="fa fa-fw fa-calendar-check-o"></i> Agrupar por ultima actualización
        		  </a>
            </li>
          </ul>
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
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" id="buscarEnTabla" type="text" placeholder="Buscar">
              <span class="input-group-btn">
                <button class="btn btn-primary" id="buscarButton" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
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
        <li class="breadcrumb-item active">Reportes</li>
      </ol>
      <!-- Panel de control-->
	  	
	  <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Reportes
		</div>
        <div class="card-body">
            <table class="display table table-striped table-bordered" id="reportes" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th class="with-input" rowspan="1" colspan="1"><input type="text" class="form-control" placeholder="Folio"/></th>
                  <th class="with-input" rowspan="1" colspan="1">
                    <div class="input-group date" id="fecha">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" class="form-control" placeholder="AAAA-MM-DD">
                    </div>
                  </th>
                  <th class="with-input" rowspan="1" colspan="1"><input type="text" class="form-control" placeholder="Referencia"/></th>
                  <th class="with-input" rowspan="1" colspan="1"><input type="text" class="form-control" placeholder="Comentario"/></th>
                  <th class="with-input" rowspan="1" colspan="1">
                    <select class="form-control">
                      <option value="">Seleccionar</option>
                      <option value="En espera">En espera</option>
                      <option value="Atendido">Atendido</option>
                      <option value="Finalizado">Finalizado</option>
                      <option value="Cancelado">Cancelado</option>
                    </select>
                  </th>
                  <th class="with-input" rowspan="1" colspan="1">
                    <div class="input-group date" id="fechaEstatus">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" class="form-control" placeholder="AAAA-MM-DD">
                    </div>
                  </th>
                </tr>
                <tr>
        					<th>Folio</th>
        					<th>Fecha</th>
        					<th>Referencia</th>
                  <th>Comentario</th>
        					<th>Estatus actual</th>
              		<th>Fecha estatus</th>
				        </tr>
              </thead>
              <tfoot>
                <tr>
        					<th>Folio</th>
        					<th>Fecha</th>
        					<th>Referencia</th>
        					<th>Comentario</th>
              		<th>Estatus actual</th>
              		<th>Fecha estatus</th>
        				</tr>
              </tfoot>
              <tbody>
                
              </tbody>
            </table>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>
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

    <!--Estatus Modal-->
    <div class="modal fade" id="modalEstatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Registrar avance del reporte</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          <form id="registroEstatus"></form>
            <div class="form-group">
              <label for="estatus">Estado actual del reporte</label>
              <select class="custom-select custom-select-lg mb-3" id="estatus">
                <option selected disabled="true">Seleccionar estatus</option>
                <option value="atendido">Atendido</option>
                <option value="finalizado">Finalizado</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción del estado actual del reporte</label>
              <textarea class="form-control" id="descripcion" rows="5"></textarea>
            </div>
            <p class="leah">* Por favor registre una breve descripción.</p>
            </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary" type="button">Registrar avance</button>
          </div>
        </div>
      </div>
    </div>


	<!--Detalles Modal-->
	<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
  		<div class="modal-content">
  		  <div class="modal-header">
  			<h5 class="modal-title" id="exampleModalLongTitle">Reporte detallado</h5>
  			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  			  <span aria-hidden="true">&times;</span>
  			</button>
  		  </div>
  		  <div class="modal-body" id="detallesReporte">
    		  <h5>Ubicación del bache reportado</h5>
    			  <div id="mapa"></div>
    		  <hr>
    		  <h5>Historial del avance del reporte</h5>
    		  <div class="table-responsive">
            <table class="table table-striped table-bordered" id="historialReporte" width="100%" cellspacing="0">
              <thead>
                <tr>
          				<th>Fecha</th>
          				<th>Estatus</th>
               		<th>Descripcion</th>
    			      </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div id="imagenReporte"></div>
          <div id="comentarioReporte"></div>
  		  </div>
  		  <div class="modal-footer">
  		    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
  		  </div>
  		</div>
	  </div>
	</div>
	<!--Eliminar Modal-->  
	<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle2">¿Realmente quiere eliminar el reporte?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			Selecciona "Eliminar" si desea eliminar el reporte, en caso contrario seleccione "Cancelar" o cierre la ventana.
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-danger" id="eliminarReporte">Eliminar</button>
		  </div>
		</div>
	  </div>
	</div>
	
  	<script src="../js/jquery-3.1.1.js"></script>
  	<script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/jquery.easing.js"></script>
  	<script src="../js/DataTables/jquery.dataTables.js"></script>
   	<script src="../js/DataTables/dataTables.bootstrap4.js"></script>
  	<script src="../js/DataTables/dataTables.buttons.js"></script>
   	<script src="../js/DataTables/buttons.bootstrap4.js"></script>
    <script src="../js/DataTables/dataTables.responsive.js"></script>
  	<script src="../js/DataTables/responsive.bootstrap4.js"></script>
  	<script src="../js/DataTables/dataTables.colReorder.js"></script>
    <script src="../js/DataTables/dataTables.fixedColumns.js"></script>
    <script src="../js/DataTables/dataTables.select.js"></script>
    <script src="../js/DataTables/dataTables.scroller.js"></script>
    <script src="../js/DataTables/dataTables.rowGroup.js"></script>
    <script src="../js/DataTables/dataTables.keyTable.js"></script>
  	<script src="../js/DataTables/jszip.js"></script>
    <script src="../js/DataTables/pdfmake.js"></script>
  	<script src="../js/DataTables/buttons.colVis.js"></script>
  	<script src="../js/DataTables/buttons.html5.js"></script>  
  	<script src="../js/DataTables/buttons.print.js"></script>
  	<script src="../js/sb-admin.js"></script>
  	<script src="../js/skinGoogleMaps.js"></script>
    <script src="../js/DatePicker/bootstrap-datepicker.js"></script>
    <script src="../js/DatePicker/bootstrap-datepicker.es.js"></script>
    <script src="../js/reportes.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5RLczzcvYGih3RsP4UOOOxqcu8gEueRg" type="text/javascript"></script>
  </div>
</body>

</html>
<?php

}

else{
  header("Location: ../php/controladorUsuario.php");
}


?>