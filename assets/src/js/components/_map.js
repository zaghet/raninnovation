class Map {
  constructor() {
    this.map = document.getElementById("map");

    this.markers = document.querySelectorAll("#markers .marker");

    if (this.map) {
      this.init();
    }
  }

  init() {

    var defaultLon = 8.92934;
    var defaultLat = 44.41264;
    var latlng = L.latLng(defaultLat, defaultLon);
    var map = L.map("map").setView([defaultLon, defaultLat], 13);
    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 19,
    }).addTo(map);
    this.markers.forEach((marker) => {
      var myIcon = L.icon({
        iconUrl: window.siteVars.template_path + "/static/img/marker.png",
        iconSize: [19, 27],
        iconAnchor: [19, 27],
        popupAnchor: [-9, -23],
      });
      var _ll = L.latLng(marker.dataset.lat, marker.dataset.lon);
      latlng = _ll;
      var _m = L.marker(_ll, { icon: myIcon }).addTo(map);
      var _d = marker.dataset.desc;
      _m.bindPopup(`${_d}`).openPopup();
    });

    
    map.panTo(latlng);
  }
}

export default Map;