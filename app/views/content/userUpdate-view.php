<div class="container is-fluid mt-3 mb-4">
    <h1 class="display-4">Mi Tarea</h1>
    <h2 class="h4">Actualiza la información de tu tarea</h2>
</div>
<div class="container pb-6 pt-6">
    <?php
    $idTarea = $_POST['id_tarea'];

    $datos = $insLogin->seleccionarDatos("Unico", "tareas", "id_tarea", $idTarea);

    if ($datos != null) {
    ?>
        <div class="container pb-6 pt-6">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" name="modulo_usuario" value="actualizar_tarea">
                <input type="hidden" name="id_tarea" value="<?php echo $idTarea; ?>">

                <div class="form-group mb-4">
                    <label for="usuario_tarea">Tarea</label>
                    <textarea class="form-control" name="usuario_tarea" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*" maxlength="250" required rows="3"><?php echo $datos['documento']['tarea'] ?></textarea>
                </div>

                <div class="form-group mb-4">
                    <label for="miembro_id">Estado</label>
                    <select class="form-control" name="usuario_tarea_estado" required>
                        <option value="<?php echo $datos['documento']['estado'] ?>"><?php echo $datos['documento']['estado'] ?></option>
                        <?php echo mostrarEstado($datos['documento']['estado']); ?>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="usuario_tarea">Fecha de realización</label><br>
                    <input type="date" class="form-control" name="usuario_tarea_fecha" value="<?php echo $datos['documento']['fecha'] ?>" required>
                </div>
                <div class="row mt-3 justify-content-center">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                    <div class="col-auto">
                        <a href="" class="btn btn-secondary btn-volver">Volver a mis tareas</a>
                    </div>
                </div>
            </form>
        </div>
    <?php
    } else {
        include "./app/views/inc/error_alert.php";
    }
    ?>
</div>
<script type="text/javascript">
    let btn_back = document.querySelector(".btn-volver");
    btn_back.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = "../userList/";
    });
</script>
<?php
function mostrarEstado($estado)
{
    $option = '';
    if ($estado == "Pendiente") {
        return '<option value="En progreso">En progreso</option>';
    } else {
        return '<option value="Pendiente">Pendiente</option>';
    }
}
?>