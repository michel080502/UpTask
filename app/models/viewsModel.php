<?php

namespace app\models;

class viewsModel
{
    protected function obtenerVistasModelo($vista)
    {
        // Lista blanca de vistas permitidas
        $listaBlanca = ["dashboard", "userNew", "userList", "userSearch", "userUpdate", "logOut","userUpdateRegister","userTeam","userCreateTeam","userUpdateTeam","teamTasks"];
        // Comprobación si la vista está en la lista blanca
        if (in_array($vista, $listaBlanca)) {
            // Verificar si el archivo de vista existe
            if (is_file("./app/views/content/" . $vista . "-view.php")) {
                // Si existe, asignar la ruta del archivo a $contenido
                $contenido = "./app/views/content/" . $vista . "-view.php";
            } else {
                // Si no existe, mostrar la página de error 404
                $contenido = "404";
            }
        } elseif ($vista == "login" || $vista == "index") {
            // Si la vista es 'login', asignar 'login' a $contenido
            $contenido = "login";
        } elseif ($vista == "userRegister") {
            $contenido = "userRegister";
        } else {
            // Si la vista no está en la lista blanca ni es 'login', mostrar la página de error 404
            $contenido = "404";
        }
        // Devolver el contenido de la vista
        return $contenido;
    }
}
