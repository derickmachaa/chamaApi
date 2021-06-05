<?php
//include config file
include_once '../../config/config.php';

//include php jwt library
include_once(ROOT.'libs/php-jwt/src/BeforeValidException.php');
include_once(ROOT.'libs/php-jwt/src/ExpiredException.php');
include_once(ROOT.'libs/php-jwt/src/SignatureInvalidException.php');
include_once(ROOT.'libs/php-jwt/src/JWT.php');

//include objects
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');
use \Firebase\JWT\JWT;

// required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate objects
$database = new Database();
$user = new User($database);

//get posted data
$data = json_decode(file_get_contents("php://input"));
$user->email = $data->email;

//check if login is valid and return jwt token
if($user->checkLogin()&&password_verify($data->password,$user->password)){
    //if true set http code to 200 and respond with jwt web token
    http_response_code(200);
    $token = array(
       "iat" => $issued_at,
       "exp" => $expiration,
       "nbf" => $issued_at,
       "iss" => JWT_ISSUER,
       "data" => array(
           "id" => $user->id->__toString(),
           "isadmin" => $user->isadmin
       )
    );
    $jwt=JWT::encode($token,JWT_KEY);
    echo json_encode(
        array(
            "message"=>"authentication succesful",
            "jwt"=>$jwt
            )
        );

}
else{
    //else set http code to 401 and respond with forbidden
    http_response_code(401);
    echo json_encode(array("message"=>"Authentication failed"));
}
?>
