<?php
/**
 * @uses Db     El motor de la DB
 * @uses Conf   la condiguración del servidor
 */
class Incluir
{       
    var $nivel_entrada = 0;
    var $camino_relativo = '';
    private $lista_incluido = array();
    
    /**
     * Crea el objeto para incluir
     * @param integer $nivel_entrada La cantidad de carpetas hasta la raíz
     */
    function __construct($nivel_entrada=0)
    {
        $this->nivel_entrada = $nivel_entrada;
        for ($i=0; $i < $nivel_entrada; $i++) { 
            $this->nivel .= "../";
        }
    }
    
    /**
     * Imprime el código necesario para incluir una librería
     * @param  string $tipo_archivo   Para saber qué se debe imprimir
     * @param  string $nombre_archivo El nombre SIN EXTENSIÓN
     * @param  Array $extra          parámetros extra para casos específicos
     * @return bool|Object
     */
    public function incluir($nombre_archivo, $extra=null)
    {
        switch ($nombre_archivo) {
            case 'jquery':
                $this->imprimir('js', 'fw/js/jquery.min.js');
                break;
            case 'general':
                $this->imprimir('js', 'app/src/core/general.js', array('nivel_entrada'=>$this->nivel, 'id'=>'js_general'));
                break;
            case 'bs':
                $this->imprimir('meta', 'name="viewport" content="width=device-width, initial-scale=1"');
                $this->imprimir('css', 'fw/bs/css/bootstrap.min.css');
                $this->incluir('jquery');
                $this->imprimir('js', 'fw/bs/js/bootstrap.min.js');
                break;
            case 'menu':
                $this->incluir_clase('app/src/core/link.php');
                $this->incluir_clase('app/src/core/item.php');
                $this->incluir_clase('app/src/core/menu.php');
                return new Menu($extra['nivel_dir']);
                break;
            case 'db':
                $this->incluir_clase('includes/auth/Conf.class.php');
                $this->incluir_clase('includes/auth/Db.class.php');
                return Db::getInstance();
                break;
            case 'sesion':
                $this->incluir_clase('includes/auth/Sesion.class.php');
                return Sesion::getInstance();
                break;
            case 'html_template':
                $this->incluir('bs');
                $this->incluir('general');
                break;
            default:
                # code...
                break;
        }
    }
    
    /**
     * Imprime el texto HTML para incluir
     * @param  string $tipo    El tipo de erchivo
     * @param  string $archivo El archivo CON LA RUTA
     * @param  array $extra_param parámetros extra_param para el archivo
     */
    public function imprimir($tipo, $archivo, $extra_param=null)
    {
        if(!in_array($archivo, $this->lista_incluido)){
            $texto_extra = '';
            switch ($tipo) {
                case 'js':
                    if(is_array($extra_param)){
                        foreach ($extra_param as $key => $param) {
                            $texto_extra .= ' '.$key.'="'.$param.'" ';
                        }
                    }
                    echo '<script src="'.$this->nivel.$archivo.'" '.$texto_extra.' ></script>
                    ';
                    break;
                case 'css':
                    echo '<link href="'.$this->nivel.$archivo.'" rel="stylesheet"/>
                    ';
                    break;
                case 'meta':
                    echo '<meta '.$archivo.'>
                    ';
                    break;
                default:
                    # code...
                    break;
            }
            $this->agregar_lista($archivo);
        }
    }
    
    /**
     * incluye el archivo PHP donde esté una clase
     * @param  string $archivo [description]
     * @param  Array $extra   Parámetros para condiciones especiales
     * @return [type]          [description]
     */
    public function incluir_clase($archivo='', $extra=null)
    {
        if(!in_array($archivo, $this->lista_incluido)){
            require_once($this->nivel.$archivo);
            if($extra['nombre_clase']){
                return new $extra['nombre_clase']();
            }
            $this->agregar_lista($archivo);
        }
    }
    
    /**
     * Agrega el archivo incluido a la lista de inclusiones actuales
     * @param  string $nombre_archivo nombre del archivo a incluir
     */
    public function agregar_lista($nombre_archivo)
    {
        array_push($this->lista_incluido, $nombre_archivo);
    }
    
    /**
     * retorna la lista de archivos incluidos actualmente
     * @return array
     */
    public function get_lista()
    {
        return $this->lista_incluido;
    }
}
?>