<?php
//include php jwt library
include_once(ROOT.'libs/php-jwt/src/BeforeValidException.php');
include_once(ROOT.'libs/php-jwt/src/ExpiredException.php');
include_once(ROOT.'libs/php-jwt/src/SignatureInvalidException.php');
include_once(ROOT.'libs/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

//handle encoding and decoding of jwt tokens to prevent rewriting same code in multiple area
class Auth{
    
    //function to encode the jwt
    public function Encode($data){
        $jwt=JWT::encode($data,JWT_KEY);
        return $jwt;
    }
    //function to decode data from jwt
    public function Decode($jwt){
        try{
                $decoded = JWT::decode($jwt, JWT_KEY,array('HS256'));
                //if successul return the data decoded
                return array("valid"=>true,"data"=>$decoded);
            }
            catch(Exception $e){
                //else flag an error
                return array("valid"=>false,"data"=>$e->getMessage());
            }
    }

}
?>