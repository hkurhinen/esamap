<?php
session_start();
?>
<!DOCTYPE html>
<html ng-app="esMap" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MikkeliES - Map</title>

    <link rel="stylesheet" href="assets/bootstrap-3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome-4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/leaflet-0.7.2/leaflet.css">
    <link rel="stylesheet" href="assets/leaflet-sidebar-0.1.5/L.Control.Sidebar.css">
    <link rel="stylesheet" href="assets/css/base.css">
	<link rel="stylesheet" href="assets/css/app.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">
		<?php
			if(isset($_SESSION['loggedin'])){
				echo 'ADMIN MODE';
			}else{
				echo '<img class="headerLogo" src="assets/img/es_badge.png" />';
			}
		?>
		</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="$('#aboutModal').modal('show'); return false;"><i class="fa fa-question-circle" style="color: white"></i>&nbsp;&nbsp;Lisätietoa</a></li>
          <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="sidebar.toggle(); return false;"><i class="fa fa-list" style="color: white"></i>&nbsp;&nbsp;Näytä/piilota sivupalkki</a></li>
		  <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="$('#addPoiModal').modal('show'); return false;"><i class="fa fa-plus-circle" style="color: white"></i>&nbsp;&nbsp;Lisää uusi...</a></li>
        </ul>
      </div><!--/.navbar-collapse -->
    </div>

    <div id="map"></div>
    <div ng-controller="SidebarController as categoryCtrl" id="sidebar">
		<ul class="list-group">
			<li class="list-group-item place-category" ng-repeat="item in categoryCtrl.items" ng-controller="DescriptionController as desc">
				<span class="badge">{{item.places.length}}</span>
				<h4 ng-click="desc.toggle()" ><img ng-src="{{item.icon}}" />&nbsp;&nbsp;{{item.name}}</h4>
				<div class="list-group" ng-show="desc.isVisible()">
				<?php if(isset($_SESSION['loggedin'])){ ?>
				<span ng-repeat="place in item.places">
					<a ng-click="desc.itemClick(place._id.$id)" href="#" class="list-group-item" >{{place.name}}</a><i class="fa fa-trash-o"></i>
				</span>
				<?php } else { ?>
					<a ng-click="desc.itemClick(place._id.$id)" href="#" class="list-group-item" ng-repeat="place in item.places">{{place.name}}</a>
				<?php
					}
				?>
				</div>
			</li> 
		</ul>
    </div>

	<div class="modal fade" id="aboutModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Lisätietoa</h4>
				</div>
				<div class="modal-body">
					<p>Kartalla näkyy kiinnostavat kohteet liittyen Mikkelin startup-toimintaan.</p>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="addPoiModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Lisää kohde...</h4>
				</div>
				<div class="modal-body">
					<form class="addPoiForm" role="form" ng-controller="FormController as formCtrl" ng-submit="formCtrl.addPoi()">
						<input ng-model="formCtrl.newPoi.lat" id="newLat" type="hidden" name="lat" value="" >
						<input ng-model="formCtrl.newPoi.lng" id="newLng" type="hidden" name="lng" value="" >

						<div class="form-group">
							<label for="newPoiType">Kohteen tyyppi:</label>
							<select ng-model="formCtrl.newPoi.category" ng-controller="SidebarController as types" class="form-control" id="newPoiType">
								<option value="">Valitse...</option>
								<option ng-repeat="type in types.items" value="{{type.id}}">{{type.name}}</option>
							</select>
						</div>
						<div class="form-group">
							<label for="newPoiName">Kohteen nimi:</label>
							<input ng-model="formCtrl.newPoi.name" type="text" class="form-control" id="newPoiName" placeholder="Kohteen nimi">
						</div>
						<div class="form-group">
							<label for="newPoiDesc">Lyhyt kuvaus:</label>
							<textarea ng-model="formCtrl.newPoi.desc" class="form-control" rows="3" id="newPoiDesc" placeholder="Lyhyt kuvaus kohteen toiminnasta"></textarea>
						</div>
						<div class="form-group">
							<label for="newPoiAddress">Osoite:</label>
							<input ng-model="formCtrl.newPoi.address" type="text" class="form-control" id="newPoiAddress" placeholder="kohteen osoite">
							<img src="assets/img/powered-by-google-on-white.png" />
						</div>
						<button type="submit" class="btn btn-default">Lähetä</button>
					</form>

				</div>
			</div>
		</div>
	</div>
	
	<script src="assets/angularjs/angular.min.js"></script>
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
	<script src="assets/js/jquery.geocomplete.min.js"></script>
    <script src="assets/bootstrap-3.1.1/js/bootstrap.min.js"></script>
    <script src="assets/leaflet-0.7.2/leaflet.js"></script>
    <script src="assets/leaflet-sidebar-0.1.5/L.Control.Sidebar.js"></script>
    <script src="assets/js/base.js"></script>
	<script src="assets/js/app.js"></script>
  </body>
</html>
