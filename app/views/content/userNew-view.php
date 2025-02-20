<?php

$equiposUsuario = $insLogin->seleccionarEquipos($_SESSION['usuario']);

if ($equiposUsuario != null) {
?>
    <div class="container is-fluid mt-3 mb-4">
        <h1 class="display-4">Nueva Tarea</h1>
        <h2 class="h4">Llena la información de tu nueva tarea</h2>
        <br>
    </div>
    <div class="container pb-6 pt-6">
        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="modulo_usuario" value="registrar_tarea">
            <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['id']; ?>">

            <div class="form-group mb-4">
                <label for="usuario_tarea">Tarea</label>
                <textarea class="form-control" name="usuario_tarea" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*" maxlength="250" required rows="3"></textarea>
            </div>


            <div class="form-group mb-4">
                <label for="equipo_id">Equipo</label>
                <select class="form-control" name="equipo_id" id="equipo_id" required onchange="cargarMiembrosEquipo(this.value)">
                    <option value="">Seleccione un equipo</option>
                    <?php
                    foreach ($equiposUsuario as $equipo) {
                        echo '<option value="' . $equipo['idEquipo'] . '">' . $equipo['datosEquipo']['nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="miembro_id">Asignar a</label>
                <select class="form-control" name="miembro_equipo" id="miembro_id" required>
                    <option value="">Primero seleccione un equipo</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="usuario_tarea">Fecha de realización</label><br>
                <input type="date" class="form-control" name="usuario_tarea_fecha" required>
            </div>
            <div class="row mt-3 justify-content-center">
                <div class="col-auto">
                    <button type="reset" class="btn btn-outline-primary">Limpiar</button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
<?php
} else { ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="text-center">
            <h5 class="text-muted mb-3">Aún no formas parte de ningún equipo.</h5>
            <p class="mb-3">No puedes asignar tareas hasta que pertenezcas a un equipo.</p>
            <p class="mb-4">¿Por qué no creas uno nuevo?</p>
            <a href="<?php echo APP_URL ?>userCreateTeam/" class="btn btn-success">Crear un Equipo</a>
        </div>
    </div>
<?php
}
?>