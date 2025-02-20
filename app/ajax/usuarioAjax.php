<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\userController;

if (isset($_POST['modulo_usuario'])) {

    $insUsuario = new userController();

    if ($_POST['modulo_usuario'] == "registrar") {
        echo $insUsuario->registrarUsuarioControlador();
    } elseif ($_POST['modulo_usuario'] == "registrar_tarea") {
        echo $insUsuario->registarTareaControlador();
    } elseif ($_POST['modulo_usuario'] == "tarea_realizada") {
        echo $insUsuario->realizarTareaControlador();
    } elseif ($_POST['modulo_usuario'] == "actualizar_tarea") {
        echo $insUsuario->actualizarTareaControlador();
    } elseif ($_POST['modulo_usuario'] == "actualizar_usuario") {
        echo $insUsuario->actualizarUsuarioControlador();
    } elseif ($_POST['modulo_usuario'] == "registrar_equipo") {
        echo $insUsuario->registarEquipoControlador();
    } elseif ($_POST['modulo_usuario'] == "actualizar_equipo") {
        echo $insUsuario->actualizarEquipoControlador();
    }elseif ($_POST['modulo_usuario'] == "eliminar_equipo") {
        echo $insUsuario->eliminarEquipoControlador();
    }elseif ($_POST['modulo_usuario'] == "salir_equipo") {
        echo $insUsuario->salirEquipoControlador();
    }
} else {
    session_destroy();
    header("Location: " . APP_URL . "login/");
}
