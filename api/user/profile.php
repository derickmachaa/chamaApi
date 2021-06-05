<?php
//include config file
include_once '../../config/config.php';

// include database and object files
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');
include_once(ROOT.'api/objects/auth.php');

//headers required
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate the objects
$database = new Database();
$user = new User($database);
$auth =  new Auth($database);

//get data from put request
if($_SERVER['REQUEST_METHOD']=="GET"){
    //check if http authorization header has been set or not
    if(!$_SERVER['HTTP_AUTHORIZATION']){
        //if no authorization header respond with 401
        http_response_code(401);
        echo json_encode(array("message"=>"Access Denied"));
    }
    else
    {
        //check if the token is valid
        $autharray = explode(" ",$_SERVER['HTTP_AUTHORIZATION']);
        $jwt=$autharray[1];
        if($jwt)
        {
            $decoded=$auth->Decode($jwt);
            if($decoded['valid']){
                $user->id=$decoded['data']->data->id;
                $user_data=$user->getUserByID();
                echo json_encode($user_data);
            }
            else
            {
                http_response_code(401);
                echo json_encode($decoded['data']);
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array("message"=>"could not find jwt"));
        }
    }
}
elseif($_SERVER['REQUEST_METHOD']=="PUT"){
    if(!$_SERVER['HTTP_AUTHORIZATION']){
        //if no authorization header respond with 401
        http_response_code(401);
        echo json_encode(array("message"=>"Access Denied"));
    }else
    {
        //try to decode the jwt
        $autharray = explode(" ",$_SERVER['HTTP_AUTHORIZATION']);
        $jwt=$autharray[1];
        if($jwt){
            $decoded = $auth->Decode($jwt);
            if($decoded['valid']){
                $data = json_decode(file_get_contents("php://input"));
                $user->email = $decoded['data']->data->email;
                $user->setUserProfile();
                if(isset($data->surname)){$user->surname = $data->surname;}
                if(isset($data->first_name)){$user->first_name = $data->first_name;}
                if(isset($data->middle_name)){$user->middle_name = $data ->middle_name;}
                if(isset($data->gender)){$user->gender = $data ->gender;}
                if(isset($data->DOB)){$user->DOB = $data->DOB;}
                if(isset($data->id_no)){$user->id_no = $data->id_no;}
                if(isset($data->email)){$user->email = $data->email;}
                if(isset($data->password)){$user->password = $data->password;}
                if(isset($data->marital_status)){$user->marital_status = $data->marital_status;}
                if(isset($data->phone_number)){$user->phone_number = $data->phone_number;}
                if(isset($data->county)){$user->county = $data->county;}
                if(isset($data->sub_county)){$user->sub_county = $data->sub_county;}
                if(isset($data->ward)){$user->ward = $data->ward;}
                if(isset($data->location)){$user->location = $data->location;}
                if(isset($data->profile_pic)){$user->profile_pic = $data->profile_pic;}
                //update with the values
                if($user->UpdateUserProfile()){
                    http_response_code(201);
                    echo json_encode(array("message"=>"successfully updated"));
                }
            }
            else{
                http_response_code(401);
                echo json_encode($decoded['data']);
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array("message"=>"could not find jwt"));
        }
    }
    
}
else
{
    http_response_code(405);
    echo json_encode(array("message"=>"method not allowed"));
}

?>
