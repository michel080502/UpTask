<?php

require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\userController;
$insUsuario = new userController();

if (isset($_POST['nombre_usuario'])) {
    echo $insUsuario->confirmarUsuarioControlador();
}elseif ($_POST['modulo_equipo'] == "obtener_miembros_equipo") {
    echo $insUsuario->traerMiembrosEquipo();
} else {
    session_destroy();
    header("Location: " . APP_URL . "login/");
}
