<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$sesion = $libs->incluir('sesion');
$libs->incluir_clase('app/src/model/GnPlan.class.php');
$libs->incluir_clase('app/src/model/PlnRegistro.class.php');
$libs->incluir_clase('app/src/model/GnClase.class.php');

$fn_nombre = $_POST['fn_nombre'];
$args = json_decode($_POST['args'], true);

if($fn_nombre=='buscar_plan'){
    echo json_encode(nuevo_plan($args['anno'], $args['grado'], $args['carrera'], $sesion->get('id_user')));
}

if($fn_nombre=='abrir_plan'){
    echo json_encode(abrir_plan($_POST['id_plan']));
}

function nuevo_plan($anno, $grado, $carrera, $id_user)
{
    $respuesta = array('msj'=>'no');
    
    $clase = new GnClase();
    
    if(!$clase_actual = $clase->buscar_clase($anno, $carrera, $grado)){
        $clase_actual = $clase->crear_clase($anno, $carrera, $grado);
    }
    if($clase_actual){
        $plan = new GnPlan();
        $id_plan = $plan->buscar_plan(array('id_clase'=> $clase_actual['_id'], 'id_user'=>$id_user), "gn_plan._id, id_user, id_clase, public, gn_clase.id_grado ");
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
    $gn_plan = new GnPlan();
    $plan_actual = $gn_plan->buscar_plan(array('gn_plan._id'=> $id_plan), "gn_plan._id as _id, id_user, id_clase, public, gn_clase.id_grado ");
    $plan_actual['arr_registro'] = abrir_registro($plan_actual['_id']);
    return $plan_actual;
}

function abrir_registro($id_plan)
{
    $pln_registro = new PlnRegistro();
    $arr_registro = $pln_registro->abrir_registro(array('id_plan'=> $id_plan));
    for ($i=0; $i < count($arr_registro); $i++) { 
        $arr_registro[$i]['arr_funsepa'] = $pln_registro->abrir_contenido_funsepa(array('id_registro'=> $arr_registro[$i]['_id']), 'id_funsepa');
        $arr_registro[$i]['arr_metodo'] = $pln_registro->abrir_metodo(array('id_registro'=> $arr_registro[$i]['_id']), 'id_metodo');
    }
    foreach ($arr_registro as $registro_actual) {
        //array_push($registro_actual, $pln_registro->abrir_contenido_funsepa(array('id_registro'=> $registro_actual['_id'])));
        //$registro_actual['arr_funsepa'] = $pln_registro->abrir_contenido_funsepa(array('id_registro'=> $registro_actual['_id']));
        //$registro_actual['arr_metodo'] = $pln_registro->abrir_metodo(array('id_registro'=> $registro_actual['_id']));
    }
    return $arr_registro;
}
?>