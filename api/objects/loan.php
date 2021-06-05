<?php
class Loan{
    private $collection_name;
    public $id;
    public $acc_no;
    public $acc_name;
    public $id_no;
    public $loan_date;
    public $loan_amount;
    public $loan_interest;
    public $amount_due;
    //this two
    public $date_cleared;
    public $is_paid;
    
    public function __construct($db){
        $this->collection_name="loans";
        $this->database=$db;
        $this->is_paid=false;
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
}
?>