// @ts-check
//Para ver como twig parsea {{mercure()}}
const eventSource = new EventSource("http://127.0.0.1:3000/.well-known/mercure?topic=http%3A%2F%2F127.0.0.1%3A8000%2Fapi%2Forden_trabajos%2F%7Bid%7D");
//$(MERCURE_URL)+/topic/encodeURIComponent('http://127.0.0.1:8000/api/orden_trabajos/{id}')
eventSource.onmessage = event => {
  let respuesta = JSON.parse(event.data);
  console.log(respuesta);
  // Buscando el elemento tabla
  const tabla = document.querySelector('tbody');
  const renglon = document.querySelector('[data-id="'+respuesta.id+'"]');
  if(renglon) {
    if(respuesta.deleted) {
      tabla.removeChild(renglon);
    } else {
      renglon.children[2].children[0].setAttribute("value", respuesta.estadoGestion);
      if(!respuesta.comentario) {
        renglon.children[5].children[0].innerHTML = "Nulo";
        renglon.children[5].children[0].className = "badge badge-secondary";
      } else {
        console.log(typeof(respuesta.comentario));
        console.log(respuesta.comentario);
        renglon.children[5].children[0].innerHTML = respuesta.comentario;
      }
      renglon.children[7].children[0].setAttribute("value", respuesta.estado);
    }
  } else {
    // Creando el renglón
    const newRenglon = document.createElement("tr");
    newRenglon.setAttribute("data-id", respuesta.id);
    newRenglon.className= "nuevo";
    // Creando y agregando el checkbox
    const tdCheckbox = document.createElement("td");
    const checkbox = document.createElement("input");
    checkbox.setAttribute("type", "checkbox")
    checkbox.setAttribute("value", respuesta.id)
    checkbox.className = "form-batch-checkbox";
    tdCheckbox.appendChild(checkbox);
    // Creando y agregando columna ID
    const tdId = document.createElement("td");
    tdId.className = "sorted integer";
    const newId= document.createTextNode(respuesta.id);
    tdId.appendChild(newId);
    // Creando y agregando columna estado gestion
    const tdGestion = document.createElement("td");
    tdId.className = "integer";
    const newGestion = document.createElement("div");
    newGestion.setAttribute("value", respuesta.estadoGestion);
    newGestion.className = "estadoGestion";
    tdGestion.appendChild(newGestion);
    // Creando y agregando Formulario
    const tdFormulario = document.createElement("td");
    tdFormulario.className = "text";
    tdFormulario.id = "tdFormulario";
    //const newFormulario = document.createTextNode(respuesta.formulario);
    // la respuesta es la string del endpoint, no el título.
    let uri = respuesta.formulario.split("/").pop();
    fetch("/nombre-formulario/"+uri, {
      method: "GET",})
    .then(res => res.json())
    .then(data => {
      const newFormulario = document.createTextNode(data.nombre)
      const tdFormulario = document.getElementById("tdFormulario")
      tdFormulario.appendChild(newFormulario);
      tdFormulario.removeAttribute("id");
    });
    // Creando y agregando Usuario
    const tdUsuario = document.createElement("td");
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
    // Creando y agregando Estado
    const tdEstado = document.createElement("td");
    tdEstado.className = "integer";
    const newEstado= document.createElement("div");
    newEstado.setAttribute("value", respuesta.estado);
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
      newHoraI.innerText = respuesta.horaInicio;
    }
    tdComentario.appendChild(newComentario);
    // Creando y agregando HoraF
    const tdHoraF = document.createElement("td");
    tdHoraF.className = " datetime "
    // Creando y agregando Cliente
    const tdCliente = document.createElement("td");
    // Creando y agregando SucursalCliente
    const tdSucursalCliente= document.createElement("td");

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
    if(renglonSup) {
      tabla.insertBefore(newRenglon, renglonSup);
    } else {
      // @ts-ignore
      document.querySelector('[class=no-results]').hidden = true;
      tabla.appendChild(newRenglon)
    }
  }
}