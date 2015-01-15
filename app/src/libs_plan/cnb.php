<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('app/src/model/CnbCompetencia.class.php');
$libs->incluir_clase('app/src/model/CnbIndicador.class.php');
$libs->incluir_clase('app/src/model/CnbContenido.class.php');
$libs->incluir_clase('app/src/model/CnbRelContenido.class.php');
$libs->incluir_clase('app/src/model/CnbFunsepa.class.php');
$libs->incluir_clase('app/src/model/CnbMetodo.class.php');

$args = json_decode($_GET['filtros'], true);
echo json_encode(abrir_cnb($args, $_GET['tipo']));

/**
 * Carga el CNB desde la base de datos
 * @param  Array $filtros
 * @param  string $tipo    'object' o 'lista' para una lista por campo
 * @todo Habilitar el modo de objeto
 * @return Array
 */
function abrir_cnb($filtros=null, $tipo='lista')
{
    $cnb_competencia = new CnbCompetencia();
    $cnb_indicador = new CnbIndicador();
    $cnb_contenido = new CnbContenido();
    $cnb_rel_contenido = new CnbRelContenido();
    $cnb_funsepa = new CnbFunsepa();
    $cnb_metodo = new CnbMetodo();
    
    $arr_competencia = $cnb_competencia->abrir_competencia();
    $arr_indicador = $cnb_indicador->abrir_indicador();
    $arr_contenido = $cnb_contenido->abrir_contenido();
    $arr_rel_contenido = $cnb_rel_contenido->abrir_rel_contenido();
    $arr_funsepa = $cnb_funsepa->abrir_funsepa();
    
    return ($tipo=='object') ? $arr_competencia : array('arr_competencia'=>$arr_competencia, 'arr_indicador'=>$arr_indicador, 'arr_contenido'=>$arr_contenido, 'arr_rel_contenido'=>$arr_rel_contenido, 'arr_funsepa'=>$arr_funsepa, 'arr_metodo'=>$cnb_metodo->abrir_metodo());
}
?>