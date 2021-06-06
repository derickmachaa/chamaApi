<?php
include_once('../../config/config.php');

// required headers
header("Access-Control-Allow-Origin: ".URL);
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once(ROOT.'api/objects/database.php');
include_once(ROOT.'api/objects/user.php');
include_once(ROOT.'api/objects/loan.php');
include_once(ROOT.'api/objects/auth.php');
//create an array of loans
$loans=array();
$loans['result']=array();
//instantiate objects
$database=new Database();
$loan=new Loan($database);
$auth=new Auth();

//check for authorization
if(isset($_SERVER['HTTP_AUTHORIZATION'])){
    $autharray = explode(" ",$_SERVER['HTTP_AUTHORIZATION']);
    $jwt=$autharray[1];
    if($auth->checkAdmin($jwt)){
        //get the loans
        $cursor=$loan->getLoans();
        foreach ($cursor as $row) {
            $loan_list=array(
                "id"=>$row->_id->__toSting(),
                "acc_no" => $row->acc_no,
                "acc_name" => $row->acc_name,
                "id_no" => $row->id_no,
                "load_date" => $row->loan_date,
                "loan_amount" => $row->loan_amount,
                "loan_interest" => $row->loan_interest,
                "amount_due" => $row->amount_due,
                "date_cleared" => $row->date_cleared,
                "is_paid" => $row->is_paid
                );
        array_push($loans['results'],$loan_list);
        }
        http_response_code(200);
        echo json_encode($loans);
    }
    else{
        http_response_code(401);
        echo json_encode(array("message"=>"Access Denied"));
    }
    
}
else{
    http_response_code(400);
    echo json_encode(array("message"=>"Authentication required"));
}

?>