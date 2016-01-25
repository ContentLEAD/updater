<?php

class error_garbage_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'error_garbage';
    }
    public function get_table(){
        return $this->table;
    }
    public function set_primary_key(){
        $this->primaryKey = 'id';
    }
    public function set_columns(){
        $column[] = (object) array(
                    'column_name'    => 'id',
                        'type'      => 'int(10)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
        );
        $column[] = (object) array(
                    'column_name'    => 'domain',
                        'type'      => 'varchar(250)',
                        'default'   => 'NOT NULL',
        );
        return $column;
    }
    
    /*{hook}_update is optional for columns that require data updates form existing data in a data set.  This hook is fired after a column is added to the table
    
    protected function {column-name}_update(){
        $total = $this->connection->retrieveData($this->table,'*');
        foreach($total as $record){
            $data = array(
            
            );
            $cond = 
            if(){
                $this->connection->updateData($this->table, $data, array('WHERE' => array($cond)));
            }
        }
    }
    
    /*{hook}_table_update is optional for actions that need to be completed after this table is added to the database.  This hook is fired after a table is added to the database
    
    protected function {table-name}_table_update(){
        $total = $this->connection->retrieveData($this->table,'*');
        foreach($total as $record){
            $data = array(
            
            );
            $cond = 
            if(){
                $this->connection->updateData($this->table, $data, array('WHERE' => array($cond)));
            }
        }
    }
    */
}
