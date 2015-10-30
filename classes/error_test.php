<?php 
class Brafton_errors {
    public $error;
    public $code;
    public $page;
    public $date;
    
    public function __construct($settings = null){
        ini_set( "display_errors", 1 );
        error_reporting( E_ALL );
        
    }
}
$setuperrors = new Brafton_errors();
?>