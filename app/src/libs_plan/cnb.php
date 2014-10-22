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
    
    $arr_competencia = array();
    $arr_indicador = array();
    $arr_contenido = array();
    $arr_rel_contenido = array();
    $arr_funsepa = array();
        //obtener todas las competencias con el filtro
    foreach ($cnb_competencia->abrir_competencia($filtros) as $competencia) {
        $competencia['arr_indicador'] = array();
            //obtener todos los indicadores para el filtro (la competencia)
        foreach ($cnb_indicador->abrir_indicador(array('id_competencia'=>$competencia['_id'])) as $indicador) {
            $indicador['arr_contenido'] = array();
                //obtener todos los contenidos para el filtro (el indicador)
            foreach ($cnb_contenido->abrir_contenido(array('id_indicador'=>$indicador['_id'])) as $contenido) {
                $contenido['arr_rel_contenido'] = array();
                    //hace la relación con el contenido de funsepa
                foreach ($cnb_rel_contenido->abrir_rel_contenido(array('id_mineduc'=>$contenido['_id'])) as $key => $rel_contenido) {
                    $rel_contenido['arr_funsepa'] = array();
                        //obtiene el contenido de funsepa
                    foreach ($cnb_funsepa->abrir_funsepa(array('_id'=>$rel_contenido['id_funsepa'])) as $funsepa) {
                        ($tipo=='object') ? array_push($rel_contenido['arr_funsepa'], $funsepa) : $arr_funsepa[$funsepa['_id']] = $funsepa;
                    }
                    ($tipo=='object') ? array_push($contenido['arr_rel_contenido'], $rel_contenido) : $arr_rel_contenido[$rel_contenido['_id']] = $rel_contenido;
                }
                ($tipo=='object') ? array_push($indicador['arr_contenido'], $contenido) : $arr_contenido[$contenido['_id']] = $contenido;
            }
            ($tipo=='object') ? array_push($competencia['arr_indicador'], $indicador) : $arr_indicador[$indicador['_id']] = $indicador;
        }
        ($tipo=='object') ? array_push($arr_competencia, $competencia) : $arr_competencia[$competencia['_id']] = $competencia;
    }
    
    return ($tipo=='object') ? $arr_competencia : array('arr_competencia'=>$arr_competencia, 'arr_indicador'=>$arr_indicador, 'arr_contenido'=>$arr_contenido, 'arr_rel_contenido'=>$arr_rel_contenido, 'arr_funsepa'=>$arr_funsepa, 'arr_metodo'=>$cnb_metodo->abrir_metodo());
}
?>