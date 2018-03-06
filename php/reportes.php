<?php

session_start();

if (isset($_COOKIE['id']) or (isset($_SESSION['id']) and $_SESSION['tipoUsuario'] == 2))
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
    
    <link rel="stylesheet" type="text/css" href="../DataTables/css/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="../DataTables/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../DataTables/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../DataTables/css/buttons.bootstrap.css"/>
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
        <a class="navbar-brand" href="../usuarios/index.php">Bachometro</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#barra_opciones" aria-expanded="false">
          	<span class="sr-only">Toggle navigation</span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>          
          </button>   
        </div>
        <div class="collapse navbar-collapse" id="barra_opciones">
          <ul class="nav navbar-nav">
            <li><a href="../usuarios/index.php" aria-expanded="true">Inicio <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="#" aria-expanded="false">Reportes</a></li>
            <!--<li><a class="reportes_hechos" href="#reportes_hechos" data-toggle="tab" aria-expanded="false">Reportes hechos</a></li>-->
            <li><a href="modificar_usuario.php" aria-expanded="false">Modificar usuario</a></li>
            <li><a id="cerrar_sesion" href="#">Cerrar sesion</a></li>
          </ul>
        </div>
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container">
        <div class="jumbotron">
          <div class="container">      
        	<p>Reportes generados</p>
        	<div id="div_tabla_todos">
	           	<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="tabla_todos">
					<thead>
						<tr>
							<th>Folio</th>
							<th>Fecha</th>
							<th>Referencia</th>
              <th>Comentario</th>
							<th>Estatus actual</th>
              <th>Fecha estatus</th>
							<th>Detalles</th>
							<th>Borrar</th>
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
							<th>Detalles</th>
							<th>Borrar</th>
						</tr> 
					</tfoot>
					<tbody id="tabla_todos_cuerpo">
					</tbody>
				</table>
			</div>	
          </div>
        </div>
    </div>

	<!-- Modal -->
	<div class="modal fade" id="reporte_detalles_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Detalles del reporte</h4>
	      </div>
	      <div class="modal-body">
	      <div id="reporte_detalles_imagen"></div>
        <div id="reporte_detalles_cuerpo"></div>
	      <div id="mapa_modal" onselectstart='return false'></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>
    
    <nav class="navbar navbar-default navbar-fixed-bottom">
      <div class="container-fluid">
        <p class="navbar-text navbar-right">UT Chetumal 2017</p>
      </div>
    </nav>


    <script src="../js/jquery-3.1.1.js"></script></script><!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/compartidos.js"></script>
    <script src="../js/reportes.js"></script>
    <script src="../DataTables/js/jquery.dataTables.js"></script>
    <script src="../DataTables/js/dataTables.bootstrap.js"></script>
    <script src="../DataTables/js/dataTables.buttons.js"></script>
    <script src="../DataTables/js/buttons.bootstrap.js"></script>
    <script src="../DataTables/js/dataTables.select.js"></script>
    <script src="../DataTables/js/buttons.flash.js"></script>
    <script src="../DataTables/js/jszip.js"></script>
    <script src="../DataTables/js/pdfmake.js"></script>
    <script src="../DataTables/js/vfs_fonts.js"></script>
    <script src="../DataTables/js/buttons.html5.js"></script>
    <script src="../DataTables/js/buttons.print.js"></script>
    <script src="../DataTables/js/buttons.colVis.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5RLczzcvYGih3RsP4UOOOxqcu8gEueRg&callback=initMap" type="text/javascript"></script>
    
    
  </body>
</html>

<?php

}

else{
  header("Location: ../");
}


?>