
(function($){
  $(function () {
    $("[data-prototype]").length && $("[data-prototype]").on(
      "easyadmin.collection.item-added", function () {
        // var isNew = 0
        /* if(isNew === 1){
          createAutoCompleteFields();
        }else{
          createAutoCompleteFieldsCustom();
        } */
        var itemIdCollection = $('[data-easyadmin-autocomplete-url]').last().attr('id').split("_")[2];
        fieldOpcionValues(itemIdCollection);

      });
    });
    /* global jQuery */
  }(jQuery));


$(document).ready(function(){
  for (var i = 0; i < $("[data-prototype]").length; i++) {
    fieldOpcionValues(i);
  }
});

function fieldOpcionValues(itemIdCollection){
  $("#modulo_propiedadItems_"+itemIdCollection+"_item_autocomplete").on('select2:select', function (e) {

    if(Array.isArray(e.params.data.opciones) == true && e.params.data.opciones.length > 0){
        $('#modulo_propiedadItems_'+itemIdCollection+'_opcion').empty();
        for (var i = 0; i < e.params.data.opciones.length; i++) {
          var newOption = new Option( e.params.data.opciones[i].text, e.params.data.opciones[i].id, true, true);
          // Append it to the select
          $('#modulo_propiedadItems_'+itemIdCollection+'_opcion').append(newOption);
        }
        $('#modulo_propiedadItems_'+itemIdCollection+'_opcion').val(['']).trigger("change")
    }
    $("#modulo_propiedadItems_"+itemIdCollection+"_item_autocomplete").on("select2:unselecting", function(e) {
        $('#modulo_propiedadItems_'+itemIdCollection+'_opcion').empty();
    });
  });

  $('#modulo_propiedadItems_'+itemIdCollection).find('select[data-widget="select2"]').select2({
      placeholder: ' ',
      allowClear: true,
      theme: 'bootstrap',
  });
}

/* function createAutoCompleteFieldsCustom(){
    console.log("custom");
    var autocompleteFields = $('[data-easyadmin-autocomplete-url]');

    autocompleteFields.each(function () {
        var $this = $(this),
        url = $this.data('easyadmin-autocomplete-url');

        var dataCustom = function (params) {
            return { 'query': params.term, 'page': params.page, item: "qweqwe" };
        }

        $this.select2({
            theme: 'bootstrap',
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: dataCustom,
                // to indicate that infinite scrolling can be used
                processResults: function (data, params) {
                    return {
                        results: data.results,
                        pagination: {
                            more: data.has_next_page
                        }
                    };
                },
                cache: true
            },
            placeholder: '',
            allowClear: true,
            minimumInputLength: 1
        });
    });
} */
