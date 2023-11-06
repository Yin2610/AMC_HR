<?php
class DBConnection
{
    private static $dbHost = "localhost";
    private static $dbName = "amc_hr";
    private static $dbUsername = "root";
    private static $dbPassword = "";
    
    private static $connection = null;
    
    public function __construct() {
        die('Init function is not allowed');
    }
    
    public static function connectToDB() {
        if(self::$connection == null) {
            try {
                self::$connection = new PDO("mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbPassword);
            }
            catch(PDOException $e) {
                die($e->getMessage());
            }
            return self::$connection;
        }
        
    }
    
    public static function disconnect() {
        self::$connection = null;
    }
}
?>