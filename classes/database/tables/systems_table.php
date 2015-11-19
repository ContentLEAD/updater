<?php
class systems_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'systems';
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
                    'column_name'   => 'logo',
                    'type'          => 'longtext',
                    'default'       => 'NULL'
        );
        $column[] = (object) array(
                    'column_name'    => 'supported_since',
                        'type'      => 'date',
                        'default'   => 'CURRENT_TIMESTAMP'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'support_end',
                        'type'      => 'date',
                        'default'   => 'NULL'                               
        );
        return $column;
    }
}