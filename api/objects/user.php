<?php
//this file is to handle all user operations in this file
class User{
  
    // database connection and table name
    public $database;
    private $collection_name;
  
    // object properties
    public $id;
    public $surname;
    public $first_name;
    public $middle_name;
    public $gender;
    public $DOB;
    public $id_no;
    public $email;
    public $password;
    public $password_hash;
    public $marital_status;
    public $phone_number;
    public $join_date;
    public $county;
    public $sub_county;
    public $ward;
    public $location;
    public $profile_pic;
    public $is_admin;
    public $token;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->database = $db;
        $this->collection_name="users";
        $this->is_admin = false;
    }
    //function to sanitize data to prevent unwanted injections
    public function sanitize($text){
        return htmlspecialchars(strip_tags($text));
    }

    public function readUsers(){
        // read all user records
        $filter = [];
        $option = [];
        $records = $this->database->queryData($this->collection_name,$filter,$option);
        return $records;
    }

    public function getUserByEmail(){
        //check if a user exists through an email and returns all user fields if user exists, or false otherwise
        $this->email=$this->sanitize($this->email);
        $filter = ['email'=>$this->email];
        $option = [];
        $all_users=$this->database->queryData($this->collection_name,$filter,$option);
        if(count($all_users)==1){
            return $all_users;
        }else{
            return FALSE;
        }
    }
    public function getUserById(){
        //this function gets a user by id and returns all the values
        $filter=["_id"=>new MongoDB\BSON\ObjectId($this->sanitize($this->id))];
        $option = [];
        $values=$this->database->queryData($this->collection_name,$filter,$option);
        if($values){
            return $values;
        }
        else{
            return false;
        }
    }
    public function checkLogin(){
        //set the password from the database that a user requires to login and return true if user exists
        $filter=['email'=>$this->sanitize($this->email)];
        $option=[
            'projection'=>[
                '_id'=>1,
                'password'=>2,
                'is_admin'=>3
            ]
            ];
        $found=$this->database->queryData($this->collection_name,$filter,$option);
        if($found){
            $data=get_object_vars($found[0]);
            $this->id=$data['_id'];
            $this->password=$data['password'];
            $this->is_admin=$data['is_admin'];
            return TRUE;

        }
        else
        {
            return FALSE;
        }
    }
    
    public function signUp(){
        //function to create a user in the database
        //hash password with bcrypt before storing
        $this->password_hash=password_hash($this->sanitize($this->password), PASSWORD_BCRYPT);
        $values = array();
        $values=[
            'first_name' =>$this->sanitize($this->first_name),
            'middle_name' =>$this->sanitize($this->middle_name),
            'id_no' =>$this->sanitize($this->id_no),
            'phone_number'=>$this->sanitize($this->phone_number),
            'email'=>$this->sanitize($this->email),
            'password'=>$this->password_hash,
            'join_date'=>$this->sanitize($this->join_date),
            'is_admin'=>$this->is_admin
        ];
        if($this->database->createRecord($this->collection_name,$values)){
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


    public function deleteUser(){
        //sanitize id input from user
        $this->id=$this->sanitize($this->id);
        $filter = ['_id'=>new MongoDB\BSON\ObjectId($this->id)];
        $option = ['limit'=>1];
        if($this->database->deleteRecord($this->collection_name,$filter,$option)){
            return true;
        }else{
            return false;
        }
    }

    public function setToken(){
        //this function sets a token for password recorvery
        $this->email = $this->sanitize($this->email);
        $filter = ['email'=>$this->email];
        $option = ['$set'=>['token' =>$this->token]];
        if($this->database->updateOne($this->collection_name,$filter,$option))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function resetPassword(){
        ///this function changes a user password
        //check if user exists in db through the email and if token is valid and does the change
        $data = $this->getUserByEmail();
        if($data){
            foreach($data as $row){
                $true_token=$row->token;
                $this->id = $row->_id;
            }
            //check if token matches in DB
            if($this->token === $true_token ){
                $filter = ['_id'=>new MongoDB\BSON\ObjectID($this->id)];
                //hash password for storage
                $this->password_hash=password_hash($this->sanitize($this->password), PASSWORD_BCRYPT);
                $this->token="";
                $option = ['$set'=>['password'=>$this->password_hash,'token'=>$this->token]];
                if($this->database->updateOne($this->collection_name,$filter,$option))
                {
                    return true;
                }
                else{
                    return false;
                }
            }
        }

    }
    public function setUserProfile(){
        //this function when called sets the users field in the db
        $data = $this->getUserByEmail();
        if($data){
            foreach($data as $row){
                $this->id = $row->_id;
                $this->surname = $row->surname;
                $this->first_name  = $row->first_name;
                $this->middle_name  = $row->middle_name;
                $this->gender  = $row->gender;
                $this->DOB  = $row->DOB;
                $this->id_no  = $row->id_no;
                $this->email = $row->email;
                $this->password_hash = $row->password;
                $this->marital_status = $row->marital_status;
                $this->phone_number = $row->phone_number;
                $this->join_date = $row->join_date;
                $this->county = $row->county;
                $this->sub_county = $row->sub_county;
                $this->ward = $row->ward;
                $this->location = $row->location;
                $this->profile_pic = $row->profile_pic;
            }
        }
    }
    public function UpdateUserProfile(){
        $this->email = $this->sanitize($this->email);
        $filter = ['email'=>$this->email];
        if(!empty($this->password)){
            $this->password_hash=password_hash($this->sanitize($this->password), PASSWORD_BCRYPT);
        }
        $values = array();
        $values=['$set'=>[
            'surname' =>$this->sanitize($this->surname),
            'first_name' =>$this->sanitize($this->first_name),
            'middle_name' =>$this->sanitize($this->middle_name),
            'gender' =>$this->sanitize($this->gender),
            'DOB' =>$this->sanitize($this->DOB),
            'id_no' =>$this->sanitize($this->id_no),
            'email'=>$this->sanitize($this->email),
            'password'=>$this->password_hash,
            'marital_status'=>$this->sanitize($this->marital_status),
            'phone_number'=>$this->sanitize($this->phone_number),
            'join_date'=>$this->sanitize($this->join_date),
            'county'=>$this->sanitize($this->county),
            'sub_county'=>$this->sanitize($this->sub_county),
            'ward'=>$this->sanitize($this->ward),
            'location'=>$this->sanitize($this->location),
            'profile_pic'=>$this->sanitize($this->profile_pic),
            'is_admin'=>$this->is_admin
            
        ]];
         if($this->database->updateOne($this->collection_name,$filter,$values))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    
}
?>
