<?php 
//require 'error_test.php';
//credential interface
require_once('creds.php');
/*
*
* DBConnect class for use with the Brafton Update API for plugins and modules
*
*/
class DBConnect implements Icreds {
    private $host;
    private $user;
    private $pass;
    private $db;
    private $connection;
    public $ENV;
    
    //constuct the db connection
    public function __construct(){
        $this->host = Icreds::HOST;
        $this->user = Icreds::USER;
        $this->pass = Icreds::PASS;
        $this->db = Icreds::DB;
        $this->connection = $this->hook();
        $this->ENV = Icreds::ENV;
        if($this->ENV != 'LIVE'){
            include_once 'error_test.php';
        }
    }
    //make the database connection
    private function hook(){
        $hook = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        return $hook;
    }
    public function idle_query($sql){
        $result = $this->connection->query($sql);
        //echo mysqli_errno($this->connection);
        return $result;
    }
    //data retrieval statment $conditions must be an array of conditions.
    /* $conditons format 
    * array("WHERE/ORDER BY ECT" => array("condtion", "condition", ect)
    *
    */
    public function retrieveData($table, $data, $conditions = NULL){
        $table = strtolower($table);
        $statement = '';
        $array = array();
        if($conditions){
            if(!is_array($conditions)){
                echo 'DBConnect Error: Your statement is malformed--> for retrieveData($table,$data,$conditions)';
                exit;
            }
            foreach($conditions as $condition => $vals){
            $statement .= $condition;
            $i = 0;
            foreach($vals as $key => $value){
                
                if($i>0){ $statement .= " AND ";}
                $statement .=  ' '.$value;
                $i++;
            }
            }
        }
        $sql = "SELECT $data FROM `$table` $statement";
        //echo $sql;
        $result = $this->connection->query($sql);
        if(!$result){ return $result; }
        while($addto = mysqli_fetch_assoc($result)){
            $array[] = $addto;
        }
        return $array;
    }
    //updateData statement $conditions must be an array
    public function updateData($table, $data, $conditions=NULL){
        $table = strtolower($table);
        if(!is_array($data)){
            echo 'DBConnect Error: Your statement is malformed--> for updateData($table, $data, $conditions)';
            exit;
        }
        if(!$conditions){
            echo 'DBConnect Error: Your statement is malformed--> for updateData($table, $data, $conditions)';
            exit;
        }
        $statement = '';
        $i=0;
        foreach($data as $key => $val){
            $val = $this->connection->real_escape_string($val);
            if($i>0){ $statement .= ','; }
            $statement .= " $key = '$val'";
            ++$i;
        }
        foreach($conditions as $condition => $vals){
            $statement .= ' '.$condition;
            $i = 0;
            foreach($vals as $key => $value){
                if($i>0){ $statement .= " AND ";}
                $statement .=  ' '.$value;
                $i++;
            }
        }
        $sql = "UPDATE $table set $statement ";
        //echo $sql;
        $result = $this->connection->query($sql);
        //echo mysqli_errno($this->connection);
        return $result;
    }
    //insert statement
    public function insertData($table, $data){
        $table = strtolower($table);
        if(!is_array($data)){
            echo 'DBConnect Error: Your statement is malformed--> for insertData($table, $data)';
        }
        $statement = '';
        $i=0;
        foreach($data as $key => $val){
            $val = $this->connection->real_escape_string($val);
            if($i>0){ $statement .= ','; }
            $statement .= " $key = '$val'";
            ++$i;
        }
        $sql = "INSERT INTO `$table` set $statement";
        
        echo $sql;
        $result = $this->connection->query($sql);
        //echo mysqli_errno($this->connection);
        return $result;
    }
    //delete data $conditions must be an array
    public function deleteData($table, $data, $conditions){
        
    }
    //create table $fields must be array
    /*Standard fields are Vid, version, description, name, requires, tested, last_updated, download_link, code_name, features, {array $fields list of required fields for use with the prorietry cms update system} */
    public function createTable($table, $fields = NULL){
        if(!is_array($fields)){
            echo 'DBConnect Error: Your statement is malformed--> for createTable($table, $fields)';
            return;
        }
    }
    public function customQuery($sql){
        $result = $this->connection->query($sql);   
    }
    public function show_env(){
        if($this->ENV == 'STAGING'){
            echo $this->ENV;
        }
    }
}
?>