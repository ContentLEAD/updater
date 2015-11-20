<?php
$table = 'testing';
$script = "<?php
class {$table}_table extends DataStructure{
    
    public function __construct(\$connection){
        parent::__construct(\$connection);
    }
    public function set_table(){
        \$this->table = $table;
    }
    public function get_table(){
        return \$this->table;
    }
    
    public function set_columns(){
        \$this->structure[] = (object) array(
                    'column_name'    => 'Eid',
                        'type'      => 'int(11)',
                        'default'   => 'AUTO_INCREMENT NOT NULL',
                        'update'    => ''
        );
        \$this->structure[] = (object) array(
                    'column_name'    => 'type',
                        'type'      => 'varchar(45)',
                        'default'   => 'NOT NULL',
                        'update'    => ''       
        );
        \$this->structure[] = (object) array(
                    'column_name'    => 'domain',
                        'type'      => 'longtext',
                        'default'   => 'NOT NULL'                               
        );
        \$this->structure[] = (object) array(
                    'column_name'    => 'date',
                        'type'      => 'datetime',
                        'default'   => 'DEFAULT CURRENT_TIMESTAMP',
                        'update'    => ''        
        );
        \$this->structure[] = (object) array(
                    'column_name'    => 'error',
                        'type'      => 'longtext',
                        'default'   => 'NULL',
                        'update'    => ''        
        );
        \$this->structure[] = (object) array(
                    'column_name'    => 'sync',
                        'type'      => 'int(1)',
                        'default'   => 'DEFAULT 0',
                        'update'    => ''        
        );
    }
    public function get_columns(){
        return \$this->structure;
    }
    protected function domain_update(){
        \$total = \$this->connection->retrieveData('errors','*');
        foreach(\$total as \$record){
            \$url = json_decode(\$record['error'])->Domain;
            \$id = \$record['Eid'];
            \$data = array(
                'domain'        => \$url
            );
            if(\$record['domain'] == '' || \$record['domain'] == NULL){
                \$this->connection->updateData('errors', \$data, array('WHERE' => array(\"Eid='\$id'\")));
            }
        }
    }
}";