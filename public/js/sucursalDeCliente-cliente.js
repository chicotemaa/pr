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
  // Si hay coordenadas, aplicarlas al mapa
  map.setView({lat:entidadLat,lng:entidadLon}, mapWelcomeZoom);
  if (entidadCoord) {
    map.setZoom(mapFoundZoom);
    marcador.setLatLng(L.latLng(entidadLat, entidadLon)).addTo(map);
  }

}