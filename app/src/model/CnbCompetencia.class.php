<?php
class CnbCompetencia
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
     * Abre una competencia basada en el ID
     * @param  Array $arr_filtro
     * @return Array
     */
    public function abrir_competencia($arr_filtro=null)
    {
        $respuesta = array();
        $query = "select * from cnb_competencia ";
        if(is_array($arr_filtro)){
            $query .= " where _id>0 ";
            foreach ($arr_filtro as $key => $filtro) {
                $query .= " and ".$key."=".$filtro;
            }
        }
        $stmt = $this->bd->ejecutar($query);
        while ($competencia = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $competencia);
        }
        
        //(count($respuesta)==1) ? $respuesta = $respuesta[0] : '';
        return $respuesta;
    }
}
?>