<!DOCTYPE html>
<html>
    <head>
        <title>Place Autocomplete Address Form</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */
            #map {
                height: 100%;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
        <style>
            #locationField, #controls {
                position: relative;
                width: 480px;
            }
            #autocomplete {
                position: absolute;
                top: 0px;
                left: 0px;
                width: 99%;
            }
            .label {
                text-align: right;
                font-weight: bold;
                width: 100px;
                color: #303030;
                font-family: "Roboto";
            }
            #address {
                border: 1px solid #000090;
                background-color: #f0f9ff;
                width: 480px;
                padding-right: 2px;
            }
            #address td {
                font-size: 10pt;
            }
            .field {
                width: 99%;
            }
            .slimField {
                width: 80px;
            }
            .wideField {
                width: 200px;
            }
            #locationField {
                height: 20px;
                margin-bottom: 2px;
            }
        </style>
    </head>

    <body>
        <div id="locationField">
            <input id="autocomplete"
                   placeholder="Enter your address"
                   onFocus="geolocate()"
                   type="text"/>
        </div>

        <table id="address">
            <tr>
                <td class="label">Street address</td>
                <td class="slimField"><input class="field" id="street_number" disabled="true"/></td>
                <td class="wideField" colspan="2"><input class="field" id="route" disabled="true"/></td>
            </tr>
            <tr>
                <td class="label">City</td>
                <td class="wideField" colspan="3"><input class="field" id="locality" disabled="true"/></td>
            </tr>
            <tr>
                <td class="label">State</td>
                <td class="slimField"><input class="field" id="administrative_area_level_1" disabled="true"/></td>
                <td class="label">Zip code</td>
                <td class="wideField"><input class="field" id="postal_code" disabled="true"/></td>
            </tr>
            <tr>
                <td class="label">Latitude</td>
                <td class="slimField"><input class="field" id="latitude" disabled="true"/></td>
                <td class="label">Longitude</td>
                <td class="wideField"><input class="field" id="longitude" disabled="true"/></td>
            </tr>
            <tr>
                <td class="label">Country</td>
                <td class="wideField" colspan="3"><input class="field" id="country" disabled="true"/></td>
            </tr>
        </table>

        <script>
            var placeSearch, autocomplete;

            var componentForm = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name',
                latitude: 'latitude',
                longitude: 'longitude',
            };

            function initAutocomplete() {
                // Create the autocomplete object, restricting the search predictions to
                // geographical location types.
                autocomplete = new google.maps.places.Autocomplete(
                        document.getElementById('autocomplete'), {types: ['geocode']});

                // Avoid paying for data that you don't need by restricting the set of
                // place fields that are returned to just the address components.
                autocomplete.setFields(['address_component', 'geometry']);

                // When the user selects an address from the drop-down, populate the
                // address fields in the form.
                autocomplete.addListener('place_changed', fillInAddress);
            }

            function fillInAddress() {
                // Get the place details from the autocomplete object.
                var place = autocomplete.getPlace();

                for (var component in componentForm) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }



                // Get each component of the address from the place details,
                // and then fill-in the corresponding field on the form.
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        document.getElementById(addressType).value = val;
                    }
                }

                var lat = place.geometry.location.lat(),
                        lng = place.geometry.location.lng();

                if (lat != "" && lng != "") {
                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;
                }
            }

            // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.
            function geolocate() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var geolocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        var circle = new google.maps.Circle({center: geolocation, radius: position.coords.accuracy});
                        autocomplete.setBounds(circle.getBounds());
                    });
                }
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=KEY&libraries=places&callback=initAutocomplete"
        async defer></script>
    </body>
</html>
