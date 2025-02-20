<div class="container is-fluid mt-3 mb-4">
    <?php
    $idEquipo = htmlspecialchars($_POST['id']);
    $datosEquipo = $insLogin->seleccionarDatos("Unico", "equipos", "", $idEquipo);
    if ($datosEquipo != null) {
        if ($datosEquipo['documento']['creador'] == $_SESSION['id']) { ?>
            <div class="container mb-3 position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="display-4">Mi Equipo</h1>
                    <form action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" class="FormularioAjaxTeamEliminar">
                        <input type="hidden" name="modulo_usuario" value="eliminar_equipo">
                        <input type="hidden" name="equipo_id" value="<?php echo $idEquipo; ?>">
                        <input type="hidden" name="usuario_equipo" value="<?php echo $datosEquipo['documento']['nombre'] ?>">
                        <button type="submit" class="btn btn-danger">Eliminar equipo</button>
                    </form>
                </div>
                <label class="h5 text-secondary">Actualiza tu equipo</label>
            </div>
            <div class="container pb-6 pt-6">
                <form class="FormularioAjaxTeamUpdate" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="modulo_usuario" value="actualizar_equipo">
                    <input type="hidden" name="equipo_id" value="<?php echo $idEquipo; ?>">

                    <div class="form-group mb-4">
                        <label for="usuario_equipo" class="h5">Nombre</label>
                        <input class="form-control" type="text" name="usuario_equipo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datosEquipo['documento']['nombre'] ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="usuario_equipo_descripción" class="h5">Descripción del equipo</label>
                        <textarea class="form-control" name="usuario_equipo_descripción" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*" maxlength="250" required rows="2"><?php echo $datosEquipo['documento']['descripcion'] ?></textarea>
                    </div>

                    <!-- Sección de Integrantes y Roles -->
                    <div class="form-group mb-5">
                        <label class="h5">Integrantes y Roles:</label>
                        <div id="integrantes_editables">
                            <?php foreach ($datosEquipo['documento']['integrantes'] as $nombreIntegrante => $rol) { ?>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <input type="text" class="form-control integrante-nombre" name="" value="<?php echo htmlspecialchars($nombreIntegrante); ?>" readonly>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control integrante-rol" data-nombre="<?php echo htmlspecialchars($nombreIntegrante); ?>" value="<?php echo htmlspecialchars($rol); ?>" placeholder="Rol del integrante" required>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Sección para agregar un nuevo integrante -->
                    <div class="form-group mb-4">
                        <label for="usuario_equipo_integrantes" class="h5">Agregar Integrantes</label><br>
                        <input type="text" class="form-control mb-2" id="integrante_input" placeholder="Escribe el nombre de usuario" name="usuario_equipo_integrantes">
                        <button type="button" class="btn btn-primary" id="agregar_integrante_btn">Agregar usuario</button>
                    </div>
                    <div class="form-group mb-4">
                        <label class="h5">Integrantes agregados:</label>
                        <ul class="list-group" id="integrantes_list">
                        </ul>
                    </div>

                    <div class="row mt-3 justify-content-center">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                        <div class="col-auto">
                            <a href="" class="btn btn-secondary btn-volver">Volver a mis equipos</a>
                        </div>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class="container pb-6 pt-6">

                <!-- Nombre del equipo -->
                <div class="mb-5">
                    <h1 class="display-4"><?php echo htmlspecialchars($datosEquipo['documento']['nombre']); ?></h1>
                </div>

                <!-- Descripción del equipo -->
                <div class="mb-5">
                    <label class="h5 text-dark">Descripción del equipo:</label>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($datosEquipo['documento']['descripcion'])); ?></p>
                </div>

                <!-- Integrantes del equipo con roles -->
                <div class="mb-5">
                    <label class="h5 text-dark">Integrantes y Roles:</label>
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th scope="col">Nombre del Integrante</th>
                                <th scope="col">Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datosEquipo['documento']['integrantes'] as $nombreIntegrante => $rol) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($nombreIntegrante); ?></td>
                                    <td><?php echo htmlspecialchars($rol); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-4 justify-content-center">
                    <form action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" class="FormularioAjaxTeamSalir col-auto">
                        <input type="hidden" name="modulo_usuario" value="salir_equipo">
                        <input type="hidden" name="nombre_usuario" value="<?php echo $_SESSION['usuario']; ?>">
                        <input type="hidden" name="equipo_id" value="<?php echo $idEquipo; ?>">
                        <button type="submit" class="btn btn-danger">Salir del equipo</button>
                    </form>
                    <div class="col-auto">
                        <a href="" class="btn btn-secondary btn-volver">Volver a mis equipos</a>
                    </div>
                </div>
            </div>

        <?php } ?>
        <br>
    <?php } else {
        include "./app/views/inc/error_alert.php";
    } ?>
    <script type="text/javascript">
        let btn_back = document.querySelector(".btn-volver");
        btn_back.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = "../userTeam/";
        });
    </script>
</div>