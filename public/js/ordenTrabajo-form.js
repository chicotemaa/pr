$(document).ready(function(){
    fieldUserUrl();
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
