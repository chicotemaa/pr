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
 * @function usarNombre
 * @param {String} uri - Uri del formulario. eg: "/nombre-formulario/[formulario_id]"
 * @param {HTMLTableCellElement} td - Celda que contiene el nombre. eg: "&lt;td&gt;nombre&lt;/td>"
 */
function usarNombre(uri, td) {
  const newFormulario = document.createTextNode(sessionStorage.getItem(uri))
  td.appendChild(newFormulario);
}

/**
 * @function fetchNombre
 * @param {String} uri - eg: "/nombre-formulario/[id]"
 * @param {HTMLTableCellElement} td - Celda que contiene el nombre. eg: "&lt;td>nombre&lt;/td>"
 */
function fetchNombre(uri, td) {
  fetch(uri, {
    method: "GET",})
  .then(res => res.json())
  .then(data => {
    sessionStorage.setItem(uri, data.nombre)
    usarNombre(uri, td)
  });
}

//Para ver como twig parsea {{mercure()}}
const eventSource = new EventSource("http://127.0.0.1:3000/.well-known/mercure?topic=http%3A%2F%2F127.0.0.1%3A8000%2Fapi%2Forden_trabajos%2F%7Bid%7D");
//$(MERCURE_URL)+/topic/encodeURIComponent('http://127.0.0.1:8000/api/orden_trabajos/{id}')
eventSource.onmessage = event => {
  /** @type {MercureOrdenTrabajo} */
  const respuesta = JSON.parse(event.data);
  console.log(respuesta);
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
        console.log(typeof(respuesta.comentario));
        console.log(respuesta.comentario);
        renglon.children[5].children[0].innerHTML = respuesta.comentario;
      }
      renglon.children[7].children[0].setAttribute("value", String(respuesta.estado));
    }
  } else {
    // Creando el renglón
    const newRenglon = document.createElement("tr");
    newRenglon.setAttribute("data-id", String(respuesta.id));
    newRenglon.className= "nuevo";
    // Creando y agregando el checkbox
    const tdCheckbox = document.createElement("td");
    const checkbox = document.createElement("input");
    checkbox.setAttribute("type", "checkbox")
    checkbox.setAttribute("value", String(respuesta.id))
    checkbox.className = "form-batch-checkbox";
    tdCheckbox.appendChild(checkbox);
    // Creando y agregando columna ID
    const tdId = document.createElement("td");
    tdId.className = "sorted integer";
    const newId= document.createTextNode(String(respuesta.id));
    tdId.appendChild(newId);
    // Creando y agregando columna estado gestion
    const tdGestion = document.createElement("td");
    tdId.className = "integer";
    const newGestion = document.createElement("div");
    newGestion.setAttribute("value", String(respuesta.estadoGestion));
    newGestion.className = "estadoGestion";
    tdGestion.appendChild(newGestion);

    // Creando y agregando Formulario
    const tdFormulario = document.createElement("td");
    tdFormulario.className = "text";
    // la respuesta es la string del endpoint, no el título.
    const formularioId = respuesta.formulario.split("/").pop();
    const BASE_NOMBRE_FORM = "/nombre-formulario/";
    const formularioURI = BASE_NOMBRE_FORM + formularioId
    const nombreFormulario = sessionStorage.getItem(formularioURI)
    nombreFormulario ? usarNombre(formularioURI, tdFormulario)
                     : fetchNombre(formularioURI, tdFormulario)
    
    // Creando y agregando Usuario
    const tdUsuario = document.createElement("td");
    tdUsuario.className = "text"
    const usuarioId = respuesta.user.split("/").pop()
    const BASE_NOMBRE_USER = "/nombre-usuario/"
    const usuarioURI = BASE_NOMBRE_USER + usuarioId
    const nombreUsuario = sessionStorage.getItem(usuarioURI)
    nombreUsuario ? usarNombre(usuarioURI, tdUsuario)
                  : fetchNombre(usuarioURI, tdUsuario)

    // Creando y agregando Comentario
    const tdComentario = document.createElement("td");
    tdComentario.className="text";
    const newComentario= document.createElement("span");
    if (!respuesta.comentario) {
      newComentario.innerText ="Nulo";
      newComentario.className ="badge badge-secondary";
    } else {
      newComentario.innerText = respuesta.comentario;
    }
    tdComentario.appendChild(newComentario);

    // Creando y agregando Sucursal
    const tdSucursal = document.createElement("td");
    tdSucursal.className = "text"
    const sucursalId = respuesta.sucursal.split("/").pop()
    const BASE_NOMBRE_SUC = "/nombre-sucursal/"
    const sucursalURI = BASE_NOMBRE_SUC + sucursalId
    const nombreSucursal = sessionStorage.getItem(sucursalURI)
    nombreSucursal ? usarNombre(sucursalURI, tdSucursal)
                  : fetchNombre(sucursalURI, tdSucursal)

    // Creando y agregando Estado
    const tdEstado = document.createElement("td");
    tdEstado.className = "integer";
    const newEstado= document.createElement("div");
    newEstado.setAttribute("value", String(respuesta.estado));
    newEstado.className = "estado";
    tdEstado.appendChild(newEstado);

    // Creando y agregando Fecha
    const tdFecha = document.createElement("td");
    tdFecha.className = " date ";
    const newFecha = document.createElement("time");
    const fecha = new Date(respuesta.fecha);
    const l10nES = new Intl.DateTimeFormat("es-AR");
    newFecha.dateTime = fecha.toISOString();
    //newFecha.innerHTML= fecha.getDate()+"/"+(fecha.getMonth()+1)+"/"+fecha.getFullYear();
    newFecha.innerHTML = l10nES.format(fecha);
    tdFecha.appendChild(newFecha);

    // Creando y agregando HoraI
    const tdHoraI = document.createElement("td");
    tdHoraI.className = " datetime "
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
    tdHoraI.appendChild(newHoraI);

    // Creando y agregando HoraF
    const tdHoraF = document.createElement("td");
    tdHoraF.className = " datetime "
    const resHoraF = new Date(respuesta.horaFin);
    if (!respuesta.horaFin) {
      const newHoraF = document.createElement("span");
      newHoraF.innerText ="Nulo";
      newHoraF.className ="badge badge-secondary";
      tdHoraF.appendChild(newHoraF);
    } else {
      const newHoraF = document.createElement("time");
      const resHoraI = new Date(respuesta.horaFin);
      newHoraF.dateTime = resHoraI.toISOString();
      newHoraF.innerText = formatoISO.format(resHoraF);
      tdHoraF.appendChild(newHoraF);
    }
    // Creando y agregando Cliente
    const tdCliente = document.createElement("td");
    tdCliente.className = "text";
    // la respuesta es la string del endpoint, no el título.
    const clienteId = respuesta.cliente.split("/").pop();
    const BASE_NOMBRE_CLIENTE = "/nombre-cliente/";
    const clienteURI = BASE_NOMBRE_CLIENTE + clienteId
    const nombreCliente = sessionStorage.getItem(clienteURI)
    nombreCliente ? usarNombre(clienteURI, tdCliente)
                  : fetchNombre(clienteURI, tdCliente)

    // Creando y agregando SucursalCliente
    const tdSucursalCliente= document.createElement("td");
    tdSucursalCliente.className = "text";
    // la respuesta es la string del endpoint, no el título.
    const sucursalClienteId = respuesta.SucursalDeCliente.split("/").pop();
    const BASE_NOMBRE_SUC_CLIENTE = "/nombre-sucursal-cliente/"
    const sucursalClienteURI = BASE_NOMBRE_SUC_CLIENTE + sucursalClienteId
    const nombreSucursalCliente = sessionStorage.getItem(sucursalClienteURI)
    nombreSucursalCliente ? usarNombre(sucursalClienteURI, tdSucursalCliente)
                          : fetchNombre(sucursalClienteURI, tdSucursalCliente)


    // Agregando todo a newRenglon
    newRenglon.appendChild(tdCheckbox);
    newRenglon.appendChild(tdId);
    newRenglon.appendChild(tdGestion);
    newRenglon.appendChild(tdFormulario);
    newRenglon.appendChild(tdUsuario);
    newRenglon.appendChild(tdComentario);
    newRenglon.appendChild(tdSucursal);
    newRenglon.appendChild(tdEstado);
    newRenglon.appendChild(tdFecha);
    newRenglon.appendChild(tdHoraI);
    newRenglon.appendChild(tdHoraF);
    newRenglon.appendChild(tdCliente);
    newRenglon.appendChild(tdSucursalCliente);

    // Buscando la fila superior
    const renglonSup = document.querySelector('[data-id]');
    setTimeout( () => {if(renglonSup) {
      tabla.insertBefore(newRenglon, renglonSup)
    } else {
      // @ts-ignore
      document.querySelector('[class=no-results]').hidden = true;
      tabla.appendChild(newRenglon)
    }}, 10000)
  }
}