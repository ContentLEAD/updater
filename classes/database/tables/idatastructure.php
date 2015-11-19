<?php
interface iDataStructure {
    public function set_table();
    
    public function get_table();
    
    public function set_columns();
    
    public function get_columns();
    
    public function set_primary_key();
}