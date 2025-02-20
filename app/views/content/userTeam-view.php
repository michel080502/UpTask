<div class="container is-fluid mt-3 mb-4">
    <h1 class="display-4">Mis Equipos</h1>
    <h2 class="h4">Crea nuevos equipos o visualiza los que tienes</h2>
    <br>
</div>
<style>
    .card-custom {
        border: 3px solid #ced4da;
        /* Borde más grueso */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>

<?php

$equiposUsuario = $insLogin->seleccionarEquipos($_SESSION['usuario']);

if ($equiposUsuario != null) {
?>
    <div class="container pb-6 pt-6">
        <input type="hidden" name="modulo_usuario" id="modulo_usuario" value="listar_equipos">
        <input type="hidden" name="usuario_id" id="user_id" value="<?php echo $_SESSION['id']; ?>">
        <input type="hidden" name="usuario_nombre" id="user" value="<?php echo $_SESSION['usuario']; ?>">

        <div class="container mt-5">
            <div class="row">
                <!-- Card para crear un equipo -->
                <div class="col-md-4 mb-4">
                    <div class="card text-center card-custom" style="width: 100%; height: 15rem;">
                        <div class="card-body">
                            <h5 class="card-title">Crear un Equipo</h5>
                            <a href="<?php echo APP_URL ?>userCreateTeam/" class="btn btn-success mt-3">
                                <span class="mr-2">+</span> Crear
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Cards de los equipos -->
                <?php foreach ($equiposUsuario as $equipo) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center card-custom" style="width: 100%; height: 15rem;">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo htmlspecialchars($equipo['datosEquipo']['nombre'] ?? 'Sin nombre'); ?></h4>
                                <p class="card-text">Descripción: <?php echo htmlspecialchars($equipo['datosEquipo']['descripcion'] ?? 'Sin descripción'); ?></p>
                                <p>Integrantes: <?php echo count($equipo['datosEquipo']['integrantes']) ?></p>

                                <!-- Contenedor para los botones en una fila -->
                                <div class="d-flex justify-content-between mt-3">
                                    <!-- Formulario para enviar el id del equipo mediante GET -->
                                    <form action="<?php echo APP_URL . 'userUpdateTeam/'; ?>" method="post" class="me-2">
                                        <input type="hidden" name="id" value="<?php echo $equipo['idEquipo']; ?>">
                                        <button type="submit" class="btn btn-primary">Ver Equipo</button>
                                    </form>
                                    <!-- Formulario para enviar el id del equipo mediante GET -->
                                    <form action="<?php echo APP_URL . 'teamTasks/'; ?>" method="post" class="me-2">
                                        <input type="hidden" name="id" value="<?php echo $equipo['idEquipo']; ?>">
                                        <button type="submit" class="btn btn-warning">Ver Tareas</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php
} else { ?>
    <div class="container mt-5 text-center">
        <h5 class="text-muted">Aún no formas parte de ningún equipo.</h5>
        <p>¿Por qué no creas uno nuevo?</p>
        <a href="<?php echo APP_URL ?>userCreateTeam/" class="btn btn-success">Crear un Equipo</a>
    </div>
<?php
}
?>