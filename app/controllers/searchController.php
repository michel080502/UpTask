<?php

namespace app\controllers;

use app\models\mainModel;

class searchController extends mainModel
{

    /*----------  Controlador modulos de busquedas  ----------*/
    public function modulosBusquedaControlador($modulo)
    {
        $listaModulos = ['userSearch'];

        if (in_array($modulo, $listaModulos)) {
            return false;
        } else {
            return true;
        }
    }


    /*----------  Controlador iniciar busqueda  ----------*/
    public function iniciarBuscadorControlador()
    {

        $url = $this->limpiarCadena($_POST['modulo_url']);
        $fecha = $this->limpiarCadena($_POST['date_buscador']);

        if ($this->modulosBusquedaControlador($url)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No podemos procesar la petición en este momento",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        if ($fecha == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Introduce una fecha",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        if ($this->verificarDatos("\d{4}-\d{2}-\d{2}", $fecha)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El termino de busqueda no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        $_SESSION[$url] = $fecha;

        $alerta = [
            "tipo" => "redireccionar",
            "url" => APP_URL . $url . "/"
        ];

        return json_encode($alerta);
    }


    /*----------  Controlador eliminar busqueda  ----------*/
    public function eliminarBuscadorControlador()
    {

        $url = $this->limpiarCadena($_POST['modulo_url']);

        if ($this->modulosBusquedaControlador($url)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No podemos procesar la petición en este momento",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        unset($_SESSION[$url]);

        $alerta = [
            "tipo" => "redireccionar",
            "url" => APP_URL . $url . "/"
        ];

        return json_encode($alerta);
    }
}
