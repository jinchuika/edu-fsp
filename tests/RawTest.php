<?php
require_once dirname(__FILE__) . '/../app/src/core/incluir.php';

class LoginTest extends PHPUnit_Framework_TestCase
{
    private $libs;
    private $mailer;
    
    public function testCreate()
    {
    	$this->libs = new Incluir(1);
    	$this->libs->incluir_clase('app/src/plugins/phpmailer/PHPMailerAutoload.php');
    	$this->mailer = new PHPMailer;
    	return $this->mailer;
    }

    /**
     * @depends testCreate
     */
    public function testSend($mailer)
    {
    	$mailer->send();
    }
}
?>