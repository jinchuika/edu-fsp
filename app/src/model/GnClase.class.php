<?php
class GnClase
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
     * Crea una nueva clase
     * @param  integer $id_anno
     * @param  integer $id_carrera
     * @param  integer $id_grado
     * @return array  id de la clase nueva
     */
    public function crear_clase($id_anno, $id_carrera, $id_grado)
    {
        $respuesta = array('msj' => 'no');
        $query = "insert into gn_clase (id_anno, id_carrera, id_grado) values (".$id_anno.", ".$id_carrera.", ".$id_grado.")";
        if ($this->bd->ejecutar($query)) {
            $respuesta['msj'] = 'si';
            $respuesta['_id'] = $this->bd->lastID();
        }
        return $respuesta;
    }
    
    /**
     * Devuelve todos los datos de una clase
     * @param  integer $id_anno
     * @param  integer $id_carrera
     * @param  integer $id_grado
     * @return array|bool
     */
    public function buscar_clase($id_anno, $id_carrera, $id_grado)
    {
        $query = 'select * from gn_clase where id_anno='.$id_anno.' AND id_carrera='.$id_carrera.' AND id_grado='.$id_grado;
        $stmt = $this->bd->ejecutar($query);
        if ($clase = $this->bd->obtener_fila($stmt)) {
            return $clase;
        }
        else{
            return false;
        }
    }

    /**
     * Busca una clase
     * @param  Array $filtros Los filtros de búsqueda
     * @param  string $campos  Los campos a buscar
     * @param  string $extra   Información extra como JOINS
     * @return Array
     */
    public function abrir_clase($filtros, $campos='*', $extra='')
    {
        if(is_array($filtros)){
            $condicion = ' where gn_clase._id>0  ';
            foreach ($filtros as $key => $filtro) {
                $condicion .= ' and '.$key.'="'.$filtro.'" ';
            }
        }
        $query = "select ".$campos." from gn_clase ".$extra." ".$condicion;
        $stmt = $this->bd->ejecutar($query);
        if($clase = $this->bd->obtener_fila($stmt, 0)){
            return $clase;
        }
        else{
            echo $query;
            return false;
        }

    }
}
?>