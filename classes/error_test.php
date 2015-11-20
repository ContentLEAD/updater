<?php 
class Brafton_errors {
    public $error;
    public $code;
    public $page;
    public $date;
    private static $instance = null;
    
    public static function ErrorsInstance($settings = null) {
		
		if( Brafton_errors::$instance === null) {
			Brafton_errors::$instance = new Brafton_errors($settings);
		}
		
		return Brafton_errors::$instance;
	}
    private function __construct($settings = null){
        if($settings){
            ini_set( "display_errors", 1 );
            error_reporting( E_ALL );
        }
        define("DEBUG", $settings);
    }
}

$setuperrors = Brafton_errors::ErrorsInstance(DEBUG_MODE);
?>