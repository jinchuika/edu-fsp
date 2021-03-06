<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$sesion = $libs->incluir('sesion');
$libs->incluir_clase('app/src/model/GnPlan.class.php');
$libs->incluir_clase('app/src/model/PlnRegistro.class.php');
$libs->incluir_clase('app/src/model/GnClase.class.php');
$libs->incluir_clase('app/src/model/GnEscuela.class.php');
$libs->incluir_clase('app/src/model/User.class.php');

$libs->incluir_clase('includes/auth/Db.class.php');
$libs->incluir_clase('includes/auth/Conf.class.php');

$fn_nombre = !empty($_POST['fn_nombre']) ? $_POST['fn_nombre'] : $_GET['fn_nombre'];
!empty($_POST['args']) ? $args = json_decode($_POST['args'], true) : json_decode($_GET['args'], true);

if($fn_nombre=='buscar_plan'){
    echo json_encode(nuevo_plan($args['anno'], $args['grado'], $args['carrera'], $sesion->get('id_user')));
}

if($fn_nombre=='abrir_plan'){
    echo json_encode(abrir_plan($_POST['id_plan']));
}

if($fn_nombre=='crear_registro'){
    echo json_encode(crear_registro($_POST['id_plan'], $args));
}

if($fn_nombre=='borrar_registro'){
    echo json_encode(borrar_registro($_POST['id_registro']));
}

if($fn_nombre=='publicar_plan'){
    echo json_encode(publicar_plan($_POST['id_plan'], $_POST['tipo']));
}

if($fn_nombre=='abrir_info_plan'){
    echo json_encode(abrir_info_plan($_POST['id_plan']));
}

if($fn_nombre=='actualizar_metodo'){
    echo json_encode(actualizar_metodo($_POST['id_registro'], $args));
}

if($fn_nombre=='actualizar_funsepa'){
    echo json_encode(actualizar_funsepa($_POST['id_registro'], $args));
}

if($fn_nombre=='editar_registro'){
    echo json_encode(editar_registro($_POST['pk'], $_POST['name'], $_POST['value']));
}

if($fn_nombre=='editar_fecha'){
    echo json_encode(editar_fecha($_POST['pk'], $_POST['name'], $_POST['value']));
}

/**
 * Crea un nuevo plan o devuelve uno que tenga los mismos atributos
 * @param  integet $anno
 * @param  integet $grado
 * @param  integet $carrera
 * @param  integet $id_user
 * @return Array
 */
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

/**
 * Devuelve todos los datos de un plan y sus registros
 * @param  integer $id_plan
 * @uses abrir_registro()
 * @return Array
 */
function abrir_plan($id_plan)
{
    $gn_plan = new GnPlan();
    $clase = new GnClase();
    $usuario = new User();

    $plan_actual = $gn_plan->buscar_plan(array('gn_plan._id'=> $id_plan), "gn_plan._id as _id, id_user, id_clase, public, gn_clase.id_grado ");
    $clase_actual = $clase->abrir_clase(array('_id' => $plan_actual['id_clase']), 'id_anno');

    $plan_actual['id_anno'] = $clase_actual['id_anno'];
    $plan_actual['arr_registro'] = abrir_registro($plan_actual['_id']);
    return $plan_actual;
}

/**
 * Devuelve todos los registros para un plan indicado
 * @param  integer $id_plan
 * @return Array
 */
function abrir_registro($id_plan)
{
    $pln_registro = new PlnRegistro();
    $arr_registro = $pln_registro->abrir_registro(array('id_plan'=> $id_plan));
    for ($i=0; $i < count($arr_registro); $i++) { 
        $arr_registro[$i]['arr_funsepa'] = $pln_registro->abrir_contenido_funsepa(array('id_registro'=> $arr_registro[$i]['_id']), 'id_funsepa');
        $arr_registro[$i]['arr_metodo'] = $pln_registro->abrir_metodo(array('id_registro'=> $arr_registro[$i]['_id']), 'id_metodo');
    }
    return $arr_registro;
}

/**
 * Crea un nuevo registro para un plan
 * @param  integer $id_plan
 * @param  Array  $args
 * @return Array
 */
function crear_registro($id_plan, Array $args)
{
    $pln_registro = new PlnRegistro();
    $args['id_plan'] = $id_plan;
    $registro_nuevo = $pln_registro->crear_registro($args);
    return $pln_registro->abrir_registro(array('_id'=>$registro_nuevo['_id']), true);
}

function borrar_registro($id_registro)
{
    $pln_registro = new PlnRegistro();
    return $pln_registro->borrar_registro($id_registro);
}

function publicar_plan($id_plan, $tipo=1)
{
    $gn_plan = new GnPlan();
    return $gn_plan->publicar_plan($id_plan, $tipo);
}

function abrir_info_plan($id_plan)
{
    $gn_plan = new GnPlan();
    $clase = new GnClase();
    $usuario = new User();
    $escuela = new GnEscuela();

    $plan_actual = $gn_plan->buscar_plan(array('gn_plan._id'=> $id_plan), "gn_plan._id as _id, id_user, id_clase ");
    $plan_actual['clase'] = $clase->abrir_clase(array('gn_clase._id'=>$plan_actual['id_clase']), ' grado, carrera, anno', 'inner join cl_anno on cl_anno._id=gn_clase.id_anno
        inner join cl_grado on cl_grado._id=gn_clase.id_grado
        inner join cl_carrera on cl_carrera._id=gn_clase.id_carrera');
    $plan_actual['usuario'] = $usuario->abrir_usuario(array('user._id'=>$plan_actual['id_user']), 'nombre, apellido, id_escuela');
    $plan_actual['escuela'] = $escuela->abrir_escuela(array('gn_escuela._id'=>$plan_actual['usuario']['id_escuela']));
    return $plan_actual;
}

function actualizar_metodo($id_registro, $arr_metodo)
{
    $respuesta = array('msj' => 'no');
    $pln_registro = new PlnRegistro();
    $actualizado = $pln_registro->actualizar_metodo($id_registro, $arr_metodo);
    $respuesta['arr_contenido_td'] = $arr_metodo;
    return $respuesta;
}

function actualizar_funsepa($id_registro, $arr_funsepa)
{
    $respuesta = array('msj' => 'no');
    $pln_registro = new PlnRegistro();
    $actualizado = $pln_registro->actualizar_funsepa($id_registro, $arr_funsepa);
    $respuesta['arr_contenido_td'] = $arr_funsepa;
    return $respuesta;
}

function editar_registro($id_registro, $campo, $value='')
{
    $pln_registro = new PlnRegistro();
    $respuesta = array('msj' => 'no');
    if($pln_registro->editar_registro($id_registro, $campo, $value)){
        $respuesta['msj'] = 'si';
    }
    return $respuesta;
}

function editar_fecha($id_registro, $campo, $value='')
{
    $pln_registro = new PlnRegistro();
    $respuesta = array('msj' => 'no');
    if($pln_registro->editar_registro($id_registro, $campo, implode("-",array_reverse(explode("/",$value))))){
        $respuesta['msj'] = 'si';
    }
    return $respuesta;
}
?>