<?php

class Database {
    static protected $conn;
    // Database connection 
    public function __construct($server, $db_name, $db_user, $db_pwd) {

        $this->server = $server;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pwd = $db_pwd;
    
        if(!isset($this->db)){
            // Connect to the database
            try{
                $connection = new PDO("mysql:host={$this->server};port=3306;
                                       dbname={$this->db_name}", 
                                       "{$this->db_user}", "{$this->db_pwd}");
                $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "<p style='color:green'>connected to database<p>";
                self::$conn = $connection;
            }
            catch(PDOException $e){
                die("Failed to connect with MySQL: " . $e->getMessage());
            }
        }
    }

    public static function connection() {
        return self::$conn;
    }
    
} // end
?>