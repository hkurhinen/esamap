(function(){
	var app = angular.module('esMap', []);
	
	app.controller('SidebarController',['$http', function($http){
		var sbitems = this;
		sbitems.items = categories;
		$http.get('php/activity.php?getdata=1').success(function(data){
			for(var i = 0; i < data.length;i++){
				var place = data[i];
				for(var j = 0; j < sbitems.items.length;j++){
					if(sbitems.items[j].id == place.category){
						var contains = false;
						for(var n = 0; n < sbitems.items[j].places.length;n++){ //TODO: figure out why controller is called twice...
							if(sbitems.items[j].places[n]['_id']['$id'] == place._id.$id){
								contains = true;
							}
						}
						if(!contains){
							sbitems.items[j].places.push(place);
							addPlaceToMap(place, sbitems.items[j].icon);
						}
					}
				}
			}
		});
	}]);

	app.controller('FormController',['$http', function($http){
		this.newPoi = {};
		this.addPoi = function(){
			this.newPoi.address = $("#newPoiAddress").val(); //TODO: fix these dirty and ugly hacks
			this.newPoi.lat = $("#newLat").val();
			this.newPoi.lng = $("#newLng").val();
			$http.post('php/activity.php?save=1', {'name': this.newPoi.name, 'address': this.newPoi.address, 'lat': this.newPoi.lat, 'lng': this.newPoi.lng, 'category': this.newPoi.category, 'description': this.newPoi.desc}).success(function(data){
			 location.reload();
			});
			this.newPoi = {};
		}
	}]);
	
	app.controller('DescriptionController', function(){
		this.visible = false;
		this.isVisible = function(){
			return this.visible;
		}
		this.toggle = function(){
			this.visible = (this.visible) ? false : true;
		}
		this.itemClick = function(id){
			sidebarClick(id);
		}
	});
	
	var categories = [
		{
			id : 0,
			icon : "assets/img/mapIcons/startup.png",
			name : "Startupit",
			places : []
		},
		{
			id : 1,
			icon : "assets/img/mapIcons/partners.png",
			name : "Yhteistyökumppanit",
			places : []
		},
		{
			id : 2,
			icon : "assets/img/mapIcons/campus.png",
			name : "Kampukset",
			places : []
		},
		{
			id : 3,
			icon : "assets/img/mapIcons/service.png",
			name : "Tukipisteet",
			places : []
		},
		{
			id : 4,
			icon : "assets/img/mapIcons/investor.png",
			name : "Rahoittajat",
			places : []
		},
		{
			id : 5,
			icon : "assets/img/mapIcons/space.png",
			name : "Toimitilat",
			places : []
		}
	];
	
})();
