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
    
    public function abrir_registro($arr_filtro)
    {
        $respuesta = array();
        $query = "select * from pln_registro ";
        if(is_array($arr_filtro)){
            $query .= " where _id>0 ";
            foreach ($arr_filtro as $key => $filtro) {
                $query .= " and ".$key."=".$filtro;
            }
        }
        $stmt = $this->bd->ejecutar($query);
        while ($registro = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $registro);
        }
        
        //(count($respuesta)==1) ? $respuesta = $respuesta[0] : '';
        return $respuesta;
    }
    
    /**
     * Carga los datos de relación para muchos a muchos del contenido funsepa
     * @param  Array $arr_filtro
     * @return Array
     */
    public function abrir_contenido_funsepa($arr_filtro, $campos='*')
    {
        $respuesta = array();
        $query = "select ".$campos." from pln_contenido_funsepa ";
        if(is_array($arr_filtro)){
            $query .= " where _id>0 ";
            foreach ($arr_filtro as $key => $filtro) {
                $query .= " and ".$key."=".$filtro;
            }
        }
        $stmt = $this->bd->ejecutar($query);
        while ($funsepa = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $funsepa);
        }
        
        //(count($respuesta)==1) ? $respuesta = $respuesta[0] : '';
        return $respuesta;
    }
    
    /**
     * Carga los datos de relación para muchos a muchos de los métodos de evaluación
     * @param  Array $arr_filtro
     * @param string $campos los campos a pedir
     * @return Array
     */
    public function abrir_metodo($arr_filtro, $campos='*')
    {
        $respuesta = array();
        $query = "select ".$campos." from pln_metodo ";
        if(is_array($arr_filtro)){
            $query .= " where _id>0 ";
            foreach ($arr_filtro as $key => $filtro) {
                $query .= " and ".$key."=".$filtro;
            }
        }
        $stmt = $this->bd->ejecutar($query);
        while ($metodo = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $metodo);
        }
        
        //(count($respuesta)==1) ? $respuesta = $respuesta[0] : '';
        return $respuesta;
    }
}
?>