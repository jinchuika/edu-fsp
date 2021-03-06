<?php
class GnEscuela
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
     * Lista todos los campos de todas las escuelas
     * @param  array $filtros
     * @return array
     */
    public function listar_escuela($filtros=null, $campos=' * ')
    {
        $respuesta = array();
        $query = "select ".$campos." from gn_escuela ";
        $stmt = $this->bd->ejecutar($query);
        while ($escuela = $this->bd->obtener_fila($stmt, 0)) {
            array_push($respuesta, $escuela);
        }
        return $respuesta;
    }

    /**
     * Abre los datos de una escuela
     * @param  Array  $filtros {campo, valor}
     * @param  string $campos  Los campos a buscar
     * @return Array
     */
    public function abrir_escuela(Array $filtros, $campos='*')
    {
        if(is_array($filtros)){
            $condicion = ' where gn_escuela._id>0  ';
            foreach ($filtros as $key => $filtro) {
                $condicion .= ' and '.$key.'="'.$filtro.'" ';
            }
        }
        $query = "select ".$campos." from gn_escuela ".$condicion;
        $stmt = $this->bd->ejecutar($query);
        if ($escuela = $this->bd->obtener_fila($stmt, 0)) {
            return $escuela;
        }
        else{
            return false;
        }
    }

}
?>