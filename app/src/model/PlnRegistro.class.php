<?php
class PlnRegistro
{
    function __construct($bd=null)
    {
        if(empty($bd)){
            require_once('../core/incluir.php');
            $nivel_dir = 3;
            $libs = new Incluir($nivel_dir);
            $this->bd = $libs->incluir('db');
        }
        $this->bd = (!empty($bd)) ? $bd : $this->bd;
    }
    
    public function abrir_registro($id_registro)
    {
        # code...
    }
}
?>