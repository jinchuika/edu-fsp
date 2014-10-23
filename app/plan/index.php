<?php
$nivel_dir = 2;
require_once("../src/core/incluir.php");
$libs = new Incluir($nivel_dir);

$sesion = $libs->incluir('sesion');
$sesion->validar_acceso();
$bd = $libs->incluir('db');

$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir, 'sesion'=>$sesion));
$menu_exportar = $menu->add('Exportar plan', array('url'=>'#', 'class' => 'export'));
$menu_exportar->add('Excel', array('url'=>'#', 'id'=>'btn_export', 'class' => 'export'));
$menu_exportar->add('Publicar', array('url'=>'javascript:void(0)', 'id'=>'btn_public', 'externo'=>true));

$libs->incluir_clase('app/src/model/ClAnno.class.php');
$libs->incluir_clase('app/src/model/ClCarrera.class.php');
$libs->incluir_clase('app/src/model/ClGrado.class.php');

$cl_anno = new ClAnno($bd);
$cl_carrera = new ClCarrera($bd);
$cl_grado = new ClGrado($bd);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Educación FUNSEPA</title>
    <?php
    $libs->incluir('html_template');
    $libs->incluir('html_plan');
    $libs->incluir('cnb_js');
    ?>
</head>
<body>
    <?php echo $menu->imprimir(); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form id="form_clase" class="form-inline well">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="anno">Año</label>
                            <div class="col-md-4">
                                <select id="anno" name="anno" class="form-control col-sm-12">
                                    <?php
                                    foreach ($cl_anno->listar_anno() as $key => $anno) {
                                        echo '<option value="'.$anno['_id'].'">'.$anno['anno'].'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="carrera">Carrera</label>
                            <div class="col-md-6">
                                <select id="carrera" name="carrera" class="form-control col-sm-12">
                                    <?php
                                    foreach ($cl_carrera->listar_carrera() as $key => $carrera) {
                                        echo '<option value="'.$carrera['_id'].'">'.$carrera['carrera'].'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="grado">Grado</label>
                            <div class="col-md-4">
                                <select id="grado" name="grado" class="form-control col-sm-12">
                                    <?php
                                    foreach ($cl_grado->listar_grado() as $key => $grado) {
                                        echo '<option data-descripcion="'.$grado['grado'].'" data-id_carrera="'.$grado['id_carrera'].'" value="'.$grado['_id'].'">'.$grado['grado'].'</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="btn_clase"></label>
                            <div class="col-md-4">
                                <button type="submit" id="btn_clase" name="btn_clase" class="btn btn-primary">Abrir plan</button>
                                <button type="button" id="btn_registro" name="btn_registro" class="btn btn-success">Nuevo registro</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table id="tabla_plan" class="table table-hover table-condensed table-bordered well">
                    <thead>
                        <tr>
                            <th>Fecha <span class="label label-warning lbl_public" style="display: none;">Público</span></th>
                            <th>Contenido MINEDUC <span class="label label-warning lbl_public" style="display: none;">Público</span></th>
                            <th>Contenido FUNSEPA <span class="label label-warning lbl_public" style="display: none;">Público</span></th>
                            <th>Actividad <span class="label label-warning lbl_public" style="display: none;">Público</span></th>
                            <th>Recurso <span class="label label-warning lbl_public" style="display: none;">Público</span></th>
                            <th>Método <span class="label label-warning lbl_public" style="display: none;">Público</span></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_plan">

                    </tbody>
                </table>
            </div>
        </div>
        <form class="form-horizontal" style="display: none;" id="form_registro">
            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_fecha">Fecha</label>  
                    <div class="col-md-4">
                        <input id="n_fecha" name="n_fecha" placeholder="DD/MM/AAAA" class="form-control input-md datepicker" required="" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_comp">Competencia</label>
                    <div class="col-md-5">
                        <select id="n_comp" name="n_comp" class="form-control select_cnb">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_indicador">Indicador de logro</label>
                    <div class="col-md-5">
                        <select id="n_indicador" name="n_indicador" class="form-control select_cnb">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_contenido">Contenido CNB</label>
                    <div class="col-md-5">
                        <select id="n_contenido" name="n_contenido" class="form-control select_cnb" required="">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_funsepa">Contenido FUNSEPA</label>
                    <div class="col-md-5">
                        <select id="n_funsepa" name="n_funsepa" class="form-control select_cnb" multiple="multiple">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_metodo">Métodos de evaluación</label>
                    <div class="col-md-5">
                        <select id="n_metodo" name="n_metodo" class="form-control select_cnb" multiple="multiple">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_actividad">Actividades</label>
                    <div class="col-md-4">                     
                        <textarea class="form-control" id="n_actividad" name="n_actividad"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="n_recursos">Recursos</label>
                    <div class="col-md-4">                     
                        <textarea class="form-control" id="n_recursos" name="n_recursos"></textarea>
                    </div>
                </div>
                <input type="submit" class="btn btn-success">
            </fieldset>
        </form>
    </div>
</body>
<?php $libs->imprimir('js', 'app/js/plan/index.js'); ?>
<script>
<?php
$id_publica = $_GET['id'];
if(!empty($id_publica)){
    $libs->incluir_clase('app/src/model/GnPlan.class.php');
    $libs->incluir_clase('app/src/model/GnClase.class.php');

    $gn_plan = new GnPlan($bd);
    $gn_clase = new GnClase($bd);

    $plan_actual = $gn_plan->buscar_plan(array('gn_plan._id'=>$id_publica, 'public'=>1), 'gn_plan._id, id_clase');
    if($plan_actual){
        $clase_actual = $gn_clase->abrir_clase(array('_id'=>$plan_actual['id_clase']));

        echo "$('#anno').val(".$clase_actual['id_anno'].");
        ";
        echo "$('#carrera').val(".$clase_actual['id_carrera'].");
        ";
        echo "$('#grado').val(".$clase_actual['id_grado'].");
        ";
        echo "abrir_plan(".$plan_actual['_id'].", true);";
        echo "console.log(".$clase_actual['id_anno'].");";
        echo 'console.log('.$plan_actual['id_clase'].');';
    }
}
?>
</script>
</html>