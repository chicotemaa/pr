// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

var placeSearch, autocomplete;

function initAutocomplete() {
    // console.log(googleAddressOptions);
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById(googleAddressOptions.idAutocomplete)),
        {types: ['geocode']});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    for (var component in componentForm) {
        document.getElementById(componentForm[component]).value = '';
        document.getElementById(componentForm[component]).disabled = false;
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    var valRoute = '';
    var valStreetNumber = '';

    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (addressType === 'route') {
            valRoute = place.address_components[i]['long_name'];
        } else if (addressType === 'street_number') {
            valStreetNumber = place.address_components[i]['long_name'];
        } else if (componentForm[addressType]) {
            var val = place.address_components[i]['long_name'];
            document.getElementById(componentForm[addressType]).value = val;
        }
    }

    document.getElementById(componentForm['street']).value = valRoute + ' ' + valStreetNumber;
}
