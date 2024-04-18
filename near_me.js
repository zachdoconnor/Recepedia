function callback(results, status) {
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    var closestStores = []; // empty array to store results
    for (var i = 0; i < results.length; i++) {
      if (i < 5) { // limit to three closest stores
        closestStores.push(results[i]);
        createMarker(results[i]);
      }
    }

    // display closest stores in near_me div
    var nearMeDiv = document.querySelector('.near_me');
    for (var i = 0; i < closestStores.length; i++) {
      var storeDiv = document.createElement('div');
      storeDiv.textContent = closestStores[i].name + ': ' + closestStores[i].formatted_address;
      nearMeDiv.appendChild(storeDiv);
    }
  }
}
