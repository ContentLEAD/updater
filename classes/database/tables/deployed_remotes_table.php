<?php
class deployed_remotes_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'deployed_remotes';
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
                    'column_name'    => 'client_url',
                        'type'      => 'varchar(150)',
                        'default'   => 'NOT NULL',  
        );
        $column[] = (object) array(
                    'column_name'    => 'functions',
                        'type'      => 'longtext',
                        'default'   => 'NOT NULL'                               
        );
        $column[] = (object) array(
                    'column_name'   => 'response',
                        'type'      => 'text',
                        'default'   => 'NOT NULL'
        );
        $column[] = (object) array(
                    'column_name'    => 'date',
                        'type'      => 'datetime',
                        'default'   => 'NOT NULL',
        );
        $column[] = (object) array(
                    'column_name'    => 'reponse_date',
                        'type'      => 'datetime',
                        'default'   => 'NOT NULL',
        );
        return $column;
    }
}