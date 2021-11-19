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
var map = L.map('mapaSucursal').setView({lat: -27.41117, lon: -59.00012}, 5);

// add the OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
}).addTo(map);

// show the scale bar on the lower left corner
L.control.scale({imperial: false, metric: true}).addTo(map);

//Inicializar marcador
let marcador = L.marker({lat: 0, lng: 0}, {draggable: true});
function onMapClick(e) {
    /*Interactividad, clics*/
    const BaseId = "sucursaldecliente_";

    // Moficia el mapa, dando retroalimentación
    marcador.addTo(map);
    marcador.setLatLng({lat: e.latlng.lat, lng: e.latlng.lng});
    // Modifica el formulario
    document.getElementById(BaseId+"Latitud").value = Number.parseFloat(e.latlng.lat).toFixed(5);
    document.getElementById(BaseId+"Longitud").value= Number.parseFloat(e.latlng.lng).toFixed(5);
}
map.on('click', onMapClick);
}