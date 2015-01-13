<?php
/**
* Controla la tabla pln_metodo (métodos de evaluación)
*/
class PlnMetodo
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
	 * Crea un nuevo registro de metodo
	 * @param  integer $id_registro El registro al que se agregar el metodo
	 * @param  integer $id_metodo   El id del metodo
	 * @return Array              {msj, _id}
	 */
	public function crear_pln_metodo($id_registro, $id_metodo=null)
	{
		$respuesta = array('msj'=>'no', 'arr_id'=>array());
		if(is_array($id_metodo)){
			$id_metodo = implode("), (".$id_registro.",  ", $id_metodo);
		}
		$query = "insert into pln_metodo (id_registro, id_metodo) values (".$id_registro.", ".$id_metodo.")";
		if($this->bd->ejecutar($query)){
			$respuesta['msj'] = 'si';
			array_push($respuesta['arr_id'], $this->bd->lastID());
		}
		return $respuesta;
	}

	/**
	 * Elimina un registro de pln_metodo basado en cualquier parámetro
	 * @param  string $campo El criterio para eliminar
	 * @param  string $valor El valor de ese campo
	 * @return boolean        si lo eliminó o no
	 */
	public function eliminar_pln_metodo($campo, $valor)
	{
		$query = "delete from pln_metodo where ".$campo."=".$valor;
		if($this->bd->ejecutar($query)){
			return true;
		}
	}
}
?>