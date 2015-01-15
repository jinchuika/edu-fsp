<?php
require_once dirname(__FILE__) . '/../includes/auth/Login.class.php';


class LoginTest extends PHPUnit_Framework_TestCase
{
    private $registro;
    
      
    public function testCreate()
    {
    	$string = 'hola';
        $encriptada = Login::esconder_string('hola');
        $desencriptada = Login::desencriptar($encriptada);
        echo $string."\n".$encriptada."\n".$desencriptada;
    }   
}
?>