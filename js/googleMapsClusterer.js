
//variables
//key del api
//var api_key             = "AIzaSyAzEVp_39gdFc8TrOcw8FajQm8G5izSr-E";
//elementos del html
var mapaHTML            = document.getElementById("mapa");
//mapa de google
var map;
//variables de posicion
var latitud, longitud;
//var times, latitud, longitud, altitud, exactitud;
//posicion actual del dispositvo
var posicionActual;
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

//constructor
function initialize () {
  "use strict";
  crearMapa();
  //para localizar las calles
  geocodificador = new google.maps.Geocoder();
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
function cargarMarcadores(event){
	"use strict";
  $.ajax({
    url: '../php/controladorReporte.php',
    type: 'POST',
    dataType: 'JSON',
    data: {
      "opcion": "consultarClusterers"     
    }
  })
  .done(function(response){
    var marcadores = [];
        
    $.each(response.reportes, function (index, value) {
      var marker = new google.maps.Marker({
            position: new google.maps.LatLng(value.Latitud, value.Longitud)
        });
      marcadores.push(marker);
    });

    
    var config = {
      gridSize: 100,
      //maxZoom: 7,
      imagePath: '../js/GoogleMaps/MarkerClusterer/images/m'
    };

    var markerCluster = new MarkerClusterer(map, marcadores, config);
  });
}

//crea el mapa
function crearMapa(){
  "use strict";
  //tema del mapa
  //skinGoogleMaps();
  //llenar variables de coordenadas
  //coordenadas de Chetumal
  latitud   = 18.5075663;
  longitud  = -88.3117101;
  //creacion de las coordenadas como pide google de la posicion actual
  posicionActual  = new google.maps.LatLng(latitud, longitud);
  //configuracion del mapa
  configuracion = {
    //si se puede mover el mapa
  	//draggable: false,
  	//zoom minimo
  	//minZoom: min_zoom,
  	//zoom maximo
  	//maxZoom: max_zoom,
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
  //si llegan a modificar el tamaño de la pagina
  google.maps.event.addDomListener(window, "resize", function() {
      //obtener el centro 
      var centro = map.getCenter();
      //evento que se dispara
      google.maps.event.trigger(map, "resize");
      //centra en el mapa
      map.setCenter(centro); 
  });
  cargarMarcadores();
}

