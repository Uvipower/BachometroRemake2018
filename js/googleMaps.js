
//variables
//key del api
//var api_key             = "AIzaSyAzEVp_39gdFc8TrOcw8FajQm8G5izSr-E";
//elementos del html
var mapaHTML            = document.getElementById("mapa");
//mapa de google
var map;
//contador
var marcadoresPuestos   = 0;
//variables de posicion
var latitud, longitud;
//var times, latitud, longitud, altitud, exactitud;
//variable de la calle en la que se ubica el bache
var direccionBache;
//posicion actual del dispositvo
var posicionActual;
//variables de posicion bache
var coordenadasBache;
var latitudBache, longitudBache;
//marcador
var marcador;
//geocodificador
var geocodificador;
//configuracion del mapa
var configuracion;
//ventana de informacion infoWindow
var infoWindow;
//direccion escrita por el usuario
//var buscar;
//zoom minimo y maximo
var min_zoom = 15;
var max_zoom = 20;
//variable donde se guarda el skin del mapa
//var temaMapa;

//-----------------------------------------------------------//
// eventos de elementos //
// al buscar en el input de "buscarInput" //
$("#buscarButton").click(function(){
  "use strict";
  localizar();
});

$(window).resize(function () {
    var h = $(window).height(),
        offsetTop = 195; // Calculate the top offset

    $('#mapa').css('height', (h - offsetTop));
}).resize();

//constructor
function initialize () {
  "use strict";
  //si el navegador permite geolocation
  if (navigator.geolocation) {
    //ejecuta funcion para conocer su ubicacion
    navigator.geolocation.getCurrentPosition(crearMapa, showError);
  }
  else {
    //mensajeError.innerHTML = "¡Error! Este navegador no soporta la Geolocalización.";
    crearMapa(null);
  }
  //para localizar las calles
  geocodificador = new google.maps.Geocoder();
}

//OBTENER INFORMACIÓN//
//---------------------------------------------------------------------------//
//obtiene las coordenadas del dispositivo/computadora
function obtenerCoordenadas(coordenadas){
  "use strict";
  if(coordenadas !== null){
    //tratado de las variables
    //times           = coordenadas.timestamp;
    latitud         = coordenadas.coords.latitude;
    longitud        = coordenadas.coords.longitude;
    //altitud         = coordenadas.coords.altitude;
    //exactitud       = coordenadas.coords.accuracy;
  }
  else{
    //coordenadas de Chetumal
    latitud   = 18.5075663;
    longitud  = -88.3117101;
  }
  //creacion de las coordenadas como pide google de la posicion actual
  posicionActual  = new google.maps.LatLng(latitud, longitud);
}

//obtener las calles del bache/marcador
function obtenerCalles(){
  "use strict";
  geocodificador.geocode({'location': coordenadasBache}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        direccionBache = results[0].address_components[1].long_name;
      }
      else{
        alertify.error("No es posible ubicar calles.");
      }
    }
  });
}

//manda a la direccion actual del usuario
/*
function refrescarUbicacion() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(obtenerCoordenadas, showError);
  }
  else{
    obtenerCoordenadas(null);
  }
  centrarMapa();
}
*/
//localizar calle en el mapa
function localizar() {
  "use strict";
  var buscar = document.getElementById("buscarInput").value;
  geocodificador.geocode( { 'address': buscar + "Chetumal Quintana Roo"}, function(resultados, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      map.setCenter(resultados[0].geometry.location);
    } 
    else {
      $("#buscarInput").notify("No es posible ubicar las calles.", "error");
    }
  });
}

//---------------------------------------------------------------------------//
//CREAR COSAS EN EL MAPA//
//---------------------------------------------------------------------------//
//crea marcador apartir del evento de tocar el mapa
function crearMarcador(event){
	"use strict";

  //solo es posible poner uno
  if(marcadoresPuestos < 1){
    //obtener variables del objeto que manda google
    latitudBache      = event.lat;
    longitudBache     = event.lng;
    coordenadasBache  = event.latLng;
    //funcion de creacion del marcador
    marcador = new google.maps.Marker({
      //posicion en la que estara
      position: coordenadasBache,
      //se puede mover
      draggable:false
	    //icono
		  //icon: icon
    });
    //agregar marcador al mapa
    marcador.setMap(map);
    //obtener calles del marcador
  	obtenerCalles();
  	//abrir infowindow
  	//infoWindow.open(map, marcador);
    //si hacen click en el marcador
  	google.maps.event.addListener(marcador, 'click', function(){
      infoWindow = new google.maps.InfoWindow();
      //texto
      infoWindow.setContent([
        '¡Bache en '+direccionBache+'!'
      ].join(''));
      infoWindow.open(map, marcador);
    });
    //contador
    marcadoresPuestos = 1;
  }
  //solo se permite 1 marcador
  else{
    alertify.error("No es posible tener más de 1 marcador en el mapa");
  }
}

//crea el mapa
function crearMapa(coordenadas){
  "use strict";
  //tema del mapa
  //skinGoogleMaps();
  //llenar variables de coordenadas
  obtenerCoordenadas(coordenadas);
  //configuracion del mapa
  configuracion = {
    //si se puede mover el mapa
  	//draggable: false,
  	//zoom minimo
  	minZoom: min_zoom,
  	//zoom maximo
  	maxZoom: max_zoom,
  	//sehabilita que sean clicleables los iconos
  	clickableIcons: false,			
    //posicion
    center: posicionActual,
    //limpia el mapa
    disableDefaultUI: true,
	  /*
	  greedy: se realiza un movimiento panorámico del mapa (hacia arriba, abajo, izquierda o derecha) cuando el usuario desliza (arrastra) la pantalla. En otras palabras, los deslizamientos con uno y dos dedos generan un movimiento panorámico del mapa.
    cooperative: el usuario debe deslizar la pantalla con un dedo para desplazar la página, y con dos dedos para generar un movimiento panorámico del mapa. Si el usuario desliza el mapa con un dedo, aparecerá una superposición sobre el mapa con un mensaje que indica al usuario que debe usar dos dedos para mover el mapa. Mira el ejemplo anterior en un dispositivo móvil para ver el modo “cooperative” en acción.
    */
	  gestureHandling: 'cooperative',
    //permitir pantallacompleta
    fullscreenControl: true,
  	//controles de los estilos del mapa
    //tipo de mapa
    //mapTypeId: temaMapa,
    //controles por rueda de mouse
    scrollwheel: true,
  	//Rotacion 45 grados
  	rotateControl: true,
  	rotateControlOptions: {
  		position: google.maps.ControlPosition.LEFT_BOTTOM
  	},
	  //zoom
    zoom: 15,
	  //controles del zoom
	  zoomControl: true
  };
	
  //crea mapa apartir del espacio para el mapa y su configuracion
  map = new google.maps.Map(mapaHTML, configuracion);
  //Associate the styled map with the MapTypeId and set it to display.
  map.mapTypes.set('styled_map', skinGoogleMaps());
  map.setMapTypeId('styled_map');
  //evento segundo plano para saber en que momento tocan el mapa "marcador"
  google.maps.event.addListener(map, 'click', function(event) {
    //agregar marcador al mapa apartir del objeto que devuelve googe
    crearMarcador(event);
  });
  //si llegan a modificar el tamaño de la pagina
  google.maps.event.addDomListener(window, "resize", function() {
      //obtener el centro 
      var centro = map.getCenter();
      //evento que se dispara
      google.maps.event.trigger(map, "resize");
      //centra en el mapa
      map.setCenter(centro); 
  });
  //botones en los mapas
  //boton centrar
  panelCentrar();
  panelBorrar();
  panelReportar();
}

//---------------------------------------------------------------------------//
//BOTONES EN EL MAPA//
//---------------------------------------------------------------------------//
//boton de centrar dentro del mapa
function panelCentrar() {
  "use strict";
  //creacion de elementos en el HTML
  var panelCentrarDiv = document.createElement('div');
  //indice
  panelCentrarDiv.index = 2;
  //estilo y base del boton centrar
  var botonCentrar = document.createElement('div');
  
	botonCentrar.title = 'Click para centrar mapa en tu ubicación actual';
  panelCentrarDiv.appendChild(botonCentrar);
  //texto del boton centrar
  var botonCentrarTexto = document.createElement('div');
  
  botonCentrarTexto.innerHTML = '<i class="btn btn-light fa fa-location-arrow fa-3x" aria-hidden="true"></i>';
  botonCentrar.appendChild(botonCentrarTexto);
  //agregar al mapa
  map.controls[google.maps.ControlPosition.LEFT_CENTER].push(panelCentrarDiv);
  //boton centrar
  botonCentrar.addEventListener('click', function() {
    Lobibox.notify('default', {
      size: 'mini',
      title: false,
      sound: false,
      pauseDelayOnHover: true,
      continueDelayOnInactiveTab: false,
      rounded: true,
      msg: 'Ubicando en el mapa tu posición actual...'
    });
    map.setCenter(posicionActual);
  });
}

//boton de centrar dentro del mapa
function panelReportar() {
  "use strict";
  //creacion de elementos en el HTML
  var panelReportarDiv = document.createElement('div');
  //indice
  panelReportarDiv.index = 3;
  //estilo y base del boton centrar
  var botonReportar = document.createElement('div');
  
  botonReportar.title = 'Click para reportar bache';
  panelReportarDiv.appendChild(botonReportar);
  //texto del boton centrar
  var botonReportarTexto = document.createElement('div');
  
  botonReportarTexto.innerHTML = '<i class="btn btn-secondary fa fa-map-marker fa-3x" aria-hidden="true"></i>';
  botonReportar.appendChild(botonReportarTexto);
  //agregar al mapa
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(panelReportarDiv);
  //boton centrar
  botonReportar.addEventListener('click', function() {
    reportarBache();
  });
}

//boton de borrar dentro del mapa
function panelBorrar(){
  "use strict";
  //creacion de elementos en el HTML
  var panelBorrarDiv = document.createElement('div');
  panelBorrarDiv.index = 1;
  //estilo y base del boton borrar
  var botonBorrar = document.createElement('div');
  
  botonBorrar.title = 'Click para borrar marcador';
  panelBorrarDiv.appendChild(botonBorrar);
  //texto del boton borrar
  var botonBorrarTexto = document.createElement('div');
  
  botonBorrarTexto.style.lineHeight = '38px';
  botonBorrarTexto.style.paddingLeft = '5px';
  botonBorrarTexto.style.paddingRight = '5px';
  botonBorrarTexto.innerHTML = '<i class="btn btn-danger fa fa-trash-o fa-3x" aria-hidden="true"></i>';
  botonBorrar.appendChild(botonBorrarTexto);
  //agregar al mapa
  map.controls[google.maps.ControlPosition.LEFT_TOP].push(panelBorrarDiv);
  //listeners
  //boton borrar
  botonBorrar.addEventListener('click', function() {
    if(marcadoresPuestos === 1){
      borrarMarcador();
      Lobibox.notify('default', {
        size: 'mini',
        title: false,
        sound: false,
        pauseDelayOnHover: true,
        continueDelayOnInactiveTab: false,
        rounded: true,
        msg: 'Se ha eliminado el marcador ubicado en el mapa.'
      });
    }
  });
}

//---------------------------------------------------------------------------//
//INTERACTUAR CON EL MAPA//
//---------------------------------------------------------------------------//
//cuando se quiere borrar los marcadores o se termina de reportar un bache//
function borrarMarcador(){
  "use strict";
  //variable de apoyo
  marcadoresPuestos=0;
  //elimina marcadores del mapa
  marcador.setMap(null);
}
//---------------------------------------------------------------------------//
//ENVIAR INFORMACION A LA BASE DE DATOS//
//---------------------------------------------------------------------------//
//cuando se quiere reportar un bache //boton
function reportarBache(){
  "use strict";
  //si hay marcadores
  if(marcadoresPuestos === 1){
    //abrir modal
    $("#modalDetalles").modal('show');

    $("#registrarBache").click(function (e){
      e.preventDefault();
      //form data
      //imagen
      var img = document.getElementById("imagen");
      var formData = new FormData();
      jQuery.each($('input[type=file]')[0].files, function(i, file) {
          formData.append('file-'+i, file);
      });

      formData.append('opcion', "registrarReporte");
      formData.append('latitud', coordenadasBache.lat());
      formData.append('longitud', coordenadasBache.lng());
      formData.append('referencia', direccionBache);
      formData.append('descripcion', $("#descripcion").val());
      formData.append('imagen', img.files[0]);
        //guardar reporte del bache
      $.ajax({
        url: '../php/controladorReporte.php',
        type: 'POST',
        dataType: 'JSON',
        data: formData,
        cache: false,
        contentType: false,
        processData: false
      })
      .done(function() {
        console.log("success");
        //console.log(data);
        $("#imagen").val("");
        $("#descripcion").val("");
        $("#modalDetalles").modal('hide');
        borrarMarcador();
        Lobibox.notify('success', {
          icon: false,
          size: 'normal',
          title: false,
          sound: false,
          pauseDelayOnHover: true,
          continueDelayOnInactiveTab: false,
          rounded: true,
          msg: "Ha reportado un bache en la direccion: " + direccionBache 
            + "\n *Se recargará la pagina en 5 segundos."
        });
        setTimeout(function(){
          window.location.reload();
        }, 5000);  
      })
      .fail(function(data) {
        Lobibox.notify('error', {
          size: 'mini',
          title: false,
          sound: false,
          pauseDelayOnHover: true,
          continueDelayOnInactiveTab: false,
          rounded: true,
          msg: data
        });
        console.log("error " + data);
      });  
      return false;
    });   
  }
  else{
    Lobibox.notify('error', {
      size: 'mini',
      title: false,
      sound: false,
      pauseDelayOnHover: true,
      continueDelayOnInactiveTab: false,
      rounded: true,
      msg: 'Debe indicar la ubicación del bache.'
    });
  }
}



/*
//pruebas
// Variable to store your files
var files;

// Add events
$('input[type=file]').on('change', prepareUpload);

// Grab the files and set them to our variable
function prepareUpload(event)
{
  "use strict";
  files = event.target.files;
}
*/
//---------------------------------------------------------------------------//
//MOSTRAR/IMPRIMIR EN EL MAPA//
//---------------------------------------------------------------------------//
//imprime las coordenadas en pantalla
/*function mostrarCoordenadas(){
  //cuadro de texto para todo
  div.innerHTML   = "<br>Latitud: " + latitud +
                    "<br>Longitud: " + longitud;
}*/
//posibles errorres que puede tener la api
function showError(error) {
  "use strict";
  switch (error.code) {
    case error.PERMISSION_DENIED:
      alertify.error("Ha denegado la peticion de su ubicación actual.\nPuede utilizar el sistema.");
      break;
    case error.POSITION_UNAVAILABLE:
      alertify.error("La información de su ubicación actual no esta disponible.\nPuede utilizar el sistema.");
      break;
    case error.TIMEOUT:
      alertify.error("El tempo de espera de su ubicación actual ha expirado.\nPuede utilizar el sistema.");
      break;
    case error.UNKNOWN_ERROR:
      alertify.error("Ha ocurrido un error desconocido.\nPuede utilizar el sistema.");
      break;
  }
  crearMapa(null);
}
