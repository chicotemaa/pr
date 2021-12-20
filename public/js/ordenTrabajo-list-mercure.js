// @ts-check

/**
 * @typedef {Object} MercureOrdenTrabajo
 * @property {string} Facility - eg: "/api/facilities/[facility_id]"
 * @property {string} SucursalDeCliente - "/api/sucursal_de_clientes/[sucursal_cliente_id]"
 * @property {string} cliente - "/api/clientes/[cliente_id]"
 * @property {String} comentario
 * @property {string} createdAt - ISO datetime, eg: "YYYY-MM-DDTHH:mm:SS+[TZ]"
 * @property {Boolean} deleted
 * @property {string} deletedAt - ISO datetime
 * @property {0 | 1 | 2 | 3 | 4 | 5} estado
 * @property {0 | 1 | 2} estadoGestion
 * @property {string[]} estados
 * @property {string[]} estadosGestion
 * @property {string} fecha - ISO datetime con horas en cero pero TZ correcto
 * @property {? | null} firma
 * @property {String} formulario
 * @property {?String} formularioResultado
 * @property {String} horaDesde - ISO time con fecha 1970-01-01, configurada al crear la OT
 * @property {?String} horaFin - ISO time o null, seteada por la aplicación del técnico
 * @property {String} horaHasta - ISO time con fecha 1970-01-01, configurada al crear la OT
 * @property {?String} horaInicio - ISO time o null, seteada por la ap del técnico
 * @property {Number} id - id de la órden de trabajo
 * @property {?} imageFile
 * @property {?} imageName
 * @property {?} imageSize
 * @property {?Number} latitud - ISO 6709
 * @property {?Number} latitudCierre - ISO 6709
 * @property {?Number} longitud - ISO 6709
 * @property {?Number} longitudCierre - ISO 6709
 * @property {?String} motivo
 * @property {Number} orden
 * @property {?} responsableFirma
 * @property {String} servicio - eg: "/api/servicios/[servicio_id]"
 * @property {?} solicitud
 * @property {String} sucursal - Sucursal de hogar. eg: "/api/sucursals/[sucursal_id]"
 * @property {String} updatedAt - ISO datetime de última modificación
 * @property {String} user - Técnico a despachar. eg: "/api/users/[usuario_id]"
 */

/**
 * @typedef  {Object} URIs
 * @property {String} id
 * @property {String} formulario
 * @property {String} usuario
 * @property {String} sucursal
 * @property {String} cliente
 * @property {String} sucCliente
 */

/**
 * @typedef {Object} TdsFetch
 * @property {HTMLTableCellElement} tdId
 * @property {HTMLTableCellElement} tdFormulario
 * @property {HTMLTableCellElement} tdUsuario
 * @property {HTMLTableCellElement} tdSucursal
 * @property {HTMLTableCellElement} tdCliente
 * @property {HTMLTableCellElement} tdSucursalCliente
 * 
 */

/**
 * @typedef {Object} Tds
 * @property {HTMLTableCellElement} tdCheckbox
 * @property {HTMLTableCellElement} tdGestion
 * @property {HTMLTableCellElement} tdComentario
 * @property {HTMLTableCellElement} tdEstado
 * @property {HTMLTableCellElement} tdId
 * @property {HTMLTableCellElement} tdFormulario
 * @property {HTMLTableCellElement} tdUsuario
 * @property {HTMLTableCellElement} tdSucursal
 * @property {HTMLTableCellElement} tdCliente
 * @property {HTMLTableCellElement} tdSucursalCliente
 * @property {HTMLTableCellElement} tdFecha
 * @property {HTMLTableCellElement} tdHoraI
 * @property {HTMLTableCellElement} tdHoraF
 */

/**
 * @function usarNombre
 * @param {String} uri - Uri del formulario. eg: "/nombre-formulario/[formulario_id]"
 * @param {TdsFetch} td - Celda que contiene el nombre. eg: "&lt;td&gt;nombre&lt;/td>"
 */
/*
function usarNombre(uri, td) {
  td.appendChild(newFormulario);
}
*/

/**
 * @function fetchNombres
 * @returns {Promise}
 * @param {String} otId - eg: "/nombre-formulario/[id]"
 * @param {TdsFetch} tds - Objeto de objetos que contienen los nombres.
 */
function fetchNombres(otId, tds) {
  let promise = fetch(otId, {
    method: "GET",})
  .then((res) => res.json())
  .then((data) => {
    /** @type URIs */
    const dataP = data
    tds.tdCliente.appendChild(document.createTextNode(dataP.cliente))
    tds.tdFormulario.appendChild(document.createTextNode(dataP.formulario))
    tds.tdSucursal.appendChild(document.createTextNode(dataP.sucursal))
    tds.tdSucursalCliente.appendChild(document.createTextNode(dataP.sucCliente))
    tds.tdUsuario.appendChild(document.createTextNode(dataP.usuario))
    return Promise.resolve(0)
  });
  return promise;
}

//Para ver como twig parsea {{mercure()}}
const ubicacion = new URL(window.location.href)
const eventSource = new EventSource(ubicacion.protocol+"//"+ubicacion.hostname+":3000/.well-known/mercure?topic=http%3A%2F%2F127.0.0.1%3A8000%2Fapi%2Forden_trabajos%2F%7Bid%7D");
//$(MERCURE_URL)+/topic/encodeURIComponent('http://127.0.0.1:8000/api/orden_trabajos/{id}')
eventSource.onmessage = event => {
  /** @type {MercureOrdenTrabajo} */
  const respuesta = JSON.parse(event.data);
  // Buscando el elemento tabla
  const tabla = document.querySelector('tbody');
  const renglon = document.querySelector('[data-id="'+respuesta.id+'"]');
  if(renglon) {
    if(respuesta.deleted) {
      tabla.removeChild(renglon);
    } else {
      renglon.children[2].children[0].setAttribute("value", String(respuesta.estadoGestion));
      if(!respuesta.comentario) {
        renglon.children[5].children[0].innerHTML = "Nulo";
        renglon.children[5].children[0].className = "badge badge-secondary";
      } else {
        renglon.children[5].children[0].innerHTML = respuesta.comentario;
      }
      renglon.children[7].children[0].setAttribute("value", String(respuesta.estado));
    }
  } else {
    // Creando el renglón
    const newRenglon = document.createElement("tr");
    newRenglon.setAttribute("data-id", String(respuesta.id));
    newRenglon.className= "nuevo";
    
    // Creando tds
    /**
     * @type {Tds}
     */
    let tds = {
      tdCheckbox:   document.createElement("td"),
      tdId:         document.createElement("td"),
      tdGestion:    document.createElement("td"),
      tdFormulario: document.createElement("td"),
      tdUsuario:    document.createElement("td"),
      tdComentario: document.createElement("td"),
      tdSucursal:   document.createElement("td"),
      tdEstado:     document.createElement("td"),
      tdFecha:      document.createElement("td"),
      tdHoraI:      document.createElement("td"),
      tdHoraF:      document.createElement("td"),
      tdCliente:    document.createElement("td"),
      tdSucursalCliente: document.createElement("td")
    };

    // Agregando el checkbox
    const checkbox = document.createElement("input");
    checkbox.setAttribute("type", "checkbox")
    checkbox.setAttribute("value", String(respuesta.id))
    checkbox.className = "form-batch-checkbox";

   
    tds.tdCheckbox.appendChild(checkbox);

    // Agregando columna ID
    tds.tdId.className = "sorted integer";
    const newId= document.createTextNode(String(respuesta.id));
    tds.tdId.appendChild(newId);

    // Agregando columna estado gestion
    tds.tdGestion.className = "integer";
    const newGestion = document.createElement("div");
    newGestion.setAttribute("value", String(respuesta.estadoGestion));
    newGestion.className = "estadoGestion";
    tds.tdGestion.appendChild(newGestion);

    // Agregando Formulario
    tds.tdFormulario.className = "text";
    // la respuesta es la string del endpoint, no el título.
    
    // Agregando Usuario
    tds.tdUsuario.className = "text"
    

    // Agregando Comentario
    tds.tdComentario.className="text";
    const newComentario= document.createElement("span");
    if (!respuesta.comentario) {
      newComentario.innerText ="Nulo";
      newComentario.className ="badge badge-secondary";
    } else {
      newComentario.innerText = respuesta.comentario;
    }
    tds.tdComentario.appendChild(newComentario);

    // Agregando Sucursal
    tds.tdSucursal.className = "text"
    
    // Agregando Estado
    tds.tdEstado.className = "integer";
    const newEstado= document.createElement("div");
    newEstado.setAttribute("value", String(respuesta.estado));
    newEstado.className = "estado";
    tds.tdEstado.appendChild(newEstado);

    // Agregando Fecha
    tds.tdFecha.className = " date ";
    const newFecha = document.createElement("time");
    const fecha = new Date(respuesta.fecha);
    const l10nES = new Intl.DateTimeFormat("es-AR");
    newFecha.dateTime = fecha.toISOString();
    //newFecha.innerHTML= fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear();
    newFecha.innerHTML = l10nES.format(fecha);
    tds.tdFecha.appendChild(newFecha);

    // Agregando HoraIdocument.createElement("td")
    tds.tdHoraI.className = " datetime "
    const newHoraI= document.createElement("time");
    // Formato de hora ISO
    const formatoISO = new Intl.DateTimeFormat("ISO", {
      timeStyle: "short"
    })
    if (!respuesta.horaInicio) {
      newHoraI.innerText ="Nulo";
      newHoraI.className ="badge badge-secondary";
    } else {
      const resHoraI = new Date(respuesta.horaInicio)
      newHoraI.dateTime = resHoraI.toISOString();
      newHoraI.innerText = formatoISO.format(resHoraI);
    }
    tds.tdHoraI.appendChild(newHoraI);

    // Agregando HoraF
    tds.tdHoraF.className = " datetime "
    const resHoraF = new Date(respuesta.horaFin);
    if (!respuesta.horaFin) {
      const newHoraF = document.createElement("span");
      newHoraF.innerText ="Nulo";
      newHoraF.className ="badge badge-secondary";
      tds.tdHoraF.appendChild(newHoraF);
    } else {
      const newHoraF = document.createElement("time");
      const resHoraI = new Date(respuesta.horaFin);
      newHoraF.dateTime = resHoraI.toISOString();
      newHoraF.innerText = formatoISO.format(resHoraF);
      tds.tdHoraF.appendChild(newHoraF);
    }
    // Agregando Cliente
    tds.tdCliente.className = "text";
    // la respuesta es la string del endpoint, no el título.

    // Agregando SucursalCliente
    tds.tdSucursalCliente.className = "text";
    // la respuesta es la string del endpoint, no el título.

    

    // Agregando todo a newRenglon
    for(const td in tds){
      newRenglon.appendChild(tds[td]);
    }

    // Buscando la fila superior
    const renglonSup = document.querySelector('[data-id]');
    /** @type TdsFetch */
    let tdsFetch = {
      tdId: tds.tdId,
      tdFormulario: tds.tdFormulario,
      tdUsuario: tds.tdUsuario,
      tdSucursal: tds.tdSucursal,
      tdCliente: tds.tdCliente,
      tdSucursalCliente: tds.tdSucursalCliente,
    }
    const RUTA = "/nombres-OT/"+respuesta.id
    Promise.all([
      fetchNombres(RUTA, tdsFetch)
    ]).then(() => {if(renglonSup) {
      tabla.insertBefore(newRenglon, renglonSup)
    } else {
      // @ts-ignore
      document.querySelector('[class=no-results]').hidden = true;
      tabla.appendChild(newRenglon)
    }})
    
  }
}