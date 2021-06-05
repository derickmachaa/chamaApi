<?php
//include config file
include_once '../../config/config.php';

// required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');
  
//create array of users
$users=array();
$users["results"]=array();
// instantiate database and user object
$database = new Database();
$user = new User($database);
//get users
$cursor = $user->readUsers();

foreach($cursor as $row){
    $user_list=array(
        "surname"=>$row->surname,
        "email"=>$row->email,
        "id"=>$row->_id->__toString(),
        "pass"=>$row->password,
        "Tel No"=>$row->phone_number,
        "gender"=>$row->gender,
        "county"=>$row->county
    );
    array_push($users["results"], $user_list);
}
http_response_code(200);
echo json_encode($users);
?>