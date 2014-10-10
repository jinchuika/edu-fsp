<?php
class ClCarrera
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
     * Lista todas las carreras
     * @param  array $filtro
     * @return array
     */
    public function listar_carrera($filtro=null)
    {
        $resultado = array();
        $query = "select * from cl_carrera ";
        $stmt = $this->bd->ejecutar($query);
        while ($carrera = $this->bd->obtener_fila($stmt)) {
            array_push($resultado, $carrera);
        }
        return $resultado;
    }
}
?>