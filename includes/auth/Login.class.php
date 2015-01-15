<?php
class Login
{
    
    function __construct($bd=null)
    {
        if(empty($bd)){
            require_once('../../app/src/core/incluir.php');
            $nivel_dir = 2;
            $libs = new Incluir($nivel_dir);
            $this->bd = $libs->incluir('db');
        }
        $this->bd = (!empty($bd)) ? $bd : $this->bd;
    }

    /**
     * Hace el log in
     * @param  string $username
     * @param  string $password
     * @return boolean           Si puede o no
     */
    public function log_in($username, $password)
    {
        $query_salt = "select salt from user where username='".$username."'";
        $stmt_salt = $this->bd->ejecutar($query_salt);
        $salt_result = $this->bd->obtener_fila($stmt_salt);

        $password = $this->desencriptar($password).$salt_result['salt'];
        $password = hash('sha256', $password);

        $query = "select _id from user where username='".$username."' AND password='".$password."' ";
        $stmt = $this->bd->ejecutar($query);
        if($usuario = $this->bd->obtener_fila($stmt, 0)){
            return array('valid'=>true, 'id_user'=>$usuario['_id']);
        }
        else{
            return array('valid'=>false);
        }
    }
    
    /**
     * Consulta a la base de datos e inicia sesion
     * @param  integer $id_user
     */
    public function crear_sesion($id_user)
    {
        $query = "
        select
        user._id,
        usr_persona._id as id_per,
        user.id_rol,
        user.username,
        usr_persona.nombre,
        usr_persona.apellido
        from user
        inner join usr_persona ON usr_persona._id = user._id
        where user._id =".$id_user;
        $stmt = $this->bd->ejecutar($query, true);
        if($usuario = $this->bd->obtener_fila($stmt, 0)){
            include 'Sesion.class.php';
            $sesion = Sesion::getInstance($usuario['_id']);
            $sesion->set("username",$usuario['username']);
            $sesion->set("nombre",$usuario['nombre']);
            $sesion->set("apellido",$usuario['apellido']);
            $sesion->set("mail",$usuario['mail']);
            $sesion->set("id_user",$usuario['_id']);
            $sesion->set("rol",$usuario['id_rol']);
            $sesion->set("id_per",$usuario['id_per']);
            $sesion->set("arr_permiso",$sesion->mostrar_permisos());
        }
    }
    
    /**
     * algoritmo para desencriptar contraseñas
     * @param  string $decrypt_string (ascii del caracter) / (random - 1)
     * @return string
     */
    public static function desencriptar($decrypt_string='')
    {
        $resultado = '';
        $arr_convert = explode("-", $decrypt_string);
        foreach ($arr_convert as $caracter) {
            if(!empty($caracter)){
                $key = explode(".", $caracter);
                $resultado .= chr((int)($key[0]) / ((int)($key[1]) + 1));
            }
        }
        return $resultado;
    }

    /**
     * Encripta una cadena
     * @param  string $llave La llave para añadir a la encriptacion
     * @return Array        {key, string}
     */
    public static function encriptar($llave='')
    {
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $real_salt = $llave.$salt;
        $stored_salt = hash('sha256', $real_salt);

        return array('key'=>$salt, 'string' => $stored_salt);
    }

    public static function esconder_string ($string) {
        $resultado = '';
        for ($i=0; $i < strlen($string); $i++) { 
            $llave = rand(1, 50) + 1;
            $numero = ord(substr($string, $i)) * $llave;
            $resultado .= '-'.$numero.'.'.($llave-1);
        }
        return $resultado;
    }

    public static function recuperar_password($user, $salt)
    {
        $string = $user.'__'.self::esconder_string(date('Y-m'));
        return $string;
    }
}
?>