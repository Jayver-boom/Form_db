<?php
    //meta data
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow0-Methods: POST, GET, PATCH, DELETE, OPTIONS");
    header("Access-Control-Max-Age: 3600");
    //header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");
    date_default_timezone_set("Asia/Manila");

    define("SERVER", "localhost");
    define("DBASE", "forum_db"); //enter your own database name
    define("USER", "root");
    define("PWORD", "");
    define("TOKEN_KEY", "E5A2755C54DC682B7ABE133A7E9E9");
    define("SECRET_KEY", "MySecretKey");

    class Connection {
        protected $connectionString = "mysql:host=" . SERVER . ";dbname=" .DBASE. ";charset=utf8";
        protected $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, 
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false

        ];

        public function connect(){
            return new \PDO($this->connectionString, USER, PWORD, $this->options);
        }
    }
    
?>