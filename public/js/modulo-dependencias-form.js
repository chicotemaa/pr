$(document).ready(function(){
  for (var i = 0; i < $(".field-modulo_propiedadItems").length; i++) {
    fieldOpcionDependechangeUrl(i);
  }
});

function fieldOpcionDependechangeUrl (i) {
    $("#modulo_dependencias_propiedadItems_"+i+"_opcionDepende").select2({
        theme: 'bootstrap',
        ajax: {
            data: function (params) {
                return { 'query': params.term, propiedadItem: $("#modulo_dependencias_propiedadItems_"+i+"_dependePropiedadItem").val() }
            },
            url: function () {
              return '/admin/opcion/by/propiedaditems';
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
    $("#modulo_dependencias_propiedadItems_"+i+"_dependePropiedadItem").on("change", function(e) {
        $('#modulo_dependencias_propiedadItems_'+i+'_opcionDepende').empty();
    })

    $("#modulo_dependencias_propiedadItems_"+i+"_dependePropiedadItem").select2({
          placeholder: ' ',
          allowClear: true,
          theme: 'bootstrap',
    })
}
