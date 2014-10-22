<?php
class PlnRegistro
{
    function __construct($bd=null, $sesion=null)
    {
        if(empty($bd)){
            require_once('../core/incluir.php');
            $nivel_dir = 3;
            $libs = new Incluir($nivel_dir);
            $this->bd = $libs->incluir('db');
            $this->sesion = $libs->incluir('sesion');
        }
        $this->bd = (!empty($bd)) ? $bd : $this->bd;
        $this->sesion = (!empty($sesion)) ? $sesion : $this->sesion;
    }
    
    /**
     * Abre un registro desde la base de datos
     * @param  Array $arr_filtro
     * @return Array
     */
    public function abrir_registro($arr_filtro, $dependientes=false)
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
            $registro['arr_funsepa'] = ($dependientes ? $this->abrir_contenido_funsepa(array('id_registro'=> $registro['_id']), 'id_funsepa') : null );
            $registro['arr_metodo'] = ($dependientes ? $this->abrir_metodo(array('id_registro'=> $registro['_id']), 'id_metodo') : null );
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
    
    /**
     * Crea un nuevo registro
     * @param  Array  $args
     * @return Array
     */
    public function crear_registro(Array $args)
    {
        $respuesta = array();
        $respuesta['msj'] = 'no';
        $query = "insert into pln_registro (id_plan, id_contenido, fecha, actividad, recurso) values ('".$args['id_plan']."', '".$args['n_contenido']."', '".$args['n_fecha']."', '".$args['n_actividad']."', '".$args['n_recursos']."')";
        if($this->bd->ejecutar($query, true)){
            $id_registro = $this->bd->lastID();
            if(is_array($args['n_funsepa'])){
                foreach ($args['n_funsepa'] as $reg_funsepa) {
                    $query_funsepa = "insert into pln_contenido_funsepa (id_registro, id_funsepa) values (".$id_registro.", ".$reg_funsepa.")";
                    $this->bd->ejecutar($query_funsepa);
                }
            }
            else{
                $query_funsepa = "insert into pln_contenido_funsepa (id_registro, id_funsepa) values (".$id_registro.", ".$args['n_funsepa'].")";
                $this->bd->ejecutar($query_funsepa);
            }
            
            if(is_array($args['n_metodo'])){
                foreach ($args['n_metodo'] as $reg_metodo) {
                    $query_metodo = "insert into pln_metodo (id_registro, id_metodo) values (".$id_registro.", ".$reg_metodo.")";
                    $this->bd->ejecutar($query_metodo);
                }
            }
            else{
                $query_metodo = "insert into pln_metodo (id_registro, id_metodo) values (".$id_registro.", ".$args['n_metodo'].")";
                $this->bd->ejecutar($query_metodo, true);
            }
            
            $respuesta['msj'] = 'si';
            $respuesta['_id'] = $id_registro;
        }
        else{
            $respuesta['query'] = $query;
        }
        return $respuesta;
    }

    public function borrar_registro($id_registro)
    {
        $respuesta = array('msj' => 'no');
        $query = 'delete from pln_registro where _id='.$id_registro;
        if($this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
        }
        return $respuesta;
    }
}
?>