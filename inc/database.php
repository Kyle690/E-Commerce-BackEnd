<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/30
 * Time: 10:17 PM
 */
include "config.php";

class Database {

    protected static $connection;


    protected function connect(){
        if(!isset(self::$connection)){
            self::$connection = new mysqli(SERVER_NAME,USERNAME,PASSWORD,DATABASE);
        }
        if(self::$connection===false){
            return false;
        }
        return self::$connection;
    }

    protected function query($query){
        $connection = $this->connect();
        $result = $connection->query($query);
        return $result;
    }
    public function select($query){
        $rows = array();
        $result = $this->query($query);
        if($result===false){
            return false;
        }
        while ($row = $result->fetch_assoc()){
            $rows[] = $row;
        }
        return $rows;
    }
    public function con($query){
        $connection = $this->connect();
        $result = $connection->query($query);
    }
    public function connection($query){
        $result = $this->query($query);
        if($result===false){
            return false;
        }
    }


    public function error(){
        $connection = $this->connect();
        return $connection->error;
    }
}
$con = mysqli_connect(SERVER_NAME,USERNAME,PASSWORD,DATABASE);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$mysqli = new mysqli(SERVER_NAME,USERNAME,PASSWORD,DATABASE);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}