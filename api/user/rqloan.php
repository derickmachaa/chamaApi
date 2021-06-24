<?php
//include config file
include_once '../../config/config.php';

//include objects
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');
include_once(ROOT.'api/objects/auth.php');
include_once(ROOT.'api/objects/loan.php');

// required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//instantiate objects 
$database= new Database();
$loan=new Loan($database);
$auth=new Auth();

if($_SERVER["HTTP_AUTHORIZATION"]){
    //extract the jwt from the http headers
    $autharray=explode(" ",$_SERVER['HTTP_AUTHORIZATION']);
    $jwt=$autharray[1];
    //check if the jwt is valid if valid take the data 
    $decoded=$auth->Decode($jwt);
    if($decoded["valid"]){
        //take data from php post request and retreive the amount requested
        $loanreq=json_decode(file_get_contents("php://input"));
        $amount=$loanreq->amount;
        if (is_integer($amount)) {
            //retrieve user id from jwt token
            $data=$decoded["data"]->data;
            $loan->id=$data->id;
            $loan->email=$data->email;
            //get account balance in users account
            $account_balance=$loan->getAccountBal()->account_balance;
            if($amount<MINIMUM_LOAN){
                http_response_code(404);
                echo json_encode(array("message"=>"Less than the minimum amount to borrow","minimum_amount"=>MINIMUM_LOAN));
            }elseif($amount>$account_balance){
                http_response_code(404);
                echo json_encode(array("message"=>"Greater than the amount you can borrow","maximum_amount"=>$account_balance));
            }else{
                http_response_code(201);
                $loan->setUserProfile();
                $loan->requestLoan($amount);
                //echo json_encode($loan->collection_name);
            }
        }
        else{
            http_response_code(405);
            echo json_encode(array("message"=>"amount must be integer"));
        }
    }
    else{
        http_response_code(401);
        echo json_encode(array("message"=>"Access Denied"));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message"=>"authorization required"));
}
?>