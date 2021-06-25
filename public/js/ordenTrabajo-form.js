$(document).ready(function(){
    fieldUserUrl();
    $("#ordentrabajo_horaInicio").change(function() {
        let Imunutos=document.getElementById('ordentrabajo_horaInicio').value;
        iniciominutos.setAttribute('value',Imunutos);
        let iniciomin=new Date(document.getElementById('ordentrabajo_horaInicio').value);
        let finmin=new Date(document.getElementById('ordentrabajo_horaFin').value);
        let resultadominutos=(finmin.getTime()-iniciomin.getTime())/60000;
        minutos.setAttribute('value',resultadominutos);
        
    });
    $("#ordentrabajo_horaFin").change(function() {
        let Imunutos=document.getElementById('ordentrabajo_horaFin').value;
        finminutos.setAttribute('value',Imunutos);
        let iniciomin=new Date(document.getElementById('ordentrabajo_horaInicio').value);
        let finmin=new Date(document.getElementById('ordentrabajo_horaFin').value);
        let resultadominutos=(finmin.getTime()-iniciomin.getTime())/60000;
        minutos.setAttribute('value',resultadominutos);
        
    });
    $("#ordentrabajo_SucursalDeCliente").change(function() {
       console.log(SucursalDecliente.value);
    })
});


$("#btnQuitarSolicitud").click(function(){
    $("#ordentrabajo_solicitudOpcion").val(1)
    $(this).parent().parent().parent().parent().hide()
})

function fieldUserUrl () {

    $("#ordentrabajo_user").select2({
         theme: 'bootstrap',
         ajax: {
            data: function (params) {
                return { 'query': params.term }
                
            },
            url: function (data, params) {
              return '/admin/user/by/sucursal'
             
            },
            dataType: 'json',
            processResults: function (data, params) {
                
                return {
                    results: data.results
                }
            },
            
            placeholder: '',
            allowClear: true
          },
    })
}
var minutos=document.getElementById('ordentrabajo_minutos');
    var iniciominutos = document.getElementById('ordentrabajo_horaInicio')
    var finminutos = document.getElementById('ordentrabajo_horaFin')
    var SucursalDecliente = document.getElementById('ordentrabajo_SucursalDeCliente')
    var idSucursalCliente=SucursalDecliente.value


    
    $("#edit-ordentrabajo-form").submit(function(){
        let minutost=document.getElementById('ordentrabajo_minutos').value
        let valorminutos=minutost;
        let id=valorId;
        let datos={
            'id':parseInt(id),
            'valor':parseInt(valorminutos)}
        $.ajax({
            "type": "POST" ,
            "url": "/editar-formularioExpress/",
            "data":datos,
            success:function(r){
               minutos.setAttribute('value',datos.valor);  
            }
            })
        });
        