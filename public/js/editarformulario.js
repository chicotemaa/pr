function addFoto(id) {
    let add = document.getElementById('add-' + id);
    let form = document.getElementById('form-' + id);
    add.classList.remove("d-flex");
    add.classList.add("d-none");

    form.classList.remove("d-none");
    form.classList.add("d-grid");
    //form.style.display='block';

}
function replaceFoto(id) {
    let add = document.getElementById('add-' + id);
    let form = document.getElementById('form2-' + id);
    add.classList.remove("d-flex");
    add.classList.add("d-none");

    form.classList.remove("d-none");
    form.classList.add("d-grid");
    //form.style.display='block';

}
$(".reemplazar-foto").on('submit', function(id) {
    id.preventDefault();
    let idResultado= id.currentTarget[1].id
    let imagen= "#"+id.currentTarget[0].id;
    const tk="cTowlSvzOEm9hBknZeEs6rpEp2c7uC9L3Zvr8P_Um0Q";
    let files = $(imagen).prop('files')[0];
    let formData = new FormData();
    formData.append("file", files);
    $.ajax({
            processData: false,
            contentType: false,
            type : "POST" ,
            url : "/api/media_objects",
            data : formData,
            headers: {'Authorization': 'Bearer ' + tk },
            success: function(response) {
                let idNombreFoto= response.filePath;
                reemplaceFoto(idResultado,idNombreFoto)
            if (response != 0) {
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            },
            error: function(){
                alert('no se pudo realizar carga probar nuevamente mas tarde') 
            }
        })

    return false;
});
$(".nueva-foto").on('submit', function(id) {
    id.preventDefault();
    let idResultado= {'id':id.currentTarget[1].id}
    let imagen= "#"+id.currentTarget[0].id;
    const tk="cTowlSvzOEm9hBknZeEs6rpEp2c7uC9L3Zvr8P_Um0Q";
    let files = $(imagen).prop('files')[0];
    console.log(files)
    let formData = new FormData();
    formData.append("file", files);
    $.ajax({
            processData: false,
            contentType: false,
            type : "POST" ,
            url : "/api/media_objects",
            data : formData,
            headers: {'Authorization': 'Bearer ' + tk },
            success: function(response) {
                let idNombreFoto= response.filePath;
                nuevaFoto(idResultado, idNombreFoto)
            if (response != 0) {
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            },
            error: function(){
                alert('no se pudo realizar carga probar nuevamente mas tarde') 
            }
        })

    return false;
});

$(".reemplazar-foto").on('submit', function(id) {
    id.preventDefault();
    let idResultado= id.currentTarget[1].id
    let imagen= "#"+id.currentTarget[0].id;
    const tk="cTowlSvzOEm9hBknZeEs6rpEp2c7uC9L3Zvr8P_Um0Q";
    let files = $(imagen).prop('files')[0];
    let formData = new FormData();
    formData.append("file", files);
    $.ajax({
            processData: false,
            contentType: false,
            type : "POST" ,
            url : "/api/media_objects",
            data : formData,
            headers: {'Authorization': 'Bearer ' + tk },
            success: function(response) {
                let idNombreFoto= response.filePath;
                reemplaceFoto(idResultado,idNombreFoto)
            if (response != 0) {
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            },
            error: function(){
                alert('no se pudo realizar carga probar nuevamente mas tarde') 
            }
        })

    return false;
});


function reemplaceFoto(idResultado, idNombreFoto) {
    let datos = {
        'id': idResultado,
        'namePath': idNombreFoto
    }
    $.ajax({
        "type": "POST",
        "url": "/reemplazar-foto/",
        "data": datos,
        success: function (r) {
            let newImage = document.getElementById('ViejoDato-' + idResultado);

            newImage.setAttribute('src', "/uploads/imagenes/resultado/" + idNombreFoto)

        },
    })
}
function nuevaFoto(idResultado, idNombreFoto) {
    let datos = {
        'id': idResultado,
        'imageName': idNombreFoto,
    }
    $.ajax({
        "type": "POST",
        "url": "/new-photo",
        "data": datos,
        success: function (r) {
            window.location.reload();
        },
    })
}
function saveFoto(id) {
    let add = document.getElementById('add-' + id);
    let form = document.getElementById('form-' + id);
    add.classList.remove("d-none");
    add.classList.add("d-flex");

    form.classList.remove("d-grid");
    form.classList.add("d-none");
    //form.style.display='block';


}
function saveFotoTwo(id) {
    let add = document.getElementById('add-' + id);
    let form = document.getElementById('form2-' + id);
    add.classList.remove("d-none");
    add.classList.add("d-flex");

    form.classList.remove("d-grid");
    form.classList.add("d-none");
    //form.style.display='block';


}
function eliminarFoto(id) {
    let ViejoDato = document.getElementById('ViejoDato-' + id);
    let guardar = document.getElementById('guardar-' + id);
    let btneditar = document.getElementById('editar-' + id);
    btneditar.style.display = 'none';
    guardar.style.display = 'block';
    ViejoDato.style.display = 'none';
    let deleted = 1;
    let datos = {
        'id': id,
        'deleted': deleted
    }
    $.ajax({
        "type": "POST",
        "url": "/editar-formulario/",
        "data": datos,
        success: function (r) {
        }
    })
}

function recuperarFoto(id) {

    let deleted = null;
    let datos = {
        'id': id,
        'deleted': deleted
    }
    $.ajax({
        "type": "POST",
        "url": "/editar-formulario/",
        "data": datos,
        success: function (r) {
        }
    })
    let ViejoDato = document.getElementById('ViejoDato-' + id);
    let guardar = document.getElementById('guardar-' + id);
    let btneditar = document.getElementById('editar-' + id);
    btneditar.style.display = 'inline-block';
    guardar.style.display = 'none';
    ViejoDato.style.display = 'inline-block';
}
function editarDatos(id) {

    let ViejoDato = document.getElementById('ViejoDato-' + id);
    let guardar = document.getElementById('guardar-' + id);
    let btnguardar = document.getElementById('btnguardar-' + id);
    let btneditar = document.getElementById('editar-' + id);
    btneditar.style.display = 'none';
    guardar.style.display = 'inline-block';
    btnguardar.style.display = 'inline-block';
    ViejoDato.style.display = 'none';
}
function guardarDatos(id) {

    let completo = document.getElementById('completo-' + id);
    let incompleto = document.getElementById('completo-' + id);
    let ViejoDato = document.getElementById('ViejoDato-' + id);
    let guardar = document.getElementById('guardar-' + id);
    let btnguardar = document.getElementById('btnguardar-' + id);
    let btneditar = document.getElementById('editar-' + id);
    ViejoDato.style.display = 'inline-block';
    btneditar.style.display = 'inline-block';
    guardar.style.display = 'none';
    btnguardar.style.display = 'none';
    let valor = guardar.value;
    let datos = {
        'id': id,
        'valor': valor
    }
    $.ajax({
        "type": "POST",
        "url": "/editar-formulario/",
        "data": datos,
        beforeSend: function () {
            ViejoDato.innerHTML = datos.valor;
        },
        success: function (r) {
            if (r == 1) {
                ViejoDato.innerHTML = datos.valor;
                completo.style.display = "inline-block";
                setTimeout(function () { completo.style.display = "none"; }, 4000);
                //alert("formulario editado");
            } else {
                incompleto.style.display = "inline-block";
                setTimeout(function () { incompleto.style.display = "none"; }, 4000);
            }
        }
    })
}
