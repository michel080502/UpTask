<div class="container is-fluid mt-3 mb-4">
	<?php
	$id = $insLogin->limpiarCadena($url[1]);
	?>
		<h1 class="display-4">Mi cuenta</h1>
		<h2 class="h4">Actualizar cuenta</h2>
</div>
<div class="container pb-6 pt-6">
	<?php

	include "./app/views/inc/btn_back.php";

	$datos = $insLogin->seleccionarDatos("Unico", "usuarios", "", $id);

	if ($datos != null) {
	?>

		<h2 class="display-4 text-center"><?php echo $datos['documento']['nombre'] . " " . $datos['documento']['apellido']; ?></h2>

		<p class="text-center pb-6"><?php echo "<strong>Usuario creado:</strong> " . date("d-m-Y  h:i:s A", strtotime($datos['documento']['creado'])) . " &nbsp; <strong>Usuario actualizado:</strong> " . date("d-m-Y  h:i:s A", strtotime($datos['documento']['actualizado'])); ?></p>

		<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">

			<input type="hidden" name="modulo_usuario" value="actualizar_usuario">
			<input type="hidden" name="usuario_id" value="<?php echo $datos['id']; ?>">

			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="usuario_nombre">Nombres</label>
						<input class="form-control" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['documento']['nombre']; ?>" required>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="usuario_apellido">Apellidos</label>
						<input class="form-control" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['documento']['apellido']; ?>" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="usuario_usuario">Usuario</label>
						<input class="form-control" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" value="<?php echo $datos['documento']['usuario']; ?>" required>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="usuario_email">Email</label>
						<input class="form-control" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['documento']['email']; ?>">
					</div>
				</div>
			</div>
			<br><br>
			<p class="text-center">
				Si desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
			</p>
			<br>
			<div class="row">
				<div class="col">
					<div class="form-group">
						<label for="usuario_clave_1">Nueva clave</label>
						<input class="form-control" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="usuario_clave_2">Repetir nueva clave</label>
						<input class="form-control" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
					</div>
				</div>
			</div>
			<br>
			<div class="row mt-3 justify-content-center">
				<div class="col-auto">
					<button type="submit" class="btn btn-success">Actualizar</button>
				</div>
			</div>
		</form>
	<?php
	} else {
		include "./app/views/inc/error_alert.php";
	}
	?>
</div>