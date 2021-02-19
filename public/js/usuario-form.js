$(document).ready(function(){
    fieldClienteUrl();

    var container = document.getElementById("user_roles");



    var label = document.createElement('label');
    label.innerHTML = 'Select all:';
    var newdiv = document.createElement('div');
    newdiv.className = 'form-check';

    var input = document.createElement("input");
    input.type = "checkbox";
    input.className = "form-check-input";
    input.id = "all";
    input.onClick="toggle(this)";

    newdiv.appendChild(input);
    newdiv.appendChild(label);
    container.prepend(newdiv);


    $('#all').click(function(event) {
        if(this.checked) {
            // Iterate each checkbox
            $("input[name='user[roles][]']").each(function() {
                this.checked = true;
            });
        } else {
            $("input[name='user[roles][]']").each(function() {
                this.checked = false;
            });
        }
    });
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
