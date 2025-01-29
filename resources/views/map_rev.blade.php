<!DOCTYPE html>
<html>
<head>
    <title>Google Map with Current Location and Address</title>
    <style>
        /* Set the size of the map */
        #map {
            height: 400px;  /* Set the height */
            width: 400px;    /* The width is the width of the web page */
        }
    </style>
    <!-- Load the Google Maps JavaScript API script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=you_google_api_key" async defer></script>
    <script>
        function initMap() {
            if (navigator.geolocation) {
                // Get current location
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    // The map, centered at the current location
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 12,
                        center: {lat: lat, lng: lng}
                    });

                    // Add marker for current location
                    new google.maps.Marker({
                        position: {lat: lat, lng: lng},
                        map: map,
                        title: "My Current Location"
                    });

                    // Call function to get address
                    getAddress(lat, lng);
                });
            }
        }

        // Function to get the address using Google Maps Geocoding API
        function getAddress(lat, lng) {
            var geocoder = new google.maps.Geocoder;
            var latlng = {lat: parseFloat(lat), lng: parseFloat(lng)};
            geocoder.geocode({'location': latlng}, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        document.getElementById('address').textContent = "" + results[0].formatted_address + "";
                    } else {
                        document.getElementById('address').textContent = "No results found";
                    }
                } else {
                    document.getElementById('address').textContent = "Geocoder failed due to: " + status;
                }
            });
        }
    </script>
</head>
<body>
    <div><b>Location:</b></div>
    <!-- The div element for the map -->
    <div id="map"></div>
    <!-- The div element to display the address -->
    <div id="address">Fetching your address...</div>
    <div><p></p><b>Server Location:</b></div>
    <div>Latitude: {{ $details->latitude }} </div>
    <div>Longitude: {{ $details->longitude }} </div>
    <div>City: {{ $details->city }} </div>
    <div>Country: {{ $details->country }} </div>
</body>
</html>
