<?php
$nivel_dir = 2;
require_once("../src/core/incluir.php");
$libs = new Incluir($nivel_dir);

$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir));

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php
	$libs->incluir('html_template');
	?>
	<meta charset="UTF-8">
	<title>Línea de tiempo</title>
</head>
<body>
	<?php echo $menu->imprimir(); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<iframe src='http://cdn.knightlab.com/libs/timeline/latest/embed/index.html?source=0AkRHCvnBd2YBdG9SUWM4dUJENmxVQ0FmbWFjRDVHSUE&font=Bevan-PotanoSans&maptype=toner&lang=es&hash_bookmark=true&height=650' width='100%' height='650' frameborder='0'></iframe>
			</div>
		</div>
	</div>
</body>
</html>