var map;
var markerMap = {};
var CustomIcon = L.Icon.Default.extend({
	options: {
			iconUrl: '',
			iconSize:[31, 42]
	}
});

function sidebarClick(id) {
	if (document.body.clientWidth <= 767) {
		sidebar.hide();
	}
	var marker = markerMap[id];
	map.panTo(marker.getLatLng());
	marker.openPopup();
}

function removeItem(id){
	
}

function addPlaceToMap(place, icon){
	 var customIcon = new CustomIcon({iconUrl: icon});
	 var location = new L.LatLng(place.lat, place.lng);
	 var marker = L.marker(location, {icon: customIcon}).addTo(map);
	 marker.bindPopup("<b>"+place.name+"</b><p>kuvaus:<br/>"+place.description+"</p><span class='popup-address'><b>Osoite:</b><br/>"+place.address+"</span>");
	 markerMap[place._id.$id] = marker;
}

/* Basemap Layers */
var mapquestOSM = L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png", {
  maxZoom: 19,
  subdomains: ["otile1", "otile2", "otile3", "otile4"],
  attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA.'
});

map = L.map("map", {
  zoom: 14,
  center: [61.687761, 27.273157],
  layers: [mapquestOSM],
  zoomControl: true
});

var baseLayers = {
  "Street Map": mapquestOSM
};

var sidebar = L.control.sidebar("sidebar", {
  closeButton: true,
  position: "left"
}).addTo(map);

if (document.body.clientWidth >= 767) {
	sidebar.show();
}

$("#newPoiAddress").geocomplete({
  details: ".addPoiForm",
  types: ["geocode", "establishment"]
});


/* Placeholder hack for IE */
if (navigator.appName == "Microsoft Internet Explorer") {
  $("input").each(function () {
	if ($(this).val() === "" && $(this).attr("placeholder") !== "") {
	  $(this).val($(this).attr("placeholder"));
	  $(this).focus(function () {
		if ($(this).val() === $(this).attr("placeholder")) $(this).val("");
	  });
	  $(this).blur(function () {
		if ($(this).val() === "") $(this).val($(this).attr("placeholder"));
	  });
	}
  });
}

