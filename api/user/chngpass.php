<?php
//include the main config file
include_once '../../config/config.php';

//include the database and user
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');

//headers required
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate objects
$database = new Database();
$user = new User($database);

//check if method is post
if ($_SERVER['REQUEST_METHOD']=='POST') {
    //check if auth header there
    $authheader = $_SERVER['HTTP_AUTHORIZATION'];
    if($authheader){
        $jwt = explode(" ",$authheader)[1];
        //try evaluating the jwt
        try {
            $decoded = JWT::decode($jwt,JWT_KEY,array('HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(array("Error"=>"Access Denied"));
        }
    }else {
        http_response_code(401);
        echo json_encode(array("Error"=>"Access Denied"));
    }
    $data=json_decode(file_get_contents('php://input'));
    
}
else {
    //warn method not allowed
    http_response_code(400);
    echo json_encode(array("error"=>"method not allowed"));
}
?>