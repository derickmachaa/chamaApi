<?php
//this is the global configuration files

//the root dir
define('ROOT', __DIR__ .'/../');

//database is mongo this section includes the mongo config files
define('DB_HOST','localhost'); //specify the host here
define('DB_PORT',27017);//specify the port  here
define('DB_NAME','chama');//specify the dbname here cant be blank
define('DB_USER','chama');//specify the user here leave blank if none
define('DB_PASS','chama');//specify the user password here leave blank if none
//end mongo config

//jwt config is in this section
date_default_timezone_set("Africa/Nairobi");
define('JWT_KEY','Place yours here any string'); //jwt key
define('JWT_ISSUER','Place yout issuer here'); //the token issuer
$issued_at=time();
$expiration=$issued_at+(60*60); //validity for tokens i.e valid for one hour
//end jwt config

//email configuration is in this section
define('EMAIL_HOST','smtp.gmail.com'); //email host required for sending reset token
define('EMAIL_PORT',587); //the email host port
define('EMAIL_ADDRESS','');//email address to use for sending mails
define('EMAIL_PASS',' ');//email password
define('EMAIL_NAME',''); //email name to include while sending mails
define('EMAIL_DEBUG',0);//whether debug is turned on
//end email config

//fix cross origin errors
//enter url name
define('URL','http://localhost');
//end url name

//loans configuration 
define('INTEREST',20);
define('MINIMUM_LOAN',1000);
?>
