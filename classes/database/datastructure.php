<?php
abstract class DataStructure {
    protected $connection;
    protected $table;
    protected $primaryKey;
    public function __construct(){
        global $con;
        $this->connection = $con;
        $this->set_table();
        $this->set_primary_key();
        $this->set_columns();
        $this->table_check();
        $this->column_check();
    }
    public function get_columns(){
        return $this->set_columns();
    }
    public function get_table(){
        return $this->table;   
    }
    private function table_check(){
        if(!$this->connection->query_for_table($this->table)){
            $query = " CREATE TABLE IF NOT EXISTS `$this->table` ( ";
            foreach($this->get_columns() as $column){
                $query .= "$column->column_name $column->type $column->default , ";
            }
            $query .= "PRIMARY KEY ( `{$this->primaryKey}` ) )";
            $result = $this->connection->customQuery($query);
            if($result){
                if(method_exists($this, $this->table.'_table_update')){
                    $this->{$this->table.'_table_update'}();
                }
                echo '<pre class="datastructure warning">';
                echo "DataStructure has changed: ADDED  '{$this->table}' Table to Database<br/> ";
                echo '</pre>';
            }else if($this->connection->errorDisplay){
                echo '<pre class="datastructure error">';
                echo "DataStructure has changed: There was an error applying your DataStructure change to your database<br/>". $this->connection->mysqli_error();
                echo '</pre>';
            }
        }
    }
    
    private function column_check(){
        foreach($this->get_columns() as $column){
            $results = $this->connection->customQuery("SELECT * 
            FROM information_schema.COLUMNS 
            WHERE
            TABLE_NAME = '$this->table' 
            AND COLUMN_NAME = '$column->column_name'");
            if($results->num_rows == 0){
                $this->column_add($column);   
            }
        }
    }
    
    private function column_add($column){
        $pos = ($pos = ArrayUtils::objArraySearch($this->get_columns(), 'column_name', $column->column_name)) ? $pos : 0 ;
        $after = $pos ? ' AFTER '.$this->get_columns()[$pos -1]->column_name : ' FIRST';
        $result = $this->connection->customQuery("ALTER TABLE $this->table ADD $column->column_name $column->type $column->default ". $after);
        if($result){
            echo '<pre class="datastructure warning">';
            echo "DataStructure has changed: ADDED  $column->column_name Column to $this->table Table<br/> ";
            if(method_exists($this, $column->column_name.'_update')){
                $this->{$column->column_name.'_update'}();
                echo "Your Data sets have been updated to accomodate your new schema";
            }
            echo '</pre>';
        }else if($this->connection->errorDisplay){
            echo '<pre class="datastructure error">';
            echo "DataStructure has changed: There was an error applying your DataStructure change to your database<br/>". $this->connection->mysqli_error();
            echo '</pre>';
            
        }
    }
}
