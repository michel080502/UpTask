<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="<?php echo APP_URL ?>app/views/img/recordatorio.png" alt="Logo" width="30" height="30" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo APP_URL ?>dashboard/">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownTareas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Tareas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTareas">
                        <li><a class="dropdown-item" href="<?php echo APP_URL ?>userNew/">Nueva Tarea</a></li>
                        <li><a class="dropdown-item" href="<?php echo APP_URL ?>userList/">Mis Tareas</a></li>
                        <!-- <li><a class="dropdown-item" href="<?/*php echo APP_URL*/ ?>userSearch/">Buscar Tarea</a></li> -->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownTareas" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Equipos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTareas">
                        <li><a class="dropdown-item" href="<?php echo APP_URL ?>userTeam/">Mis Equipos</a></li>
                        <li><a class="dropdown-item" href="<?php echo APP_URL ?>userCreateTeam/">Nuevo Equipo</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION['usuario'] ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownUsuario">
                        <li><a class="dropdown-item" href="<?php echo APP_URL . "userUpdateRegister/" . $_SESSION['id'] . "/"; ?>">Mi Cuenta</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a  id="btn_exit" class="dropdown-item" href="<?php echo APP_URL ?>logOut/">Cerrar Sesion</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>