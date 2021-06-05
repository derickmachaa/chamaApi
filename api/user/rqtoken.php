<?php
//include config file
include_once '../../config/config.php';

include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');
//the phpmailer to help send emails
require(ROOT.'PHPMAILER/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//set the appropriate headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate objects
$database = new Database();

$user = new User($database);
//get email
$data = json_decode(file_get_contents("php://input"));
$user->email = $data->email;
$valid = $user->getUserByEmail();
if($valid){
    foreach($valid as $row){
        $to=$row->email;
    }
    //generate token
    $token=rand(100,999).'-'.rand(100,999);
    //store token in DB
    $user->token = $token;
    $user->setToken();
    //setup mail for sending token
    $mail = new PHPMailer();
    $mail->CharSet= "utf-8";
    $mail->IsSMTP();
    $mail->SMTPDebug = EMAIL_DEBUG ;
    $mail->Username = EMAIL_ADDRESS ;
    $mail->Password = EMAIL_PASS ;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure="tls";
    $mail->Host = EMAIL_HOST;
    $mail->Port = EMAIL_PORT;
    $mail->From = EMAIL_ADDRESS;
    $mail->FromName = EMAIL_NAME;
    $mail->Subject = "Reset Password";
    $mail->AddAddress($to);
    $mail->Body = "Your Password reset Token is: $token
    ingore if you did not request for a password reset";
    if($mail->Send()){
        http_response_code(201);
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("message"=>"$mail->ErrorInfo"));
    }
}
else{
    http_response_code(200);
}
?>