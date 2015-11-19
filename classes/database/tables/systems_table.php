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
    protected function logo_update(){
        global $date;
        $logos = array(
            'Wordpress' => 'wordpress-color.png',
            'HubspotCOS'    => 'hubspot-bubble.jpg',
            'Drupal7'       => 'drupal-straight.png',
            'Joomla3'   => 'joomla-swirl.jpg',
            'Drupal8'   => 'drupal-skew.png'
        );
            foreach($logos as $importer => $logo){
                $result = $this->connection->updateData('systems', array('logo' => $logo), array('WHERE', array("name = '$importer'")));
                if($result){

                }
                else{
                    echo $this->connection->mysqli_error();   
                }
            }
    }
}