<?php
class User
{
    function __construct($libs=null)
    {
        if(!($libs instanceof Incluir)){
            require_once('../core/incluir.php');
            $nivel_dir = 3;
            $this->libs = new Incluir($nivel_dir);
        }else{
            $this->libs = $libs;
        }
        $this->bd = $this->libs->incluir('db');
        
    }
    
    /**
     * Crea un usuario en la base de datos
     * @param  string  $nombre    
     * @param  string  $apellido  
     * @param  integer $id_genero 
     * @param  integer $id_escuela
     * @param  string  $username  
     * @param  string  $password  
     * @param  Array  $args['extra']      Los parámetros opcionales
     * @return Array
     */
    public function crear_usuario($args)
    {
        $respuesta = array();
        
        $this->libs->incluir_clase('includes/auth/Login.class.php');
        $pass = Login::encriptar($args['password']);

        $query = 'call crearUsuario("'.$args['nombre'].'", "'.$args['apellido'].'", '.$args['id_genero'].', '.$args['id_escuela'].',"'.$args['username'].'", "'.$pass['string'].'", "'.$args['mail'].'", "'.$pass['key'].'")';
        $stmt = $this->bd->ejecutar($query, true);
        if($user = $this->bd->ejecutar_procedimiento($stmt)){
            $respuesta['msj']= 'si';
            if(is_array($args['extra'])){
                $query_update = 'UPDATE usr_persona SET _id='.$user['_id'];
                foreach ($args['extra'] as $key => $campo) {
                    $query_update .= ', '.$key.'="'.$campo.'"';
                }
                $query_update .= 'WHERE _id='.$user['_id'];
                $stmt = $this->bd->ejecutar($query_update, true);
            }
            return $respuesta;
        }
    }

    public function validar_nombre($filtro, $tabla='user')
    {
        $query = "select _id from ".$tabla." where ".$filtro['campo']."='".$filtro['valor']."'";
        $stmt = $this->bd->ejecutar($query);
        if($this->bd->obtener_fila($stmt)){
            return array('valid' => false);
        }
        else{
            return array('valid' => true);
        }
    }

    public function abrir_usuario(Array $filtros, $campos = '*')
    {
        if(is_array($filtros)){
            $condicion = ' where user._id>0  ';
            foreach ($filtros as $key => $filtro) {
                $condicion .= ' and '.$key.'="'.$filtro.'" ';
            }
        }
        $query = "select ".$campos." from user 
        inner join usr_persona on usr_persona._id=user._id
        ".$condicion;
        $stmt = $this->bd->ejecutar($query, true);
        if($usuario = $this->bd->obtener_fila($stmt)){
            return $usuario;
        }
        else{
            return false;
        }
    }

    /**
     * Edita los datos del usuario
     * @param  integer $id    El ID de la persona o del usuario (es la misma 1-1)
     * @param  string $campo El campo a editar
     * @param  string $valor El nuevo valor
     * @param  string $tabla La tabla donde esta user/usr_persona
     * @return Array        Si se creo con exito
     */
    public function editar_usuario($id, $campo, $valor, $tabla='user')
    {
        $respuesta = array('msj'=>'no');
        $query = "UPDATE ".$tabla." SET ".$campo."='".$valor."' where _id=".$id;
        if($this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
        }
        else{
            $respuesta['error'] = 'El campo no se pudo modificar';
        }
        return $respuesta;
    }

    public function cambiar_password($id_user, $old_pass, $new_pass)
    {
        $respuesta = array('msj'=>'no');
        $this->libs->incluir_clase('includes/auth/Login.class.php');
        $old_pass = Login::desencriptar($old_pass);
        $query_old = "select _id from user where _id=".$id_user." and password='".$old_pass."' ";
        $stmt_old = $this->bd->ejecutar($query_old);
        $usuario = $this->bd->obtener_fila($stmt_old);
        if(!empty($usuario)){
            $respuesta = $this->editar_usuario($id_user, 'password', Login::desencriptar($new_pass));
        }
        else{
            $respuesta['error'] = 'La contraseña actual no coincide';
        }
        return $respuesta;
    }
}
?>