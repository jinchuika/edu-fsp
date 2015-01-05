<?php
require_once dirname(__FILE__) . '/../app/src/model/PlnRegistro.class.php';


class PlnRegistroTest extends PHPUnit_Framework_TestCase
{
    private $registro;
    
    public function setUp()
    {
        # code...
    }
    
    public function testCreate()
    {
        $this->registro = new PlnRegistro();
    }   
}
?>