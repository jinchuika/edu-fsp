<?php
require_once("Conf.class.php");
require_once("Db.class.php");

class Sesion {
    /**
     * La instancia actual para evitar clonacion
     * @var object
     */
    private static $instance;
    
    /**
     * Array de los permisos para cada área
     * @var array
     * @todo implementar
     */
    public $arr_permiso;
    
    
    private function __construct($id_user=null) {
        session_start ();
        if($id_user){
            $this->id_user = $id_user;
            $this->arr_permiso = $this->sync_remote(null,$id_user);
        }
        else{
            $this->id_user = $this->get("id_per");
            $this->arr_permiso = $this->sync_remote(null,$this->id_user);
        }
        
    }
    
    /**
     * Almacena una variable en la sesión actual
     * @param string $nombre
     */
    public function set($nombre, $valor) {
        $_SESSION [$nombre] = $valor;
    }
    
    /**
     * Obtiene una variable almacenada en la sesión actual
     * @param  string $nombre
     * @return string|bool
     */
    public function get($nombre) {
        if (isset ( $_SESSION [$nombre] )) {
            return $_SESSION [$nombre];
        } else {
            return false;
        }
    }
    
    /*Evitamos el clonaje del objeto. Patrón Singleton*/
    private function __clone(){ }

    private function __wakeup(){ }
    
    /**
     * Devuelve o crea la instancia actual
     * @param  integer $id_user
     * @return object
     */
    public static function getInstance($id_user=null){
        if (!(self::$instance instanceof self)){
            self::$instance=new self(self::get("id_per"));
        }
        else{
            if($id_user){
                self::sync_remote(null,self::get("id_per"));
            }
        }
        return self::$instance;
    }
    
    public function validar_acceso($url_origen='', $url_destino='')
    {
        $url_origen = (empty($url_origen)) ? 'http://funsepa.net/edu?' : $url_origen.'?';
        $url_origen .= (empty($url_destino)) ? 'destino='.$url_destino : '';
        
        if(!($this->get('id_user'))){
            header( 'Location: '.$url_origen );
        }
        else{
            return false;
        }
    }
    
    public function elimina_variable($nombre) {
        unset ( $_SESSION [$nombre] );
    }
    
    public function termina_sesion() {
        $_SESSION = array();
        session_destroy ();
        return true;
    }
    
    public function mostrar_permisos($id_fun=null, $id_user=null)
    {
        if($id_user==null){
            $this->arr_permiso["id_user"] = $this->get("id_per");
            return $id_fun == null ? $this->arr_permiso : ($this->arr_permiso[$id_fun]);
        }
        else{
            $respuesta = $this->sync_remote($id_fun, $id_user);
            return $respuesta[$id_fun];
        }
    }

    public function has($id_fun, $mask, $id_user=null)
    {
        if($id_user==null){
            return $this->arr_permiso[$id_fun] & $mask;
        }
        else{
            $temp = $this->sync_remote($id_fun, $id_user);
            return $temp[$id_fun] & $mask;
        }
    }

    public function give($id_fun, $mask, $id_user=null)
    {
        if($id_user==null){
            $this->arr_permiso[$id_fun] |= $mask;
            $this->sync_local($id_fun, $this->id_user, $mask);
            return ($this->arr_permiso[$id_fun]);
        }
        else{
            $perm = $this->sync_remote($id_fun, $id_user);
            $this->sync_local($id_fun, $id_user, $perm[$id_fun] | $mask);
        }
    }
    public function take($id_fun, $mask, $id_user=null)
    {
        if($id_user==null){
            $this->arr_permiso[$id_fun] &= ~$mask;
            $this->sync_local($id_fun, $this->id_user, $mask);
            return ($this->arr_permiso[$id_fun]);
        }
        else{
            $perm = $this->sync_remote($id_fun, $id_user);
            $this->sync_local($id_fun, $id_user, $perm[$id_fun] & ~$mask);
        }
    }

    public function sync_remote($id_fun=null, $id_user=null){
        
        $bd = Db::getInstance();
        /*
        * sincroniza la instancia actual desde el servidor
        * cuando $id_fun==null
        **** retorna un array completo para sincronizar todo
        * cuando $id_fun!=null && $id_ust!=null
        **** retorna un array con el permiso almacenado en $key = $id_fun
        */
        if($id_user==null || empty($id_user) || (!$id_user)){
            $id_user = $this->get("id_per");
        }
        if(!empty($id_user)){
            
            $query = "SELECT * FROM aut_permiso where id_user=".$id_user." ";
            if($id_fun){
                $query .= " and id_area=".$id_fun;
            }
            $stmt = $bd->ejecutar($query);
            while ($perm = $bd->obtener_fila($stmt, 0)) {
                $arr_temp[$perm["id_area"]] = $perm["permiso"];
            }
            return $arr_temp;
        }
    }

    private function sync_local($id_fun=null, $id_user=null, $permiso_in=null)
    {
        /*
        * sincroniza de local hacia el servidor
        * sincroniza todo el array actual si $id_fin=null
        */
        $this->bd = Db::getInstance();
        if($id_fun!==null){
            if($id_user==null){
                $id_user = $this->id_user;
                $permiso_in = $this->arr_permiso[$id_fun];
            }
            $query_select = "SELECT _id FROM aut_permiso where id_user=".$id_user." and id_area=".$id_fun;
            $stmt_select = $this->bd->ejecutar($query_select);
            if($select = $this->bd->obtener_fila($stmt_select, 0)){
                $query = "UPDATE aut_permiso SET permiso=".$permiso_in." where id=".$select["id"];
                $stmt = $this->bd->ejecutar($query);
            }
            else{
                $query = "INSERT INTO aut_permiso (id_user, id_area, permiso) VALUES ('".$id_user."', '".$id_fun."', '".$permiso_in."')";
                $stmt = $this->bd->ejecutar($query);
            }
        }
        else{
            foreach ($this->arr_permiso as $key => $permiso) {
                $query_select = "SELECT _id FROM aut_permiso where id_user=".$this->id_user." and id_area=".$key;
                $stmt_select = $this->bd->ejecutar($query_select);
                if($select = $this->bd->obtener_fila($stmt_select, 0)){
                    $query = "UPDATE aut_permiso SET permiso=".$permiso." where id=".$select["id"];
                    $stmt = $this->bd->ejecutar($query);
                }
                else{
                    $query = "INSERT INTO aut_permiso (id_user, id_area, permiso) VALUES ('".$this->id_user."', '".$key."', '".$permiso."')";
                    $stmt = $this->bd->ejecutar($query);
                }
            }
        }
    }
}
?>