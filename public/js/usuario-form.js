$(document).ready(function(){
    fieldClienteUrl();
});

function fieldClienteUrl () {

    $("#user_cliente").select2({
         theme: 'bootstrap',
         ajax: {
            data: function (params) {
                return { 'query': params.term }
            },
            url: function () {
              return '/cliente/sin/usuarios';
            },
            dataType: 'json',
            processResults: function (data, params) {
                return {
                    results: data.results
                };
            },
            placeholder: '',
            allowClear: true
          }
    })
}
