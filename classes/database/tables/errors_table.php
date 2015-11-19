<?php
class errors_table extends DataStructure{
    
    public function set_table(){
        $this->table = 'errors';
    }
    public function set_primary_key(){
        $this->primaryKey = 'Eid';
    }
    public function set_columns(){
        $column[] = (object) array(
                    'column_name'    => 'Eid',
                        'type'      => 'int(11)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
                        'update'    => ''
        );
        $column[] = (object) array(
                    'column_name'    => 'type',
                        'type'      => 'varchar(45)',
                        'default'   => 'NOT NULL',
                        'update'    => ''       
        );
        $column[] = (object) array(
                    'column_name'    => 'domain',
                        'type'      => 'longtext',
                        'default'   => 'NOT NULL'                               
        );
        $column[] = (object) array(
                    'column_name'    => 'date',
                        'type'      => 'datetime',
                        'default'   => 'DEFAULT CURRENT_TIMESTAMP',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'error',
                        'type'      => 'longtext',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        $column[] = (object) array(
                    'column_name'    => 'sync',
                        'type'      => 'int(1)',
                        'default'   => 'DEFAULT 0',
                        'update'    => ''        
        );
        
        return $column;
    }
    
    protected function domain_update(){
        $total = $this->connection->retrieveData('errors','*');
        foreach($total as $record){
            $url = json_decode($record['error'])->Domain;
            $id = $record['Eid'];
            $data = array(
                'domain'        => $url
            );
            if($record['domain'] == '' || $record['domain'] == NULL){
                $this->connection->updateData('errors', $data, array('WHERE' => array("Eid='$id'")));
            }
        }
    }
}