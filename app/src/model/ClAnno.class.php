<?php
/**
 * Gestiona los años
 */
class ClAnno
{
    /**
     * Crea la instancia
     * @param Db $bd Instancia de la base de datos
     */
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
     * Lista todos los años
     * @param  array $filtro
     * @return array
     */
    public function listar_anno($filtro=null)
    {
        $resultado = array();
        $query = "select * from cl_anno ";
        $stmt = $this->bd->ejecutar($query);
        while ($anno = $this->bd->obtener_fila($stmt)) {
            array_push($resultado, $anno);
        }
        return $resultado;
    }
}
?>