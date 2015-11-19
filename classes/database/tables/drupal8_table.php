<?php
class drupal8_table extends DataStructure{

    public function set_table(){
        $this->table = 'drupal8';
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
            /*{hook}_table_update is optional for actions that need to be completed after this table is added to the database.  This hook is fired after a table is added to the database*/
        
    protected function drupal8_table_update(){
        global $date;
        $formalName = ucfirst($this->table);
        $result = $this->connection->insertData('systems', array('name' => $formalName, 'supported_since' => $date));
        if($result){
            
        }
        else{
            echo $this->connection->mysqli_error();   
        }
    }
}