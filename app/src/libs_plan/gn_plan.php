<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$sesion = $libs->incluir('sesion');
$libs->incluir_clase('app/src/model/GnPlan.class.php');
$libs->incluir_clase('app/src/model/GnClase.class.php');

$fn_nombre = $_POST['fn_nombre'];
$args = json_decode($_POST['args'], true);

if($fn_nombre=='buscar_plan'){
    echo json_encode(nuevo_plan($args['anno'], $args['grado'], $args['carrera'], $sesion->get('id_user')));
}

function nuevo_plan($anno, $grado, $carrera, $id_user)
{
    $respuesta = array('msj'=>'no');
    
    $clase = new GnClase();
    
    if(!$clase_actual = $clase->buscar_clase($anno, $grado, $carrera)){
        $clase_actual = $clase->crear_clase($anno, $grado, $carrera);
    }
    if($clase_actual){
        $plan = new GnPlan();
        $id_plan = $plan->buscar_plan(array('id_clase'=> $clase_actual['_id'], 'id_user'=>$id_user));
        if(!$id_plan){
            $id_plan = $plan->crear_plan($id_user, $clase_actual['_id']);
        }
        $respuesta['msj'] = (!empty($id_plan['_id'])) ? 'si' : 'no';
        $respuesta['_id'] = $id_plan['_id'];
    }
    
    return $respuesta;
}

function abrir_plan($id_plan)
{
    # code...
}
?>