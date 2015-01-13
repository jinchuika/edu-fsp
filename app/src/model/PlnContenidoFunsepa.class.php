<?php
/**
* Controla la tabla pln_contenido_funsepa (contenido de funsepa para cada registro)
*/
class PlnContenidoFunsepa
{
	protected $bd;
	/**
	 * Establece la conexion a la base de datos
	 * @param Incluir $libs Objeto para incluir
	 */
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
	 * Crea un nuevo registro de pln_contenido_funsepa
	 * @param  integer $id_registro El registro al que se agregar el pln_contenido_funsepa
	 * @param  integer $id_funsepa   El id del pln_contenido_funsepa
	 * @return Array              {msj, _id}
	 */
	public function crear_pln_funsepa($id_registro, $id_funsepa=null)
	{
		$respuesta = array('msj'=>'no');
		if(is_array($id_funsepa)){
			$id_funsepa = implode("), (".$id_registro.",  ", $id_funsepa);
		}
		$query = "insert into pln_contenido_funsepa (id_registro, id_funsepa) values (".$id_registro.", ".$id_funsepa.")";
		if($this->bd->ejecutar($query)){
			$respuesta['msj'] = 'si';
			$respuesta['_id'] = $this->bd->lastID();
		}
		return $respuesta;
	}

	/**
	 * Elimina un registro de pln_contenido_funsepa basado en cualquier parámetro
	 * @param  string $campo El criterio para eliminar
	 * @param  string $valor El valor de ese campo
	 * @return boolean        si lo eliminó o no
	 */
	public function eliminar_pln_funsepa($campo, $valor)
	{
		$query = "delete from pln_contenido_funsepa where ".$campo."=".$valor;
		if($this->bd->ejecutar($query)){
			return true;
		}
	}
}
?>