<?php

$m = new MongoClient("mongodb://192.168.11.30");
$db = $m->mikkelies;
$collection = $db->poi;

if(isset($_GET['getdata'])){
	$cursor = $collection->find();
	$arr = array();
	foreach($cursor as $c) {
        array_push($arr, $c);
    }
	echo json_encode($arr);
}else if(isset($_GET['save'])){
	$data = json_decode(file_get_contents("php://input"));
	$name = $data->name;
	$address = $data->address;
	$lat = $data->lat;
	$lng = $data->lng;
	$category = $data->category;
	$description = $data->description;
	
	$document = array( "name" => $name, "address" => $address, "lat" => $lat, "lng" => $lng,  "category" => $category, "description" => $description);
	$collection->insert($document);
	echo "done";
}
?>
