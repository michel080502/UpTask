<div class="container is-fluid mt-3 mb-4">
    <?php
    $idEquipo = htmlspecialchars($_POST['id']);
    $tareasEquipo = $insLogin->seleccionarTareasEquipo($idEquipo);
    $nombreEquipo = $insLogin->obtenerEquipoPorId($idEquipo)['nombre'];
    if ($tareasEquipo != null) {
    ?>
        <div class="container">
            <!-- Nombre del equipo -->
            <div class="mb-5">
                <h1 class="display-4"><?php echo $nombreEquipo ?></h1>
                <h2 class="h4">Visualiza las tareas pendientes o realizadas de tu equipo</h2>
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Tarea</th>
                        <th class="text-center">Fecha de Realización</th>
                        <th class="text-center">Responsable</th>
                        <th class="text-center">Estado</th>
                    </tr>
                    <?php
                    $contador = 1;
                    foreach ($tareasEquipo as $tarea) { ?>
                        <tr class="text-center">
                            <td><?php echo $contador ?></td>
                            <td><?php echo htmlspecialchars($tarea['datosTarea']['tarea'] ?? 'Sin nombre'); ?></td>
                            <td><?php echo htmlspecialchars($tarea['datosTarea']['fecha'] ?? 'Sin nombre'); ?></td>
                            <td><?php echo htmlspecialchars($tarea['datosTarea']['responsable'] ?? 'Sin nombre'); ?></td>
                            <?php if ($tarea['datosTarea']['estado'] == "Pendiente") {
                                echo '<td class="bg-warning">' . $tarea['datosTarea']['estado'] . '</td>';
                            } elseif ($tarea['datosTarea']['estado'] == "En progreso") {
                                echo '<td class="bg-info">' . $tarea['datosTarea']['estado'] . '</td>';
                            } elseif ($tarea['datosTarea']['estado'] == "Realizada") {
                                echo '<td class="bg-success">' . $tarea['datosTarea']['estado'] . '</td>';
                            } ?>
                        </tr>
                    <?php $contador++;
                    } ?>
                </thead>
            </table>
            <div class="row mt-4 justify-content-center">
                <div class="col-auto">
                    <a href="" class="btn btn-secondary btn-volver">Volver a mis equipos</a>
                </div>
                <div class="col-auto">
                    <a href="" class="btn btn-success btn-tarea">Crear una tarea</a>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="container mt-5 text-center">
            <h5 class="text-muted">Tu equipo aún no tiene tareas asignadas.</h5>
            <p>¿Por qué no creas una tarea nueva para tu equipo?</p>
            <a href="<?php echo APP_URL ?>userNew/" class="btn btn-success">Crear Tarea</a>
        </div>
    <?php } ?>
    <script type="text/javascript">
        let btn_back = document.querySelector(".btn-volver");
        let btn_tarea = document.querySelector(".btn-tarea");

        btn_back.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = "../userTeam/";
        });
        btn_tarea.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = "../userNew/";
        });
    </script>
</div>