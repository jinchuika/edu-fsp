<?php
$nivel_dir = 3;
include '../core/incluir.php';
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('app/src/plugins/SimpleExcel/SimpleExcel.php');
?>