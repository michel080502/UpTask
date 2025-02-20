<div class="container is-fluid mt-3 mb-4">
	<h1 class="display-4">Mis Tareas</h1>
	<h2 class="h4">Lista de todas tus tareas</h2>
	<br>
</div>
<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<?php
	$tareasUsuario = $insLogin->seleccionarTareasUsuario($_SESSION['usuario']);
	if ($tareasUsuario != null) {
	?>
		<div class="container">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Tarea</th>
						<th class="text-center">Fecha de Realización</th>
						<th class="text-center">Equipo</th>
						<th class="text-center">Responsable</th>
						<th class="text-center">Estado</th>
						<th class="text-center" colspan="2">Opciones</th>
					</tr>
					<?php
					$contador = 1;
					foreach ($tareasUsuario as $tarea) { ?>
						<tr class="text-center">
							<td><?php echo $contador ?></td>
							<td><?php echo htmlspecialchars($tarea['datosTarea']['tarea'] ?? 'Sin nombre'); ?></td>
							<td><?php echo htmlspecialchars($tarea['datosTarea']['fecha'] ?? 'Sin nombre'); ?></td>
							<td><?php echo htmlspecialchars($tarea['nombreEquipo'] ?? 'Sin nombre'); ?></td>
							<td><?php echo htmlspecialchars($tarea['datosTarea']['responsable'] ?? 'Sin nombre'); ?></td>
							<?php if ($tarea['datosTarea']['estado'] == "Pendiente") {
								echo '<td class="bg-warning">' . $tarea['datosTarea']['estado'] . '</td>';
							} elseif ($tarea['datosTarea']['estado'] == "En progreso") {
								echo '<td class="bg-info">' . $tarea['datosTarea']['estado'] . '</td>';
							} ?>
							<td>
								<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">
									<input type="hidden" name="modulo_usuario" value="tarea_realizada">
									<input type="hidden" name="id_tarea" value="<?php echo $tarea['idTarea']; ?>">
									<button type="submit" class="btn btn-success btn-rounded btn-sm">Realizada</button>
								</form>
							</td>
							<td>
								<form class="" action="<?php echo APP_URL; ?>userUpdate/" method="POST" autocomplete="off">
									<input type="hidden" name="modulo_usuario" value="tarea_realizada">
									<input type="hidden" name="id_tarea" value="<?php echo $tarea['idTarea']; ?>">
									<button type="submit" class="btn btn-primary btn-rounded btn-sm">Actualizar</button>
								</form>
							</td>
						</tr>
					<?php $contador++;
					} ?>
				</thead>
			</table>
		</div>
	<?php
	} else { ?>
		<div class="container mt-5 text-center">
			<h5 class="text-muted">Aún no tienes tareas pendientes asignadas.</h5>
			<p>¿Por qué no creas una tarea nueva para ti?</p>
			<a href="<?php echo APP_URL ?>userNew/" class="btn btn-success">Crear un Equipo</a>
		</div>
	<?php
	}
	?>
</div>