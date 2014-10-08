<?php
class Login
{
	
	function __construct()
	{
		require_once("../../app/src/core/incluir.php");
		$nivel_dir = 2;
        $libs = new Incluir($nivel_dir);
		$this->bd = $libs->incluir('db');
	}

	/**
	 * Hace el log in
	 * @param  string $username
	 * @param  string $password
	 * @return [type]           [description]
	 */
	public function log_in($username, $password)
	{
		$query = "select _id from user where username='".$username."' AND password='".$this->desencriptar($password)."' ";
		$stmt = $this->bd->ejecutar($query);
		if($usuario = $this->bd->obtener_fila($stmt, 0)){
			$this->iniciar_sesion($usuario['_id']);
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * Consulta a la base de datos e inicia sesion
	 * @param  integer $id_user
	 */
	public function iniciar_sesion($id_user)
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
	private function desencriptar($decrypt_string='')
	{
		$arr_convert = explode("-", $decrypt_string);
		foreach ($arr_convert as $caracter) {
			if(!empty($caracter)){
				$key = explode(".", $caracter);
				$resultado .= chr((int)($key[0]) / ((int)($key[1]) + 1));
			}
		}
		return $resultado;
	}
}
?>