<?php
//include config file
include_once '../../config/config.php';

include_once(ROOT.'api/objects/user.php');
include_once(ROOT.'api/objects/database.php');
//set the appropriate headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate the objects
$database = new Database();

$user = new User($database);

//get email token from post
$data = json_decode(file_get_contents('php://input'));
//ensure data is not empty first
if(!empty($data->email)&&!empty($data->password)&& !empty($data->token)){
    $user->email = $data->email;
    $user->password = $data->password;
    $user->token = $data->token;

    if($user->resetPassword()){
        http_response_code(201);
        echo json_encode(array("message"=>"password succesfully changed"));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message"=>"missing values"));
}
?>