//variables
var reportes;
var idReportes;
var idReporteDetalles;
var srcImagen;
var SelectRowClass  = 'bg-primary'; // To force Bootstrap's primary colour or any other
//var mapa;

$(window).ready(function(){
	"use strict";
	consultarBaches();
});

//cuando el usuario escribe en el input buscar
$('#buscarEnTabla').change(function () {
    "use strict";
	reportes.search(this.value).draw();
});

//cuando el usuario indica como quiere agrupar la tabla
$('a.group-by').click(function (e) {
	"use strict";
	e.preventDefault();
	reportes.rowGroup().dataSrc( $(this).data('column') );
	reportes.rowGroup().enable().draw();

});

$('#reportes tbody').on( 'click', 'tr', function () {
	$(this).toggleClass('active');
});

//funcion que se ejecuta al iniciar la carga de la pagina
//consulta los registros del reporte
function consultarBaches(){
	"use strict";
	$.ajax({
		url: '../php/controladorReporte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			"opcion": "consultarReportes"			
		}
	})
	.done(function(response){
		$.each(response.reportes, function (index, value) {
			//creacion de la fila
			var fila = "";
			fila += "<tr>";
			fila += "<th scope='row'>"+value.Id_reporte+"</td>";
			fila += "<td>"+value.Fecha+"</td>";
			fila += "<td>"+value.Referencia+"</td>";
			fila += "<td>"+value.Comentario+"</td>";
			fila += "<td>"+value.Estatus+"</td>";
			fila += "<td>"+value.Fecha2+"</td>";
			fila += "</tr>";
			//agregar la fila a la tabla
			$("#reportes").append(fila);			
		});
		reportes = $('#reportes').DataTable({
			//estructura de los elementos de datatables
			dom: "<'card-body pl-0 pr-0 pt-0'<'row align-items-center'<'col-12 col-md-12'B>>>" +
		            "<'row'<'col-12'tr>>" +
		            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			//dom: '<"top"B<"clear">>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
			//stateSave: true,
			deferRender:    true,
//			scrollCollapse: true,
        	//scroller:       true,
			order : [0, 'desc'],
			//ordering: [[ 1, 'desc' ]],
			//cambiar el numero de registros que se visualizara
			lengthChange: true,
			//numero de registros para ver en la tabla
			lengthMenu: [
				[ 10, 25, 50, -1 ],
				[ '10', '25', '50', 'Todos' ]
			],
			pagingType: "simple_numbers",
			//cambiar el idioma de ingles a español
			language: {
               	url: "../js/DataTables/Spanish.json",
				buttons: {
					pageLength: {
						_: "%d",
						'-1': "Todos"
					}
				}
           	},
			//buttones
		    buttons: [
				{
					//boton de coleccion, boton de exportar en diferentes formatos
					extend: 'collection',
					text: '<i class="fa fa-file-o"></i>',
					autoClose: true,
					buttons: [
						{
							//exportar a csv
							extend: 'csvHtml5',
							text: '<i class="fa fa-file-text-o"></i> CSV',
							titleAttr: 'CSV',
							exportOptions: {
								columns: ':visible'
							}
						}, 
						{
							//exportar a excel
							extend: 'excelHtml5',
							text: '<i class="fa fa-file-excel-o"></i> Excel',
							titleAttr: 'Excel',
							//columnas visibles
							exportOptions: {
								columns: ':visible'
							}
						}, 
						{
							//exportar a pdf
							extend: 'pdfHtml5',
							text: '<i class="fa fa-file-pdf-o"></i> PDF',
							titleAttr: 'PDF',
							footer: true,
							download: 'open',
							//columnas visibles
							exportOptions: {
								columns: ':visible'
							}
						}
					]
				},
				{
					//boton de coleccion, botones de imprimir
					extend: 'collection',
					text: '<i class="fa fa-print"></i>',
					autoClose: true,
					buttons: [{
						//imprimir
						extend: 'print',
						text: 'Imprimir todo',
						footer: true,
						customize: function ( win ) {
							$(win.document.body)
								.css( 'font-size', '12pt' )
								.prepend(
									'<img src="https://i.gyazo.com/8f5e933902eb98e1ff7586f422519e9f.png" style="position:absolute; top:0; left:0;" />'
								);

							$(win.document.body).find('table').addClass('display').css('font-size', '12px');
							$(win.document.body).find('tr:nth-child(odd) td').each(function(){
								$(this).css('background-color','#D0D0D0');
							});
							$(win.document.body).find('h1').css('text-align','center');
						},
						//columnas visibles
						exportOptions: {
							columns: ':visible'
						}
					}, 
					{
						//imprimir
						extend: 'print',
						text: 'Imprimir filas seleccionadas',
						footer: true,
						customize: function (win) {
							$(win.document.body)
								.css( 'font-size', '12pt' )
								.prepend(
									'<img src="https://i.gyazo.com/8f5e933902eb98e1ff7586f422519e9f.png" style="position:absolute; top:0; left:0;" />'
								);

								$(win.document.body).find('table').addClass('display').css('font-size', '12px');
								$(win.document.body).find('tr:nth-child(odd) td').each(function(){
									$(this).css('background-color','#D0D0D0');
								});
								$(win.document.body).find('h1').css('text-align','center');
						},
						//columnas visibles
						exportOptions: {
							columns: ':visible',
							modifier: {
								selected: true
							}
						}
					}]
				},
				{
					//ocultar y desocultar columnas
					extend: 'colvis',
					text: '<i class="fa fa-columns"></i>',
					autoClose: false
				},
				{
					//restaurar columnas ocultas, regresa al estado original de la tabla
					extend: 'colvisRestore',
					text:'<i class="fa fa-refresh"></i>'
				},
				{
					//numeros de registros que se podrán ver
					extend: 'pageLength',
					text: '<i class="fa fa-hashtag"></i>',
					autoClose: false
				},
				//los siguientes botones solo aparecen cuando 
				//se selecciona uno o unos registros
				{
					//boton de ver detalles
		            text: "<i class='fa fa-eye' aria-hidden='true'></i>",
		            tittleAttr: 'Ver detalles',
		            // .ml-2 and .rounded-left to make a flawless space between this first button and the fixed ones 
		            // .btn-req-selection to make the show/disapear behaviour
		            // .invisible to make it invisible by default 
		            className: 'ml-2 rounded-left btn-req-selection invisible btn-secondary',   
		            action: function ( e, dt, node, config ) {
		            	var filaSeleccionada = dt.row({ selected: true }).data();
		                idReporteDetalles = filaSeleccionada[0];
		                $("#modalDetalles").modal('show');
		            }
		        },                
		        {
		        	//boton de registrar avance de estatus
		            text: "<i class='fa fa-check-square' aria-hidden='true'></i>",
		            tittleAttr: 'Registrar modificación de estatus',
		            className: 'btn-req-selection invisible btn-info', // Basic classes for selection control and invisibility
		            action: function ( e, dt, node, config ) {
		                var idReportesSeleccionados = new Array();
		            	//console.log(dt.rows({ selected: true }).data().toArray());
		                var filasSeleccionadas = dt.rows({ selected: true }).data().each(function (value, index) {
					        idReportesSeleccionados.push(value[0]);
					    });
		                llenarVariables(idReportesSeleccionados);
		                $("#modalEstatus").modal('show');
		            }
		        },
		        {
		        	//boton de eliminar reporte
		            text: "<i class='fa fa-trash' aria-hidden='true'></i>",
		            tittleAttr: 'Eliminar reporte (s)',
		            className: 'btn-req-selection invisible btn-danger', // Bootstrap's btn-danger for delete
		            action: function ( e, dt, node, config ) {
		            	var idReportesSeleccionados = new Array();
		            	//console.log(dt.rows({ selected: true }).data().toArray());
		                var filasSeleccionadas = dt.rows({ selected: true }).data().each(function (value, index) {
					        idReportesSeleccionados.push(value[0]);
					    });
		                llenarVariables(idReportesSeleccionados);
		                $("#modalEliminar").modal('show');
		            }
		        }
					  
			],
			//agrupar por filas
			rowGroup: {
		        enable: false
		    },
		    //seleccionar una fila
			select: {
				style:    'os',
        		blurable: true
			},
			//reponsivo
			responsive: true
		});
		//ordenamiento
		reportes.on( 'rowgroup-datasrc', function ( e, dt, val ) {
			reportes.order.fixed( {pre: [[ val, 'asc' ]]} ).draw();
		});
		// ROW SELECTION
		//
		// I had to force Bootstrap's primary colour to highlight the selection. 
		// I also used Bootstrap's invisible class to make the buttons appear/disappear
		// depending on row selection
		reportes.on('select', function ( e, dt, type, indexes ) {
		    // Set Bootstrap's highlight 
		    indexes.forEach(function (element, index, array) {
		        reportes.row(element).nodes().to$().addClass( 'text-white ' + SelectRowClass);    
		    });

		    // Make '.btn-req-selection' buttons visible
		    $('div.dt-buttons button.btn-req-selection').removeClass('invisible');
		});

		// ROW DESELECTION
		reportes.on( 'deselect', function ( e, dt, type, indexes ) {
		    // Removing Bootstrap's highlight
		    indexes.forEach(function (element, index, array) {
		        reportes.row(element).nodes().to$().removeClass( 'text-white ' + SelectRowClass);    
		    });   

		    // Make '.btn-req-selection' buttons invisible
		    if (reportes.rows( { selected: true } ).data().length == 0 ) {
		        $('div.dt-buttons button.btn-req-selection').addClass('invisible');
		    }            
		});
	})
	.fail(function(response){
		console.log("fail " + response);
	});
}
//inputs de fecha
$("#fecha").datepicker({
	format: "yyyy-mm-dd",
	leftArrow: '<i class="fa fa-long-arrow-left"></i>',
    rightArrow: '<i class="fa fa-long-arrow-right"></i>',
    todayBtn: "linked",
    //clearBtn: true,
    forceParse: false,
    todayHighlight: true,
    language: "es",
    orientation: "bottom auto"
});

$("#fechaEstatus").datepicker({
    format: "yyyy-mm-dd",
	leftArrow: '<i class="fa fa-long-arrow-left"></i>',
    rightArrow: '<i class="fa fa-long-arrow-right"></i>',
    todayBtn: "linked",
    //clearBtn: true,
    forceParse: false,
    todayHighlight: true,
    language: "es",
    orientation: "bottom auto"
});

// Apply the search
$("#reportes thead th input[type=text]").on( 'keyup change', function () {
   	reportes
        .column($(this).closest('th').index()+':visible')
        .search(this.value)
        .draw();
});
        
// Apply the search
$("#reportes thead th select").on('change', function () {
	var val = $.fn.dataTable.util.escapeRegex(
        $(this).val()
    );
	reportes
	    .column($(this).closest('th').index()+':visible')
	    .search(val ? '^'+val+'$' : '', true, false)
		.draw();
});
        
$("#reportes").prop("title", "Click para ver detalles, modificar estatus o eliminar.");
//abrir panel de detalles
$('#modalDetalles').on('shown.bs.modal', function() {
	"use strict";
	//cargar mapa
	cargarMapa();
	//evento para redimencionar el mapa
	google.maps.event.trigger(mapa, "resize");
	//var currentCenter = mapa.getCenter();  // Get current center before resizing
	historialReporte();
	imagenReporte();
	comentarioReporte();
});

//cargar el mapa del reporte
function cargarMapa(){
	"use strict";
	//ajax
	$.ajax({
		url: '../php/controladorReporte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			opcion: "coordenadasReporte",
			idReporte: idReporteDetalles
		}
	})
	.done(function(response){
		if(response.success === true){
			//var mapa;
			var min_zoom = 15;
			var max_zoom = 20;
			var mapaHTML    = document.getElementById("mapa");
			//tema del mapa
			//skinGoogleMaps();
			var configuracion = {
				//posicion
				center: {
					lat: parseFloat(response.latitud), 
					lng: parseFloat(response.longitud)
				},
				//si se puede mover el mapa
				//draggable: false,
				//zoom minimo
				minZoom: min_zoom,
				//zoom maximo
				maxZoom: max_zoom,
				//sehabilita que sean clicleables los iconos
				clickableIcons: false,
				//limpia el mapa
				disableDefaultUI: true,
				//permitir pantallacompleta
				fullscreenControl: true,
				//tipo de mapa
				//mapTypeId: google.maps.MapTypeId.ROADMAP,
				//controles por rueda de mouse
				scrollwheel: true,
				//Rotacion 45 grados
				rotateControl: true,
				rotateControlOptions: {
					position: google.maps.ControlPosition.LEFT_BOTTOM
				},
				//streetview
				streetViewControl: true,
				//zoom
				zoom: 17,
				//controles del zoom
				zoomControl: true
			};
			// Cambiar tamaño del div en el modal
			mapaHTML.style.width='100%';
			mapaHTML.style.height='500px';
			
			// Create a map object and specify the DOM element for display.
			var mapa = new google.maps.Map(mapaHTML, configuracion);
			var marker = new google.maps.Marker({
				position: {
					lat: parseFloat(response.latitud), 
					lng: parseFloat(response.longitud)
				},
				title: 'Bache'
			});
			marker.setMap(mapa);
			
			//Associate the styled map with the MapTypeId and set it to display.
			mapa.mapTypes.set('styled_map', skinGoogleMaps());
			mapa.setMapTypeId('styled_map');
			google.maps.event.addDomListener(window, "resize", function() {
			  //obtener el centro 
			  var centro = mapa.getCenter();
			  //evento que se dispara
			  google.maps.event.trigger(mapa, "resize");
			  //centra en el mapa
			  mapa.setCenter(centro); 
		  });
		}
	});
}
//consultar avance del reporte (historial)
function historialReporte(){
	"use strict";
	$.ajax({
		url: '../php/controladorReporte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			opcion: "historialReporte",
			idReporte: idReporteDetalles
		}
	})
	.done(function(response){
		$.each(response.historial, function (index, value) {
			//limpiar tabla
			$("#historialReporte tbody tr").remove();
			//crea la fila
			var fila = "";
			fila += "<tr>";
			fila += "<td>"+value.Fecha+"</td>";
			fila += "<td>"+value.Estatus+"</td>";
			fila += "<td>"+value.Descripcion+"</td>";
			fila += "</tr>";
			//incluye la fila a la tabla
			$("#historialReporte").append(fila);

		});
	});
	
}
//consultar imagen del reporte
function imagenReporte(){
	"use strict";
	$.ajax({
		url: '../php/controladorReporte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			opcion: "imagenReporte",
			idReporte: idReporteDetalles
		}
	})
	.done(function(response){
		//en caso de obtener una repuesta del servidor, se guarda en un string el codigo del html para luego ser insertado en la pagina
		if(response.imagen !== ""){
			//crea el div para la imagen
			var divImagen = "";
			divImagen += "<h5>Imagen del bache</h5>";
			divImagen += "<div class='text-center'>";
			divImagen += "<img src='"+response.imagen+"' class='img-fluid rounded'>";
			divImagen += "</div>";
			divImagen += "<hr>";
			//lo agrega al html
			$("#imagenReporte").html(divImagen);
		}
	});
}
//cargar comentario del ciudadano sobre el reporte
function comentarioReporte(){
	"use strict";
	$.ajax({
		url: '../php/controladorReporte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			opcion: "comentarioReporte",
			idReporte: idReporteDetalles
		}
	})
	.done(function(response){
		//en caso de obtener una repuesta del servidor, se guarda en un string el codigo del html para luego ser insertado en la pagina
		if(response.comentario !== ""){
			var divComentario = "";
			divComentario += "<h5>Comentario referente al reporte</h5>";
			divComentario += "<div class='text-center'>";
			divComentario += "<p class='lead'>"+response.comentario+"</div>";
			divComentario += "</div>";
			$("#comentarioReporte").html(divComentario);
		}
	});
	
}
//puede aceptar 1 o mas valores
//se utiliza para modificar el estatus (registrar avance del reporte o reportes) o para eliminar reporte o reportes
function llenarVariables(idReportesSeleccionados){
	"use strict";
	idReportes = idReportesSeleccionados;
	//srcImagen = srcImagen;
}

//eliminar reporte
$("#eliminarReporte").click(function() {
	"use strict";
	$.ajax({
		url: '../php/controladorReporte.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			opcion: "eliminarReportes",
			idReportes: idReportes
		}
	})
	.done(function(response) {
		//una vez que se elimine, vuelve a llamar a toda la pagina
		if(response.success === true){
			alert("Se ha eliminado el reporte con exito.");
			location.reload();
		}
		else{
			alert("Problemas para eliminar el reporte, favor de intentar más tarde.");
		}
	})
	.fail(function(){
		
	});
});

