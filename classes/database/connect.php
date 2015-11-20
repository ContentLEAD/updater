<?php 
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
    protected $connection;
    public $ENV;
    public $errorDisplay = false;
    
    //constuct the db connection
    public function __construct(){
        $this->ENV = Icreds::ENV;
        if($this->ENV != 'LIVE'){
            $this->errorDisplay = true;
        }
        $this->host = Icreds::HOST;
        $this->user = Icreds::USER;
        $this->pass = Icreds::PASS;
        $this->db = Icreds::DB;
        $this->connection = $this->hook();
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
        
        //echo $sql;
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
    public function schema_connection($table){
        $result = $this->connection->query("SELECT * FROM $table LIMIT 0");
        return $result->fetch_fields();
        
    }
    public function customQuery($sql){
        $result = $this->connection->query($sql);
        return $result;
    }
    public function show_env(){
        if($this->ENV != 'LIVE'){
            echo $this->ENV . ' : : ';
        }
    }
    public function mysqli_error(){
        return $this->connection->error;
    }
    public function query_for_table($table){
        return $this->connection->query("DESCRIBE `$table`");   
    }
    public function connection_method($method){
        return $this->connection->$method;
    }
    public function uploadDB($sql){
        //$sql = $this->connection->real_escape_string($sql);
        $result = $this->connection->multi_query($sql);
        $errors = 0;
        $report = array();
        do {
            /* store first result set */
            if ($oresult = $this->connection->store_result()) {
                while ($row = $oresult->fetch_row()) {
                    $report[] = $row[0];
                    ++$errors;
                }
                $oresult->free();
            }
            /* print divider */
            if ($this->connection->more_results()) {
            }
        } while ($this->connection->next_result());
        $pack = array($errors, $report);
        return $pack;
    }
    public function getDBName(){
        return $this->db;   
    }
}
$con = new DBConnect();
?>