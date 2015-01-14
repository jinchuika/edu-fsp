<?php
class CnbRelContenido
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
     * Abre una relación entre el contendio MINEDUC y el contenido FUSNEPA
     * @param  Array $arr_filtro
     * @return Array
     */
    public function abrir_rel_contenido($arr_filtro=null)
    {
        $respuesta = array();
        $query = "select * from cnb_rel_contenido ";
        if(is_array($arr_filtro)){
            $query .= " where 1=1 ";
            foreach ($arr_filtro as $key => $filtro) {
                $query .= " and ".$key."=".$filtro;
            }
        }
        $stmt = $this->bd->ejecutar($query);
        while ($rel_contenido = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $rel_contenido);
        }
        
        //(count($respuesta)==1) ? $respuesta = $respuesta[0] : '';
        return $respuesta;
    }
}
?>