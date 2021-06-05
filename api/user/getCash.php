<?php
//include the main configuration
include_once '../../config/config.php';
//include the user and database objects
include(ROOT.'api/objects/database.php');
include(ROOT.'api/objects/user.php');

//include the required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate objects
$database  = new Database();
$user = new User($database);
$data = json_decode(file_get_contents("php://input"));
if(isset($data->id)){
    $user->id = $data->id;
}
//check method if is get
if($_SERVER['REQUEST_METHOD']=='POST'){
    echo json_encode(array("cash"=>$user->getCash()));
}
?>