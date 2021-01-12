$(document).ready(function () {
  ocultarCampos();

  $("#cliente_condicionIVA").on('change', function() {
      if ($(this).val() == 3) {
          $("#cliente_cuit").parent().parent().hide();
      }else {
          $("#cliente_cuit").parent().parent().show();
      }
  })
});

function ocultarCampos(){
  if ($("#cliente_condicionIVA").val() == 3) {
      $("#cliente_cuit").parent().parent().hide();
  }else {
      $("#cliente_cuit").parent().parent().show();
  }
}

var googleAddressOptions = {
    'idAutocomplete': 'cliente_autocompleteAddress'
}
var componentForm = {
    street: 'cliente_street',
    locality: 'cliente_city',
    administrative_area_level_1: 'cliente_province',
    country: 'cliente_country',
};
