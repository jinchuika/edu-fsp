<?php
class GnPlan
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
     * Crea un nuevo plan
     * @param  integer $id_user
     * @param  integer $id_clase
     * @return array
     */
    public function crear_plan($id_user, $id_clase)
    {
        $respuesta = array('msj'=>'no');
        $query = 'insert into gn_plan (id_user, id_clase) values ('.$id_user.', '.$id_clase.')';
        if($this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
            $respuesta['_id'] = $this->bd->lastID();
        }
        return $respuesta;
    }
    
    /**
     * Abre un planificador
     * @param  Array  $arr_filtro
     * @return Array
     */
    public function buscar_plan($arr_filtro)
    {
        if(is_array($arr_filtro)){
            $condicion = ' where _id>0  ';
            foreach ($arr_filtro as $key => $filtro) {
                $condicion .= ' and '.$key.'="'.$filtro.'" ';
            }
        }
        $query = "select * from gn_plan ".$condicion;
        $stmt = $this->bd->ejecutar($query);
        if($plan = $this->bd->obtener_fila($stmt)){
            return $plan;
        }
    }
}
?>