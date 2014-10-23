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
    public function buscar_plan($arr_filtro, $campos='*')
    {
        if(is_array($arr_filtro)){
            $condicion = ' where gn_plan._id>0  ';
            foreach ($arr_filtro as $key => $filtro) {
                $condicion .= ' and '.$key.'="'.$filtro.'" ';
            }
        }
        $query = "select ".$campos." from gn_plan 
        inner join gn_clase on gn_clase._id=gn_plan.id_clase
        ".$condicion;
        
        $stmt = $this->bd->ejecutar($query);
        if($plan = $this->bd->obtener_fila($stmt)){
            $plan['query'] = $query;
            return $plan;
        }
    }

    /**
     * Permite publicar ua planificación
     * @param  integer $id_plan
     * @param  integer $tipo
     * @return Array
     */
    public function publicar_plan($id_plan, $tipo=1)
    {
        $respuesta = array('msj'=>'no');
        $query = "UPDATE gn_plan set public=".$tipo." where _id=".$id_plan;
        if($this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
            $respuesta['_id'] = $id_plan;
        }
        return $respuesta;
    }
}
?>