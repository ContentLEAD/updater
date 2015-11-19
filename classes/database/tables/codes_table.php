<?php
class codes_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'codes';
    }
    public function set_primary_key(){
        $this->primaryKey = 'id';
    }
    public function set_columns(){
        $column[] = (object) array(
                    'column_name'    => 'id',
                        'type'      => 'int(11)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
        );
        $column[] = (object) array(
                    'column_name'    => 'name',
                        'type'      => 'varchar(100)',
                        'default'   => 'NOT NULL',  
        );
        $column[] = (object) array(
                    'column_name'    => 'support_added',
                        'type'      => 'longtext',
                        'default'   => 'NOT NULL'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'active',
                        'type'      => 'int(11)',
                        'default'   => 'DEFAULT 0',
        );
        return $column;
    }
}