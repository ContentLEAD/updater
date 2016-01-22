<?php
class dotnetnuke_table extends DataStructure{

    public function set_table(){
        $this->table = 'dotnetnuke';
    }
    
    public function set_primary_key(){
        $this->primaryKey = 'Vid';
    }
    public function set_columns(){
        $column[] = (object) array(
                    'column_name'    => 'Vid',
                        'type'      => 'int(11)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
                        'update'    => ''
        );
        $column[] = (object) array(
                    'column_name'    => 'version',
                        'type'      => 'varchar(10)',
                        'default'   => 'NULL',
                        'update'    => ''       
        );
        $column[] = (object) array(
                    'column_name'    => 'description',
                        'type'      => 'longtext',
                        'default'   => 'NULL'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'name',
                        'type'      => 'varchar(75)',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'requires',
                        'type'      => 'varchar(75)',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'tested',
                        'type'      => 'varchar(75)',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'last_updated',
                        'type'      => 'datetime',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'download_link',
                        'type'      => 'longtext',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'code_name',
                        'type'      => 'varchar(75)',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'features',
                        'type'      => 'longtext',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'custom',
                        'type'      => 'longtext',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        return $column;
    }
    
        /*{hook}_update is optional for columns that require data updates form existing data in a data set.  This hook is fired after a column is added to the table*/
    /*
    protected function {column-name}_update(){
        $total = $this->connection->retrieveData($this->table,'*');
        foreach($total as $record){
            $data = array();
            
            $cond = array();
            
            if(){
                $this->connection->updateData($this->table, $data, array('WHERE' => array($cond)));
            }
        }
    }
    */
    
        /*{hook}_table_update is optional for actions that need to be completed after this table is added to the database.  This hook is fired after a table is added to the database*/
        
    protected function dotnetnuke_table_update(){
        global $date;
        $formalName = ucfirst($this->table);
        $result = $this->connection->insertData('systems', array('name' => $formalName, 'supported_since' => $date, 'logo'  => 'communityDLSeal.png'));
        if($result){
            
        }
        else{
            echo $this->connection->mysqli_error();   
        }
    }
}