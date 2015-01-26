<?php
/**
 * Clase para acciones básicas
 */
class Controlador
{
    /**
     * Obtiene las variables del array $_POST o $_GET
     * @param  string  $nombre_var El apuntador de la variable
     * @param  boolean $json       Si está en JSON y debe ser decodificado
     * @return variable
     */
    public static function get_var($nombre_var='', $json=false)
    {
        if(!empty($_GET[$nombre_var]) && isset($_GET[$nombre_var])){
            return ($json==true ? json_decode($_GET[$nombre_var], true) : $_GET[$nombre_var]);
        }

        if(!empty($_POST[$nombre_var]) && isset($_POST[$nombre_var])){
            return ($json==true ? json_decode($_POST[$nombre_var], true) : $_POST[$nombre_var]);
        }
    }
}
?>