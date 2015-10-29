<?php 
class Brafton_errors {
    public $error;
    public $code;
    public $page;
    public $date;
    
    public function __construct($settings){
        error_reporting(E_ALL);
            init_set("display_errors", 1);
        
    }
}
$setuperrors = new Brafton_errors();
?>