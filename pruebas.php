<?php

use app\models\mainModel;

require "./app/models/mainModel.php";

$model = new mainModel();
$equipo_datos_reg = [
    'nombre' => "Hola",
];

/*$response = $model->seleccionarDatos("Unico", "tareas", "","-OAPdtsCrF9QBEayLDrA");
print_r($response['documento']['responsable']);*/
//$response = $model->eliminarTareasEquipo("tareas","-OA5bRFavycqt1XW1oRi");
$response1 = $model->obtenerEquipoPorId("-OA5bRFavycqt1XW1oRi");
echo json_encode($response['usuario'], JSON_PRETTY_PRINT);


