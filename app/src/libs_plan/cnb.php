<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('app/src/model/CnbCompetencia.class.php');
$libs->incluir_clase('app/src/model/CnbIndicador.class.php');
$libs->incluir_clase('app/src/model/CnbContenido.class.php');

$args = json_decode($_GET['filtros'], true);
echo json_encode(abrir_cnb($args));

function abrir_cnb($filtros=null)
{
    $cnb_competencia = new CnbCompetencia();
    $cnb_indicador = new CnbIndicador();
    $cnb_contenido = new CnbContenido();
    
    $arr_competencia = array();
    foreach ($cnb_competencia->abrir_competencia($filtros) as $competencia) {
        $competencia['arr_indicador'] = array();
        foreach ($cnb_indicador->abrir_indicador(array('id_competencia'=>$competencia['_id'])) as $indicador) {
            $indicador['arr_contenido'] = array();
            foreach ($cnb_contenido->abrir_contenido(array('id_indicador'=>$indicador['_id'])) as $contenido) {
                array_push($indicador['arr_contenido'], $contenido);
            }
            array_push($competencia['arr_indicador'], $indicador);
        }
        array_push($arr_competencia, $competencia);
    }
    
    return $arr_competencia;
}
?>