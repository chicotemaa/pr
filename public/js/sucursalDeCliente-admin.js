document.body.onload = function() {
/*
Este módulo debe 
1. recibir la ubicación del mapa
2. rellenar el formulario
3. proveer funciones de búsqueda

Ejemplo
1. Cursor en el centro de la plaza de Resistencia, Chaco
sucursalMapa.lat(); // -27.45117597008212
sucursalMapa.lng(); // -58.98649737984137
*/
  // inicializar Leaflet
  var map = L.map('mapaSucursal');
  const mapFoundZoom   = 16; //[5 mín - 18 máx]
  const mapWelcomeZoom =  5;
  const LAT_DEFAULT = -27.41117;
  const LON_DEFAULT = -59.00012;

  // add the OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
  }).addTo(map);

  // mostrar escala métrica
  L.control.scale({imperial: false, metric: true}).addTo(map);

  // Inicializar marcador
  let marcador = L.marker({lat: 0, lng: 0}, {draggable: false});
  // Si hay coordenadas en form, aplicarlas al mapa
  const BASE_ID = "sucursaldecliente_";
  const HTML_LAT = document.getElementById(BASE_ID+"Latitud").value;
  const HTML_LON = document.getElementById(BASE_ID+"Longitud").value;
  if (HTML_LAT !== "" && HTML_LON !== "") {
    let formLat = Number.parseFloat(HTML_LAT.replace(/,/g, '.'));
    let formLon = Number.parseFloat(HTML_LON.replace(/,/g, '.'));
    map.setView({lat:formLat,lng:formLon}, mapFoundZoom);
    marcador.setLatLng(L.latLng(formLat, formLon)).addTo(map);
  } else {
    map.setView({lat: LAT_DEFAULT, lon: LON_DEFAULT}, mapWelcomeZoom)
  }

  function onMapClick(e) {
      /*Interactividad, clics*/

      // Modifica el mapa, dando retroalimentación
      marcador.addTo(map);
      marcador.setLatLng({lat: e.latlng.lat, lng: e.latlng.lng});
      // Modifica el formulario
      document.getElementById(BASE_ID+"Latitud").value = Number.parseFloat(e.latlng.lat).toFixed(5);
      document.getElementById(BASE_ID+"Longitud").value = Number.parseFloat(e.latlng.lng).toFixed(5);
  }
  map.on('click', onMapClick);

  /* Buscador de direcciones */
  function buscador() {
    let resLat = 0.0, resLon = 0.0;

    const direccion = document.getElementById("direccion").value;
    const endpoint = new URL('/search', 'https://nominatim.openstreetmap.org');
    let query = new URLSearchParams({'q': direccion,'format': 'jsonv2'});
    endpoint.search = query;
    // Busqueda en caché
    if (sessionStorage.getItem(direccion+"Lat") === null) {
      // Buscar ubicación en internet
      fetch(endpoint)
      .then(data => data.json())
      .then(res  => {
        resLat = res[0].lat;
        resLon = res[0].lon;

        // Cacheo de respuesta
        sessionStorage.setItem(direccion+"Lat", String(resLat));
        sessionStorage.setItem(direccion+"Lon", String(resLon));

        // Seteo de formulario
        document.getElementById(BASE_ID+"Latitud").value  =resLat;
        document.getElementById(BASE_ID+"Longitud").value =resLon;
        // Seteo de mapa
        marcador.setLatLng({lat: resLat, lng: resLon}).addTo(map);
        map.setView({lat:resLat,lng:resLon}, mapFoundZoom);
      });
    } else {
      resLat = Number.parseFloat(sessionStorage.getItem(direccion+"Lat"));
      resLon = Number.parseFloat(sessionStorage.getItem(direccion+"Lon"));
      // TODO: Convertir el fragmento siguiente y el del último ´then´ en funciones.
      // Seteo de formulario
      document.getElementById(BASE_ID+"Latitud").value  =resLat;
      document.getElementById(BASE_ID+"Longitud").value =resLon;
      // Seteo de mapa
      marcador.setLatLng({lat: resLat, lng: resLon}).addTo(map);
      map.setView({lat:resLat,lng:resLon}, mapFoundZoom);
    }
  }

  document.getElementById("buscar-btn").addEventListener("click", buscador);
}