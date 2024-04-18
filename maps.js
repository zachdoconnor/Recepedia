var map, service;
function initMap() {
  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 14
      });
      var request = {
        location: pos,
        radius: '5000',
        query: 'grocery store'
      };
      service = new google.maps.places.PlacesService(map);
      service.textSearch(request, callback);
    }, function() {
      handleLocationError(true, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, map.getCenter());
  }
}

function callback(results, status) {
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      createMarker(results[i]);
    }
  }
}

function createMarker(place) {
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location
  });

  var infowindow = new google.maps.InfoWindow();
  var content = '<div><strong>' + place.name + '</strong><br>' +
                'Address: ' + place.formatted_address + '<br>' +
                '<a href="https://www.google.com/maps/dir/?api=1&destination=' + place.formatted_address + '">Directions</a></div>';
  infowindow.setContent(content);

  marker.addListener('click', function() {
    infowindow.open(map, marker);
  });
}

function handleLocationError(browserHasGeolocation, pos) {
  map.setCenter(pos);
  map.setZoom(15);
  var infoWindow = new google.maps.InfoWindow({map: map});
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
}
