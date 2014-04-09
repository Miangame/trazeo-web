/* Leaflet configuration*/
/*
/*
var map = L.map('map').setView([37.8938548, -4.788015299999984], 12);

L.tileLayer('http://{s}.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/997/256/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
    maxZoom: 18
}).addTo(map);

var baseUrl = '/template-openmap-bundle/Resources/public/css';
var greenIcon = L.icon({
    iconUrl: baseUrl + '/images/marker-icon-start.png',
    shadowUrl: baseUrl + '/images/marker-shadow.png',

});

var marker = L.marker([ 37.8938548, -4.788015299999984 ],{icon: greenIcon}).addTo(map);
marker.bindPopup("<b>Comienzo</b><br>Punto de partida.").openPopup();*/

var gk, topo, thunderforest, osm, waymarkedtrails;

L.Icon.Default.imagePath = '/template-openmap-bundle/Resources/public/css/images';  
//L.Icon.Default.imagePath = '/trazeo-web/src/Sopinet/OpenMapBundle/Resources/public/css/images';  

    gk = 'http://opencache.statkart.no/gatekeeper/gk';

    osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: 'tiles &copy; <a target="_blank" href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      styleId: 22677
    });

    var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/{styleId}/256/{z}/{x}/{y}.png',
    cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade';
    var midnight   = L.tileLayer(cloudmadeUrl, {styleId: 999, attribution: cloudmadeAttribution}),
    motorways = L.tileLayer(cloudmadeUrl, {styleId: 46561, attribution: cloudmadeAttribution});
    
    waymarkedtrails = L.tileLayer('http://tile.waymarkedtrails.org/hiking/{z}/{x}/{y}.png', {
      maxZoom: 19,
      opacity: 0.5,
      attribution: 'overlay &copy; <a target="_blank" href="http://hiking.waymarkedtrails.org">Waymarked Trails</a> '
              + '(<a target="_blank" href="http://creativecommons.org/licenses/by-sa/3.0/de/deed.en">CC-BY-SA 3.0 DE</a>)'
    });

    map = new L.Map('mapa', {
      layers: [osm]
      ,center: new L.LatLng(37.8938548, -4.788015299999984)
      ,zoom: 16
    });

    L.control.layers({
        'Vista ciudad': osm,
        'Vista nocturna': midnight,
    }, {
        'Señalizar senderos': waymarkedtrails,
        'Señalizar autopistas': motorways
    }).addTo(map);

    //map.attributionControl.addAttribution('&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors');

    router = function(m1, m2, cb) {
      var proxy = 'http://www2.turistforeningen.no/routing.php?url=';
      var route = 'http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&v=foot&fast=1&layer=mapnik';
      var params = '&flat=' + m1.lat + '&flon=' + m1.lng + '&tlat=' + m2.lat + '&tlon=' + m2.lng;
      $.getJSON(proxy + route + params, function(geojson, status) {
        if (!geojson || !geojson.coordinates || !geojson.coordinates.length === 0) {
          if (typeof console.error === 'function') {
            console.error('OSM router failed', geojson);
          }
          return cb(new Error());
        }
        return cb(null, L.GeoJSON.geometryToLayer(geojson));
      });
    }
    routing = new L.Routing({
      position: 'topleft'
      ,routing: {
        router: router
      }
      ,snapping: {
        layers: []
      }
    });
    map.addControl(routing);
    routing.draw()
