<?php
class Loan extends User{
    private $collection_name;
    public $loan_id;
    public $id_no;
    public $loan_date;
    public $loan_amount;
    public $loan_rqdate;
    public $loan_interest;
    public $amount_due;
    //this three here are important
    public $date_cleared;
    public $is_paid;
    public $status;
    
    public function __construct($db){
        parent::__construct($db);
        $this->collection_name="loans";
        //$this->database=$db;
        $this->is_paid=false;
        $this->loan_interest=INTEREST;
    }

    //function to sanitize text to prevent injection attacks
    public function sanitize($text){
        return htmlspecialchars(strip_tags($text));
    }
    
    //function to get the list of all loans
    public function getLoans(){
        $option=[];
        $filter=[];
        $records = $this->database->queryData($this->collection_name,$filter,$option);
        return $records;
    }
    //function to get the list of unpaid loans
    public function getUnpaidLoans(){
        $option=[];
        $filter=["is_paid"=>false];
        $records = $this->database->queryData($this->collection_name,$filter,$option);
        return $records;
    }
    //list of all unpaid loans
    public function getPaidLoans(){
        $option=[];
        $filter=["is_paid"=>true];
        $records = $this->database->queryData($this->collection_name,$filter,$option);
        return $records;
    }
    //function to get all pending loans
    public function getPendingLoans(){
        $option=[];
        $filter=["status"=>"pending"];
        $records=$this->database->queryData($this->collection_name,$filter,$options);
        return $records;
    }
    //function to pay loan
    public function payLoan($amount){
        echo "blablabla";
    }
    //function to request loan in php
    public function requestLoan($amount){
        $values=array();
        $values=[
            'account_no'=>$this->account_no,
            'loan_amount'=>$amount,
            'status'=>"pending",
            'loan_rqdate'=>time()
        ];
        if($this->database->createRecord($this->collection_name,$values)){
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
}
?>