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
	<title>Importancia</title>
</head>
<body>
	<?php echo $menu->imprimir(); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<iframe width="800" height="491" src="http://www.powtoon.com/embed/deVLajq7xsX/" frameborder="0"></iframe>
			</div>
		</div>
	</div>
</body>
</html>