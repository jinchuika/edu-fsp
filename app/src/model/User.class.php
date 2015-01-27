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
     * @param  Array  $args      Los parámetros opcionales
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

    /**
     * Abre los datos del usuario y persona
     * @param  Array  $filtros {campo, valor}
     * @param  string $campos  Los campos que pide
     * @return Array|boolean
     */
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

    /**
     * Modifica el password del usuario
     * @param  string $id_user  id del usuario a modificar
     * @param  string $old_pass password antiguo (encriptado)
     * @param  string $new_pass password nuevo (encriptado)
     * @uses Login Para encriptar y desencriptar
     * @return Array           {msj}
     */
    public function cambiar_password($id_user, $old_pass, $new_pass)
    {
        $respuesta = array('msj'=>'no');
        $this->libs->incluir_clase('includes/auth/Login.class.php');

        $query_salt = "select salt from user where _id=".$id_user;
        $stmt_salt = $this->bd->ejecutar($query_salt);
        $salt = $this->bd->obtener_fila($stmt_salt);
        $password = Login::desencriptar($old_pass).$salt['salt'];
        $password = hash('sha256', $password);

        $query_old = "select _id from user where _id=".$id_user." and password='".$password."' ";
        $stmt_old = $this->bd->ejecutar($query_old);
        $usuario = $this->bd->obtener_fila($stmt_old);
        if(!empty($usuario)){
            $nuevo = Login::encriptar(Login::desencriptar($new_pass));
            $respuesta = $this->editar_usuario($id_user, 'password', $nuevo['string']);
            $respuesta = $this->editar_usuario($id_user, 'salt', $nuevo['key']);
            $respuesta['msj'] = 'si';
        }
        else{
            $respuesta['error'] = 'La contraseña actual no coincide';
        }
        return $respuesta;
    }

    /**
     * Devuelve los datos del passwor de forma oculta
     * @param  string $mail El mail del usuario
     * @return Array       {encriptado(username), mail, salt}
     */
    public function datos_password($mail)
    {
        $respuesta = array('msj'=>'no');
        $arr_encriptado = array();
        $this->libs->incluir_clase('includes/auth/Login.class.php');

        $usuario = $this->abrir_usuario(array('mail'=>$mail), 'username, mail, salt');

        if(!empty($usuario)){
            $usuario['username'] = Login::esconder_string($usuario['username']);
        }
        return $usuario;
    }

    public function nuevo_password($username, $new_pass, $salt)
    {
        $this->libs->incluir_clase('includes/auth/Login.class.php');
        $respuesta = array('msj' => 'no');
        $usuario = $this->abrir_usuario(array('username'=>$username, 'salt'=>$salt), 'user._id');
        if(!empty($usuario)){
            $nuevo = Login::encriptar(Login::desencriptar($new_pass));
            $respuesta = $this->editar_usuario($usuario['_id'], 'password', $nuevo['string']);
            $respuesta = $this->editar_usuario($usuario['_id'], 'salt', $nuevo['key']);
            $respuesta = array('msj'=> 'si', 'username' => $username);
        }
        return $respuesta;
    }
}
?>