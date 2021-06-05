<?php
//include config file
include_once '../../config/config.php';


// include database and object files
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');

// required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instanciate the database
$database = new Database();

$user = new User($database);

//get data from post request
$data=json_decode(file_get_contents("php://input"));
//ensure data has an email address
if(!empty($data->id)){
    $user->id = $data->id;
    if($user->deleteUser()){
        http_response_code(201);
        echo json_encode(array("message"=>"successfully deleted"));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message"=>"please specify id"));
}
?>