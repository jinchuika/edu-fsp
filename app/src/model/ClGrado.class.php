<?php
class ClGrado
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
    
    /**
     * Lista todos los grados
     * @param  array $filtro
     * @return array
     */
    public function listar_grado($filtro=null)
    {
        $resultado = array();
        $query = "select * from cl_grado ";
        $stmt = $this->bd->ejecutar($query);
        while ($grado = $this->bd->obtener_fila($stmt)) {
            array_push($resultado, $grado);
        }
        return $resultado;
    }
}
?>