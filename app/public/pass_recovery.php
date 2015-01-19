<?php
$nivel_dir = 2;
require_once("../src/core/incluir.php");
$libs = new Incluir($nivel_dir);

$libs->incluir_clase('includes/auth/Login.class.php');

if(isset($_GET['code'])){
	$codigo = explode('__', $_GET['code']);
	$login = new Login($libs);
	$string_valida = $login->validar_string_recovery($_GET['code']);
	
	if($string_valida['valid']==true){
		$menu = $libs->incluir('menu', array('nivel_dir'=>$nivel_dir));
	}
	else{
		header('Location: ../../');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Restablecer la contraseña</title>
	<?php
    $libs->incluir('html_template');
    ?>
</head>
<body>
	<?php echo $menu->imprimir(); ?>
	<div class="row-fluid">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<form class="form-horizontal well" id="form-recovery">
				<fieldset>
					<legend>Restablecer la contraseña</legend>
					<div class="form-group">
						<label class="col-md-4 control-label" for="password1">Nueva contraseña</label>
						<div class="col-md-5">
							<input minlength="6" id="password1" name="password1" type="password" placeholder="" class="form-control input-md" required="">
							<input type="hidden" name="username" id="username" value="<?php echo $string_valida['username']; ?>">
							<input type="hidden" name="salt" id="salt" value="<?php echo $codigo[0]; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="password2">Re-ingrese la contraseña</label>
						<div class="col-md-5">
							<input minlength="6" id="password2" name="password2" type="password" placeholder="" class="form-control input-md" required="">

						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="btn-enviar"></label>
						<div class="col-md-4">
							<button type="submit" id="btn-enviar" name="btn-enviar" class="btn btn-primary">Guardar</button>
						</div>
					</div>

				</fieldset>
			</form>

		</div>
	</div>
</body>
<?php
$libs->imprimir('js', 'app/js/auth/Auth.js');
$libs->imprimir('js', 'app/js/auth/pass_recovery.view.js');
?>
</html>