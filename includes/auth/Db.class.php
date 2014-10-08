<?php 
/* Clase encargada de gestionar las conexiones a la base de datos */
Class Db{
    public $servidor;
    private $usuario;
    private $password;
    private $base_datos;
    private $tipo;
    private $link;
    private $stmt;
    private $array;

    private static $_instance;

    /**
     * evita la iteración
     */
    private function __construct(){
        $this->setConexion();
        $this->conectar();
    }

    /**
     * Establece parámetros de conexión
     * @uses Conf La clase que mantiene la configuración
     */
    private function setConexion(){

        $conf = Conf::getInstance();
        $this->servidor=$conf->getHostDB();
        $this->base_datos=$conf->getDB();
        $this->usuario=$conf->getUserDB();
        $this->password=$conf->getPassDB();
        $this->tipo=$conf->getDBType();
    }

    /**
     * Se evita la clonación para usar patrón Singleton
     */
    private function __clone(){ }

    private function __wakeup(){ }

    /**
     * Crea la instancia de ser necesario
     * si ya hay una instancia del objeto devuelve esa instancia
     * @return object
     */
    public static function getInstance(){

        if (!(self::$_instance instanceof self)){

            self::$_instance=new self();
        }
        return self::$_instance;
    }

    /**
     * Realiza la conexión a la base de datos
     * @uses $this->tipo El tipo de base de datos al que se conecta
     * @see $this->link
     */
    private function conectar(){
        switch ($this->tipo){
            case 'mysql':
            $this->link=mysqli_connect($this->servidor, $this->usuario, $this->password);
            mysqli_select_db($this->link, $this->base_datos);
            @mysqli_query("SET NAMES 'utf8'");
            mysqli_set_charset($this->link, "utf8");
            break;
        }
    }

    /**
    * Ejecuta una consulta de MySQL
    * @param  string $sql
    * @param bool $debug Muestra el error de la base de datos
    * @return object mysqli_result
    */
    public function ejecutar($sql, $debug = false){
        $this->stmt=mysqli_query($this->link, $sql);
        if (mysqli_error($this->link) && $debug==true) {
            printf("Error de MySQL : %s\n", mysqli_error($this->link));
        }
        return $this->stmt;
    }

    /*Método para obtener una fila de resultados de la sentencia sql*/
    public function obtener_fila($stmt,$fila=0){
        if ($fila==0){
            $this->array=mysqli_fetch_assoc($stmt);
        }
        else{
            mysqli_data_seek($stmt,$fila);
            $this->array=mysqli_fetch_assoc($stmt);
        }
        return $this->array;
    }
    /**
     * Ejecuta el procedimiento almacenado mostrando el resultado de los selects
     * @param  mysqli_result $stmt El resultado de una consulta mediante ejecutar()
     * @uses free_result Para limpiar el buffer y permitir que las consultas sigan
     * @return Array       Resultado de los selects
     */
    public function ejecutar_procedimiento($stmt)
    {
        $this->array = mysqli_fetch_array($stmt);
        $this->free_result();
        return $this->array;
    }

    /**
     * Limpia el buffer tras un procedimiento
     */
    function free_result() {
        while (mysqli_more_results($this->link) && mysqli_next_result($this->link)) {
            $dummyResult = mysqli_use_result($this->link);
            if ($dummyResult instanceof mysqli_result) {
                mysqli_free_result($this->link);
            }
        }
    }

    /**
     * Devuelve el autogenerado de la última consulta
     * @return string el Primary key del último insert
     */
    public function lastID(){
        return mysqli_insert_id($this->link);
    }

    /**
     * Valida que el registro no exista ya en la tabla
     * @param  string $dato  Valor a buscar
     * @param  string $tabla Tabla donde se espera que esté
     * @param  string $campo El campo a buscar
     * @param  string $alias El nombre genérico del campo para mostrar si hay error
     * @return Array        Si existe y un mensaje de error, FALSE si no existe
     */
    function duplicados($dato, $tabla, $campo, $alias=''){
        $sql = "SELECT * FROM ".$tabla." WHERE ".$campo."='".$dato."'";
        $stmt = $this->ejecutar($sql);
        if ($x=$this->obtener_fila($stmt,0)){
            $respuesta = array('existe' => "1", 'error' => "El dato ".$alias." ya existe.");
            break;
        }
        else{
            $respuesta = false;
        }
        return $respuesta;
    }
} 
?>