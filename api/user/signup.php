<?php
//include config file
include_once '../../config/config.php';

//include database and object files
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');

//headers required
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate the database and user object
$database = new Database();
$user = new User($database);
//get data from post request
$data = json_decode(file_get_contents("php://input"));
//make sure data is not empty
if(
    !empty($data->id_no) && !empty($data->email) &&
    !empty($data->password) && !empty($data->first_name) &&
    !empty($data->middle_name) && !empty($data->phone_number)
){
  
    // set user property values
    $user->first_name = $data->first_name;
    $user->middle_name = $data ->middle_name;
    $user->id_no = $data->id_no;
    $user->phone_number = $data->phone_number;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->join_date = date('Y-m-d H:i:s');
  
    // create the user
    if($user->signUp()){
        // set response code - 201
        http_response_code(201);
        // tell the user
        echo json_encode(array("message"=> "User was created."));
    }
  
    // if unable to create the user, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(array("message" => "Unable to create User. Possible duplicates"));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}

?>
