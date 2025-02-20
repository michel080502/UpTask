<?php

namespace app\models;

if (file_exists(__DIR__ . "/../../config/server.php")) {
    require_once __DIR__ . "/../../config/server.php";
}
class mainModel
{
    private $server = DB_SERVER;
    private $pass = DB_PASS;

    protected function conectar()
    {
        $url = $this->server . '.json';

        if (!empty($this->authToken)) {
            $url .= '?auth=' . $this->pass;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud y almacenar la respuesta
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // Obtener el código de respuesta HTTP
        curl_close($ch);

        // Si la respuesta tiene un código 200, la conexión fue exitosa
        if ($httpcode === 200) {
            return true;
        } else {
            // Mostrar el error de conexión con más detalles
            echo "Error al conectar a Firebase. Código HTTP: " . $httpcode . "\n";
            return false;
        }
    }

    protected function ejecutarConsulta($collection, $document)
    {
        #Se hace la conexion con el metodo ya establecido y se prepara la consulta que se requiere
        if ($this->conectar()) {
            $url = $this->server . $collection . '/' . $document . '.json';
            $ch =  curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response, true);

            return $response;
        } else {
            echo "Error al intentar conectar con Firebase.\n";
            return null;
        }
    }

    public function limpiarCadena($cadena)
    {
        #Se establecen las palabras que no estaran permitidas para evitar inyeccion de codigo
        $palabras = [
            "<script>",
            "</script>",
            "<script src",
            "<script type=",
            "<?php",
            "?>",
            "--",
            "^",
            "<",
            ">",
            "==",
            "=",
            ";",
            "::",
            "eval(",
            "alert(",
            "console.log("
        ];
        $cadena = trim($cadena);
        $cadena = stripcslashes($cadena);
        #Se recorren todas las palabras para ver si la cadena contiene alguna de ellas y las elimina
        foreach ($palabras as $palabra) {
            $cadena = str_ireplace($palabra, "", $cadena);
        }
        // Convertir caracteres especiales en entidades HTML para evitar inyección de código
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
        $cadena = trim($cadena);
        $cadena = stripcslashes($cadena);
        return $cadena;
    }

    protected function verificarDatos($filtro, $cadena)
    {
        #Verifica que la cadena coincida con las expresiones permitidas   
        if (preg_match("/^" . $filtro . "$/", $cadena)) {
            return false;
        } else {
            return true;
        }
    }

    protected function guardarDatos($collection, $document, $data)
    {
        // Se conecta con Firebase usando el método conectar()
        if ($this->conectar()) {
            // Si se proporciona un documento, usamos PUT, de lo contrario, POST para insertar un nuevo documento
            if ($document) {
                $url = $this->server . $collection . '/' . $document . '.json';
                $method = 'PUT';  // Actualiza o crea un documento con el ID especificado
            } else {
                $url = $this->server . $collection . '.json';
                $method = 'POST';  // Crea un nuevo documento con ID generado automáticamente
            }

            // Verifica si hay token de autenticación
            if (!empty($this->authToken)) {
                $url .= '?auth=' . $this->pass;
            }

            // Inicializar cURL para realizar la solicitud
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            // Convertimos el arreglo de datos a JSON
            $jsonData = json_encode($data);

            // Pasar los datos al cuerpo de la solicitud
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Si el código HTTP es 200 o 201, la operación fue exitosa
            if ($httpcode === 200 || $httpcode === 201) {
                return json_decode($response, true);  // Retorna la respuesta decodificada
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    protected function existeEnUsuarioBD($clave, $dato)
    {
        if ($this->conectar()) {
            $url = $url = $this->server . '/usuarios.json';
            // Inicializar cURL para obtener todos los documentos de la colección
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            curl_close($ch);
            // Decodificar la respuesta JSON a un array PHP
            $usuarios = json_decode($response, true);
            if (!empty($usuarios)) {
                foreach ($usuarios as $idUsuario => $datosUsuario) {
                    if (isset($datosUsuario[$clave]) && $datosUsuario[$clave] === $dato) {
                        // Si se encuentra un campo coincidente, devolver true
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function  obtenerUsuario($nombreUsuario)
    {
        if ($this->conectar()) {
            $url = $url = $this->server . '/usuarios.json';
            // Inicializar cURL para obtener todos los documentos de la colección
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // Decodificar la respuesta JSON a un array PHP
            $usuarios = json_decode($response, true);
            // Verificar si hay documentos en la colección
            if (!empty($usuarios)) {
                foreach ($usuarios as $idUsuario => $datosUsuario) {
                    if (isset($datosUsuario['usuario']) && $datosUsuario['usuario'] === $nombreUsuario) {
                        // Si se encuentra un nombre de usuario coincidente, devolver toda la información del documento
                        return [
                            'idUsuario' => $idUsuario,
                            'datosUsuario' => $datosUsuario
                        ];
                    }
                }
            }
            return null;
        }
    }
    public function seleccionarDatos($tipo, $collection, $campo, $id)
    {
        // Limpiar las cadenas de entrada para evitar inyección de SQL
        $tipo = $this->limpiarCadena($tipo);
        $collection = $this->limpiarCadena($collection);
        $campo = $this->limpiarCadena($campo);
        $id = $this->limpiarCadena($id);

        // Construir y preparar la consulta SQL según el tipo de selección
        if ($tipo == "Unico") {
            if ($this->conectar()) {
                $url = $this->server . $collection . '/' . $id . '.json';
                // Inicializar cURL para obtener todos los documentos de la colección
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                // Ejecutar la solicitud y obtener la respuesta
                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                // Decodificar la respuesta JSON a un array PHP
                $documento = json_decode($response, true);
                // Verificar si hay documentos en la colección
                if (!empty($documento)) {
                    return [
                        'id' => $id,
                        'documento' => $documento
                    ];
                }
                return null;
            }
        } elseif ($tipo == "Normal") {
            // Construir la URL para la colección completa
            $url = $this->server . $collection . '.json';

            // Inicializar cURL para obtener todos los documentos de la colección
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Decodificar la respuesta JSON
            $documentos = json_decode($response, true);
            // Verificar si hay documentos en la colección
            if (!empty($documentos)) {
                return $documentos;
            } else {
                return null;
            }
        }
    }

    protected function actualizarDatos($collection, $id, $data)
    {
        // Construir la URL para el documento específico
        $url = $this->server . $collection . '/' . $id . '.json';

        // Inicializar cURL para actualizar el documento
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); // Usar PATCH para actualizar parcialmente
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Los datos a actualizar
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);

        // Ejecutar la solicitud y obtener la respuesta
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Devolver true si la actualización fue exitosa, false de lo contrario
        return $httpcode == 200;
    }

    public function seleccionarEquipos($usuario)
    {
        if ($this->conectar()) {
            $url = $url = $this->server . '/equipos.json';
            // Inicializar cURL para obtener todos los documentos de la colección
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            curl_close($ch);
            // Decodificar la respuesta JSON a un array PHP
            $equipos = json_decode($response, true);
            $equiposUsuario = [];
            // Verificar si hay documentos en la colección
            if (!empty($equipos)) {
                foreach ($equipos as $idEquipo => $datosEquipo) {
                    if (isset($datosEquipo['integrantes'])) {
                        foreach ($datosEquipo['integrantes'] as $idusuario => $rol) {
                            if ($usuario == $idusuario) {
                                $equipoConUsuario = [
                                    'idEquipo' => $idEquipo,
                                    'datosEquipo' => $datosEquipo,
                                ];
                                $equiposUsuario[] = $equipoConUsuario;
                                break;
                            }
                        }
                    }
                }
                // Retornar los equipos si el usuario pertenece a alguno, de lo contrario retornar null
                return !empty($equiposUsuario) ? $equiposUsuario : null;
            }
            return null;
        }
    }

    protected function actualizarDatosEspecificos($collection, $document, $data)
    {
        // URL de la API de Realtime Database
        $url = $this->server . $collection . '/' . $document . '.json';

        // Configurar la petición cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); // PATCH asegura actualización parcial
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Verificar la respuesta
        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return null;
        }
    }

    protected function eliminarDocumento($collection, $document)
    {
        // URL de la API de Realtime Database
        $url = $this->server . $collection . '/' . $document . '.json';
        // Configurar la petición cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // DELETE elimina el documento

        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            return true; // Eliminación exitosa
        } else {
            return null; // Error en la eliminación
        }
    }

    protected function eliminarTareasEquipo($collection, $id_equipo)
    {
        // URL de la API de Realtime Database para obtener los documentos con el id_equipo específico
        $url = $this->server . $collection . '.json?orderBy="equipo"&equalTo="' . $id_equipo . '"';

        // Configurar la petición cURL para obtener los documentos
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Obtener documentos

        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200 && $response != 'null') {
            $data = json_decode($response, true);

            // Recorrer los documentos obtenidos y eliminar cada uno
            foreach ($data as $documentId => $documentData) {
                // URL de la API para eliminar cada documento
                $deleteUrl = $this->server . $collection . '/' . $documentId . '.json';

                // Configurar la petición cURL para eliminar el documento
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $deleteUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // DELETE elimina el documento

                // Ejecutar la petición de eliminación
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode != 200) {
                    return null; // Error al eliminar un documento
                }
            }
            return true; // Eliminación exitosa
        } else {
            return null; // No se encontraron documentos o hubo un error al obtener los documentos
        }
    }

    protected function actualizarResponsableTareas($collection, $id_equipo, $responsableAntiguo)
    {
        // URL de la API de Realtime Database para obtener los documentos con el id_equipo específico
        $url = $this->server . $collection . '.json?orderBy="equipo"&equalTo="' . $id_equipo . '"';

        // Configurar la petición cURL para obtener los documentos
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Obtener documentos

        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200 && $response != 'null') {
            $data = json_decode($response, true);

            // Recorrer los documentos obtenidos y modificar el campo 'responsable' si es necesario
            foreach ($data as $documentId => $documentData) {
                // Verificar si el valor de 'responsable' es igual al valor que se pasa a la función
                if (isset($documentData['responsable']) && $documentData['responsable'] == $responsableAntiguo) {
                    $iDresponsableNuevo = $this->obtenerEquipoPorId($id_equipo)['creador'];
                    $responsableNuevo = $this->ejecutarConsulta('usuarios', $iDresponsableNuevo);              // Actualizar el campo 'responsable' con el nuevo valor
                    $documentData['responsable'] = $responsableNuevo['usuario'];

                    // URL de la API para actualizar el documento
                    $updateUrl = $this->server . $collection . '/' . $documentId . '.json';

                    // Configurar la petición cURL para actualizar el documento
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $updateUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // PUT para reemplazar el documento con el nuevo valor

                    // Enviar los datos actualizados
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($documentData)); // Enviar los datos como JSON

                    // Ejecutar la petición de actualización
                    curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode != 200) {
                        return null; // Error al actualizar un documento
                    }
                }
            }
            return true; // Actualización exitosa
        } else {
            return null; // No se encontraron documentos o hubo un error al obtener los documentos
        }
    }

    public function seleccionarTareasUsuario($usuario)
    {
        if ($this->conectar()) {
            $url = $url = $this->server . '/tareas.json';
            // Inicializar cURL para obtener todos los documentos de la colección
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            curl_close($ch);
            // Decodificar la respuesta JSON a un array PHP
            $tareas = json_decode($response, true);
            $tareasUsuario = [];
            // Verificar si hay documentos en la colección
            if (!empty($tareas)) {
                foreach ($tareas as $idTarea => $datosTarea) {
                    if (isset($datosTarea['responsable'])) {
                        if ($datosTarea['responsable'] == $usuario && $datosTarea['estado'] != 'Realizada') {
                            $tareaConUsuario = [
                                'idTarea' => $idTarea,
                                'nombreEquipo' => $this->obtenerEquipoPorId($datosTarea['equipo'])['nombre'],
                                'datosTarea' => $datosTarea,
                            ];
                            $tareasUsuario[] = $tareaConUsuario;
                        }
                    }
                }
                // Retornar los equipos si el usuario pertenece a alguno, de lo contrario retornar null
                return !empty($tareasUsuario) ? $tareasUsuario : null;
            }
            return null;
        }
    }

    public function obtenerEquipoPorId($idEquipo)
    {
        if ($this->conectar()) {
            // Definir la URL con el ID del equipo específico
            $url = $this->server . '/equipos/' . $idEquipo . '.json';

            // Inicializar cURL para obtener el documento específico
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            curl_close($ch);

            // Decodificar la respuesta JSON a un array PHP
            $equipo = json_decode($response, true);

            // Verificar si el documento existe
            return !empty($equipo) ? $equipo : null;
        }
    }

    public function seleccionarTareasEquipo($idEquipo)
    {
        if ($this->conectar()) {
            $url = $url = $this->server . '/tareas.json';
            // Inicializar cURL para obtener todos los documentos de la colección
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            curl_close($ch);
            // Decodificar la respuesta JSON a un array PHP
            $tareas = json_decode($response, true);
            $tareasEquipo = [];
            // Verificar si hay documentos en la colección
            if (!empty($tareas)) {
                foreach ($tareas as $idTarea => $datosTarea) {
                    if (isset($datosTarea['equipo'])) {
                        if ($datosTarea['equipo'] == $idEquipo) {
                            $tareaEquipo = [
                                'idTarea' => $idTarea,
                                'nombreEquipo' => $this->obtenerEquipoPorId($datosTarea['equipo'])['nombre'],
                                'datosTarea' => $datosTarea,
                            ];
                            $tareasEquipo[] = $tareaEquipo;
                        }
                    }
                }
                // Retornar los equipos si el usuario pertenece a alguno, de lo contrario retornar null
                return !empty($tareasEquipo) ? $tareasEquipo : null;
            }
            return null;
        }
    }
}
