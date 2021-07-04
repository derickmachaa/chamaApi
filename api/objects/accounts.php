<?php
//handle all account transactions in this file
class Accounts extends User{
    public $amount;
    public $date;
    public $id_no;
    public $account_no;
    public $account_balance;
    private $collection_name;
    
    public function __construct($db){
        parent::__construct($db);
        //set the interest
        $this->loan_interest=INTEREST;
        //set the loan table in db
        $this->collection_name="account";
    }
    ///method to set the user account number
    public function setAccountNo(){
        $filter=["id_no"=>$this->id_no];
        $option=[
            'projection'=>["account_no"=>2,"_id"=>0]
        ];
        $values=$this->database->queryData($this->collection_name,$filter,$option);
        $this->account_no=$values[0]->account_no;
    }

    ///method to return the balance of a user
    public function getAccountBal(){
        $filter=["account_no"=>$this->account_no];
        $option=[
            'projection'=>["account_balance"=>2,"_id"=>0]
        ];
        $values=$this->database->queryData($this->collection_name,$filter,$option);
        return $values[0];

    }
}
//end of class accounts

//begin of class loan to handle loan transactions
class Loan extends Accounts{
    public $loan_id;
    public $loan_date;
    public $loan_amount;
    public $loan_rqdate;
    public $date_cleared;
    public $is_paid;
    public $status;
    private $collection_name;
    //function to get the list of all loans
    public function __construct($db){
        parent::__construct($db);
        $this->collection_name="loans";
    }
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
//end of class loan
?>