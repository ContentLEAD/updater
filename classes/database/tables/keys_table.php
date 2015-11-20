<?php
class keys_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'keys';
    }
    public function set_primary_key(){
        $this->primaryKey = 'kid';
    }
    public function set_columns(){
        $column[] = (object) array(
                    'column_name'    => 'kid',
                        'type'      => 'int(11)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
        );
        $column[] = (object) array(
                    'column_name'    => 'encryptionKey',
                        'type'      => 'varchar(100)',
                        'default'   => 'NOT NULL',  
        );
        $column[] = (object) array(
                    'column_name'    => 'importer',
                        'type'      => 'varchar(100)',
                        'default'   => 'NOT NULL'                               
        );
        return $column;
    }
}