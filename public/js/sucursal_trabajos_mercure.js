//Para ver como twig parsea {{mercure()}}
const eventSource = new EventSource("http://127.0.0.1:3000/.well-known/mercure?topic=http%3A%2F%2F127.0.0.1%3A8000%2Fapi%2Forden_trabajos%2F%7Bid%7D");
eventSource.onmessage = event => {
  let respuesta = JSON.parse(event.data);
  console.log(respuesta);
  let renglon = document.querySelector('[data-id="'+respuesta.id+'"]');
  if(renglon) {
    renglon.children[7].children[0].setAttribute("value", respuesta.estado);
  } else {
    // El elemento tabla
    const tabla = document.querySelector('tbody');
    // La fila superior
    const renglonSup = document.querySelector('[data-id]');
    const newRenglon = document.createElement("tr");
    newRenglon.setAttribute("data-id", respuesta.id);

    tabla.insertBefore(newRenglon, renglonSup);
  }
}