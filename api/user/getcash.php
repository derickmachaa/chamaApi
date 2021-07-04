<?php
//include the main configuration
include_once '../../config/config.php';
//include the user and database objects
include(ROOT.'api/objects/database.php');
include(ROOT.'api/objects/user.php');
include(ROOT.'api/objects/accounts.php');
include(ROOT.'api/objects/auth.php');

//include the required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate objects
$database  = new Database();
$user = new Loan($database);

$auth = new Auth();
if($_SERVER["HTTP_AUTHORIZATION"]){
    $autharray=explode(" ",$_SERVER["HTTP_AUTHORIZATION"]);
    $jwt=$autharray[1];
    $decoded=$auth->Decode($jwt);
    if($decoded['valid']){
        $data=$decoded['data']->data;
        $user->email=$data->email;
        $user->setUserProfile();
        $user->setAccountNo();
        echo json_encode($user->getAccountBal());

    }else{
        http_response_code(401);
        echo json_encode(array("message"=>"access denied"));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message"=>"authorization required"));
}
?>