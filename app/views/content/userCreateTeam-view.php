<div class="container is-fluid mt-3 mb-4">
    <h1 class="display-4">Nuevo Equipo</h1>
    <h2 class="h4">Llena la información de tu nuevo Equipo</h2>
    <br>
</div>

<div class="container pb-6 pt-6">
    <form class="FormularioAjaxTeam" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="modulo_usuario" value="registrar_equipo">
        <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['id']; ?>">
        <input type="hidden" name="usuario_nombre" value="<?php echo $_SESSION['usuario']; ?>">

        <div class="form-group mb-4">
            <label for="usuario_equipo">Nombre</label>
            <input class="form-control" type="text" name="usuario_equipo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
        </div>

        <div class="form-group mb-4">
            <label for="usuario_equipo_descripción">Descripción del equipo</label>
            <textarea class="form-control" name="usuario_equipo_descripción" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*" maxlength="250" required rows="2"></textarea>
        </div>

        <div class="form-group mb-4">
            <label for="usuario_equipo_integrantes">Integrantes</label><br>
            <input type="text" class="form-control mb-2" id="integrante_input" placeholder="Escribe el nombre de usuario" name="usuario_equipo_integrantes">
            <button type="button" class="btn btn-primary" id="agregar_integrante_btn">Agregar usuario</button>
        </div>
        <div class="form-group mb-4">
            <label>Integrantes agregados:</label>
            <ul class="list-group" id="integrantes_list">
            </ul>
        </div>

        <div class="row mt-3 justify-content-center">
            <div class="col-auto">
                <button type="reset" class="btn btn-outline-primary">Limpiar</button>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Crear</button>
            </div>
        </div>
    </form>
</div>