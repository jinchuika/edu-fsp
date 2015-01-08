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
        $query = '
        call crearUsuario("'.$args['nombre'].'", "'.$args['apellido'].'", '.$args['id_genero'].', '.$args['id_escuela'].',"'.$args['username'].'", "'.$args['password'].'", "'.$args['mail'].'")
        ';
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
            
            $codigo_activacion = $this->random_string()."-".$user['_id'];
            $query_activar = "insert into usr_activo (id_user, cadena) values (".$user['_id'].", '".$codigo_activacion."')";
            if($this->bd->ejecutar($query_activar, debug)){
                $respuesta['codigo'] = $codigo_activacion;
                //$respuesta['correo'] = ($this->enviar_correo($args['mail'], $codigo_activacion) == true) ? 'si' : 'no';
            }
            return $respuesta;
        }
    }
    
    public function random_string($len = 12){
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $random_string = "";
        for($i=0;$i<$len;$i++) {
            $random_string .= substr($str,rand(0,62),1);
        }
        return $random_string;
    }
    
    public function enviar_correo($mail, $random_string)
    {
        $header = "From: webmaster@funsepa.net \r\n";
        $header .= "Content-type: text/html\r\n";
        $mensaje = "<h1>Bienvenido</h1>";
        $mensaje .= "Recibió este mensaje porque se isncribió en el programa de escuelas Normales de FUNSEPA.  \r\n";
        $mensaje .= "Para activar su cuenta de usuario deberá hacer click en el siguiente enlace o copiarlo en la barra de direcciones de su navegador.<br> \r\n";
        $mensaje .= " http://funsepa.net/edu-dev/includes/usr_activo.php?codigo=".$random_string."\n";

        if(mail($mail, "Activación de usuario", $mensaje, $header)){
            return true;
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
        if(!empty($this->bd->obtener_fila($stmt_old))){
            $respuesta = $this->editar_usuario($id_user, 'password', Login::desencriptar($new_pass));
        }
        else{
            $respuesta['error'] = 'La contraseña actual no coincide';
        }
        return $respuesta;
    }
}
?>