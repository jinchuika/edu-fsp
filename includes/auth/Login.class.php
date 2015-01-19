<?php
class Login
{
    public $libs;
    function __construct($libs=null)
    {
        if(!($libs instanceof Incluir)){
            require_once('../../app/src/core/incluir.php');
            $nivel_dir = 2;
            $this->libs = new Incluir($nivel_dir);
        }else{
            $this->libs = $libs;
        }
        $this->bd = $this->libs->incluir('db');
    }

    /**
     * Hace el log in
     * @param  string $username
     * @param  string $password
     * @return boolean           Si puede o no
     */
    public function log_in($username, $password)
    {
        $this->libs->incluir_clase('app/src/model/User.class.php');
        $user = new User($this->libs);
        $salt_result = $user->abrir_usuario(array('username'=>$username), 'salt');

        $password = $this->desencriptar($password).$salt_result['salt'];
        $password = hash('sha256', $password);

        $usuario = $user->abrir_usuario(array('username'=>$username, 'password' => $password), 'user._id');
        if(!empty($usuario)){
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
        $this->libs->incluir_clase('app/src/model/User.class.php');
        $user = new User($this->libs);
        $campos = 'user._id, usr_persona._id as id_per, id_rol, username, nombre, apellido';
        $usuario = $user->abrir_usuario(array('user._id'=>$id_user), $campos);
        if(!empty($usuario)){
            $this->libs->incluir_clase('includes/auth/Sesion.class.php');
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
     * Crea un string para recuperar la contraseña
     * @param  string $mail Correo del usuario
     * @return string       La cadena para enviar por correo
     */
    public function crear_string_recovery($mail)
    {
        $this->libs->incluir_clase('app/src/model/User.class.php');
        $user = new User($this->libs);
        $datos_user = $user->abrir_usuario(array('mail'=>$mail), 'username, salt');
        if(!empty($datos_user) && $datos_user!==false){
            $llave = $datos_user['username'].'~'.date('m');
            $pass_oculto = $this->esconder_string($llave);

            return $datos_user['salt'].'__'.$pass_oculto;
        }
        else{
            return false;
        }
    }

    /**
     * Decodifica una string creado por crear_string_recovery() 
     * y valida que el usuario exista
     * @param  string $cadena La cadena a codificar
     * @return boolean         Si es válido o no
     */
    public function validar_string_recovery($cadena)
    {
        $this->libs->incluir_clase('app/src/model/User.class.php');
        $user = new User($this->libs);
        
        $partes = explode('__', $cadena);
        $llave = Login::desencriptar($partes[1]);
        $usuario = explode('~', $llave);
        
        $datos_user = $user->abrir_usuario(array('salt'=>$partes[0], 'username'=>$usuario[0]), 'username');
        if(!empty($datos_user) && $datos_user!==false){
            return array('valid'=>true, 'username'=>$usuario[0]);
        }
        else{
            return false;
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
    public static function encriptar($llave='', $random=true)
    {
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_RAND));
        $real_salt = ($random==true ? $llave.$salt : $llave);
        $stored_salt = hash('sha256', $real_salt);

        return array(
            'key'=>($random==true ? $salt : $llave),
            'string' => $stored_salt
            );
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

    
}
?>