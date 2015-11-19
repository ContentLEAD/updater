<?php
class log_versions_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'log_versions';
    }
    public function set_primary_key(){
        $this->primaryKey = 'lid';
    }
    public function set_columns(){
        $column[] = (object) array(
                    'column_name'    => 'lid',
                        'type'      => 'int(11)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
        );
        $column[] = (object) array(
                    'column_name'    => 'domain',
                        'type'      => 'varchar(100)',
                        'default'   => 'NOT NULL',  
        );
        $column[] = (object) array(
                    'column_name'    => 'importer',
                        'type'      => 'varchar(100)',
                        'default'   => 'NOT NULL'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'version',
                        'type'      => 'varchar(100)',
                        'default'   => 'NOT NULL'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'api',
                        'type'      => 'text',
                        'default'   => 'NOT NULL'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'brand',
                        'type'      => 'text',
                        'default'   => 'NOT NULL'                               
        );
        return $coloumn;
    }
}