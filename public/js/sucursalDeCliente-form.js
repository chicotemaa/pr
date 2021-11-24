document.body.onload = function() {
/*
Este módulo debe 
1. recibir la ubicación del mapa
2. rellenar el formulario

Ejemplo
1. Cursor en el centro de la plaza de Resistencia, Chaco
sucursalMapa.lat(); // -27.45117597008212
sucursalMapa.lng(); // -58.98649737984137
*/

// initialize Leaflet
var map = L.map('mapaSucursal');

// add the OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
}).addTo(map);

// show the scale bar on the lower left corner
L.control.scale({imperial: false, metric: true}).addTo(map);

// Inicializar marcador
let marcador = L.marker({lat: 0, lng: 0}, {draggable: false});
// Si hay coordenadas, aplicarlas al mapa
const BASE_ID = "sucursaldecliente_";
const HTML_LAT = document.getElementById(BASE_ID+"Latitud").value;
const HTML_LON = document.getElementById(BASE_ID+"Longitud").value;
if (HTML_LAT !== "" && HTML_LON !== "") {
  let formLat = Number.parseFloat(HTML_LAT.replace(/,/g, '.'));
  let formLon = Number.parseFloat(HTML_LON.replace(/,/g, '.'));
  map.setView({lat:formLat,lng:formLon}, 15);
  marcador.setLatLng(L.latLng(formLat, formLon)).addTo(map);
} else {
  map.setView({lat: -27.41117, lon: -59.00012}, 5)
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

/* Buscador */
document.getElementById("buscar-btn").addEventListener("click", function() {
  const direccion = document.getElementById("direccion").value;
  let endpoint = new URL('/search', 'https://nominatim.openstreetmap.org');
  let query = new URLSearchParams({'q': direccion,'format': 'jsonv2'});
  endpoint.search = query;
  console.log(endpoint);
  fetch(endpoint).then(data => data.json())
  .then(function(res) {
    const resLat = res[0].lat;
    const resLon = res[0].lon;
    document.getElementById(BASE_ID+"Latitud").value = resLat;
    document.getElementById(BASE_ID+"Longitud").value =resLon;
    marcador.setLatLng({lat: resLat, lng: resLon}).addTo(map);
    map.setView({lat:resLat,lng:resLon}, 15);
  })
})
}