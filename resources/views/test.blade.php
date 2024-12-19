<!DOCTYPE html>
<html>
<head>
<title>Solodev's Auto-Complete Address Form</title>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<style>
	.container {
	  margin-top: 20px;
	}
	</style>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript" ></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript" ></script>
	<script src="auto-complete.js" type="text/javascript" ></script>
	
</head>
<body>

<div class="container">
<input id="autocomplete" placeholder="Enter your address" type="text"></input>
</div>  
<script>
    var placeSearch, autocomplete;

function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
    /** @type {HTMLInputElement} */
    (document.getElementById('autocomplete')), {
        types: ['geocode']
    });

    google.maps.event.addDomListener(document.getElementById('autocomplete'), 'focus', geolocate);
}

function geolocate() {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {

            var geolocation = new google.maps.LatLng(
            position.coords.latitude, position.coords.longitude);
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());

            // Log autocomplete bounds here
            console.log(autocomplete.getBounds());
        });
    }
}

initialize();
</script>
		
</body>
</html>