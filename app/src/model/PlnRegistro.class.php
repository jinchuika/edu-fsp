<?php
class PlnRegistro
{
    function __construct($libs=null)
    {
        if(empty($libs)){
            require_once('../core/incluir.php');
            $nivel_dir = 3;
            $this->libs = new Incluir($nivel_dir);
        }
        else{
            $this->libs = $libs;
        }
        $this->bd = $this->libs->incluir('db');
    }
    
    /**
     * Abre varios registros desde la base de datos en base al filtro
     * @param  Array $arr_filtro
     * @param boolean $dependientes Si abre también el contenido de funsepa y los metodos
     * @return Array
     */
    public function abrir_registro($arr_filtro, $dependientes=false)
    {
        $respuesta = array();
        $query = "select * from pln_registro ";
        if(is_array($arr_filtro)){
            $query .= " where 1=1 ";
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
     * Actualiza los métodos para ese registro
     * @param  integer $id_registro El id del registro a cambiar
     * @param  integer|array $arr_metodo  Los nuevos ID
     * @return Array              La lista de ID que se añadieron
     */
    public function actualizar_metodo($id_registro, $arr_metodo)
    {
        $this->libs->incluir_clase('app/src/model/PlnMetodo.class.php');
        $pln_metodo = new PlnMetodo($this->libs);
        $pln_metodo->eliminar_pln_metodo('id_registro', $id_registro);
        $crear = $pln_metodo->crear_pln_metodo($id_registro, $arr_metodo);
        return $crear['arr_id'];
    }

    /**
     * Actualiza el contenido de funsepa para ese registro
     * @param  integer $id_registro El id del registro a cambiar
     * @param  integer|array $arr_metodo  Los nuevos id
     * @return Array|void              Listado de los nuevos ID | void si no habían nuevos
     */
    public function actualizar_funsepa($id_registro, $arr_funsepa)
    {
        $this->libs->incluir_clase('app/src/model/PlnContenidoFunsepa.class.php');
        $pln_funsepa = new PlnContenidoFunsepa($this->libs);
        $pln_funsepa->eliminar_pln_funsepa('id_registro', $id_registro);
        $crear = $pln_funsepa->crear_pln_funsepa($id_registro, $arr_funsepa);
        return $crear['arr_id'];
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
        $this->libs->incluir_clase('app/src/model/PlnContenidoFunsepa.class.php');
        $this->libs->incluir_clase('app/src/model/PlnMetodo.class.php');
        
        $pln_contenido_funsepa = new PlnContenidoFunsepa($this->libs);
        $pln_metodo = new PlnMetodo($this->libs);

        $actividad = $this->bd->escapar_string($args['n_actividad']);
        $recursos = $this->bd->escapar_string($args['n_recursos']);
        $query = "insert into pln_registro (id_plan, id_contenido, fecha, actividad, recurso) values ('".$args['id_plan']."', '".$args['n_contenido']."', '".implode("-",array_reverse(explode("/",$args['n_fecha'])))."', '".$actividad."', '".$recursos."')";
        if($this->bd->ejecutar($query, true)){
            $id_registro = $this->bd->lastID();

            !empty($args['n_funsepa']) ? $pln_contenido_funsepa->crear_contenido_funsepa($id_registro, $args['n_funsepa']) : '';
            !empty($args['n_metodo'])  ? $pln_metodo->crear_pln_metodo($id_registro, $args['n_metodo']) : '';

            $respuesta['msj'] = 'si';
            $respuesta['_id'] = $id_registro;
        }
        return $respuesta;
    }

    /**
     * Elimina un registro basado en su ID
     * @param  integer $id_registro el id del registro
     * @return Array              {msj: si se pudo}
     */
    public function borrar_registro($id_registro)
    {
        $respuesta = array('msj' => 'no');
        $query = 'delete from pln_registro where _id='.$id_registro;
        if($this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
        }
        return $respuesta;
    }

    public function editar_registro($id_registro, $campo, $value='')
    {
        $value = $this->bd->escapar_string($value);
        $query = "update pln_registro set ".$campo."='".$value."' where _id=".$id_registro;
        if($this->bd->ejecutar($query, true)){
            return true;
        }
    }
}
?>