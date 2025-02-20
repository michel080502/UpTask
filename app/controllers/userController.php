<?php

namespace app\controllers;

use app\models\mainModel;

class userController extends mainModel
{
	/*----------  Controlador registrar usuario  ----------*/
	public function registrarUsuarioControlador()
	{
		# Almacenando datos#
		$nombre = $this->limpiarCadena($_POST['usuario_nombre']);
		$apellido = $this->limpiarCadena($_POST['usuario_apellido']);
		$usuario = $this->limpiarCadena($_POST['usuario_usuario']);
		$email = $this->limpiarCadena($_POST['usuario_email']);
		$clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
		$clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);

		# Verificando campos obligatorios #
		if ($nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == "") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El NOMBRE no coincide con el formato solicitado, solo se permiten letras",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El APELLIDO no coincide con el formato solicitado, solo se permiten letras",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El USUARIO no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "Las CLAVES no coinciden con el formato solicitado debe ser de 7 caracteres minimos",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando email #
		if ($email != "") {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$check_email = $this->existeEnUsuarioBD("email", $email);
				if ($check_email) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Ocurrió un error inesperado",
						"texto" => "El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
						"icono" => "error"
					];
					return json_encode($alerta);
					exit();
				}
			} else {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Ha ingresado un correo electrónico no valido",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}
		}

		# Verificando claves #
		if ($clave1 != $clave2) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "Las contraseñas que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		} else {
			$clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
		}

		# Verificando usuario #
		$check_usuario = $this->existeEnUsuarioBD("usuario", $usuario);
		if ($check_usuario) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}
		echo $check_usuario;

		$foto = "usuario.png";

		$usuario_datos_reg = [
			'nombre' => $nombre,
			'apellido' => $apellido,
			'email' => $email,
			'usuario' => $usuario,
			'clave' => $clave,
			'creado' => date("Y-m-d H:i:s"),
			'actualizado' => date("Y-m-d H:i:s"),
			'foto' => $foto
		];

		$registrar_usuario = $this->guardarDatos("usuarios", null, $usuario_datos_reg);

		if ($registrar_usuario != null) {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Usuario registrado",
				"texto" => "El usuario " . $usuario . " se registro con exito",
				"icono" => "success"
			];
		} else {

			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No se pudo registrar el usuario, por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	public function registarTareaControlador()
	{
		$tarea = $this->limpiarCadena($_POST['usuario_tarea']);
		$fecha = $this->limpiarCadena($_POST['usuario_tarea_fecha']);
		$responsable = $this->limpiarCadena($_POST['miembro_equipo']);
		$equipo = $_POST['equipo_id'];
		$id_usuario_creador = $_POST['usuario_id'];

		# Verificando campos obligatorios #
		if ($tarea == "" || $fecha == "" || $id_usuario_creador == "" || $responsable == "" || $equipo == "") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $tarea)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La descripcion de la TAREA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("\d{4}-\d{2}-\d{2}", $fecha)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La FECHA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		$estado = "Pendiente";

		$usuario_datos_tarea = [
			'tarea' => $tarea,
			'equipo' => $equipo,
			'responsable' => $responsable,
			'creador' => $id_usuario_creador,
			'fecha' => $fecha,
			'estado' => 'Pendiente'
		];

		$registrar_tarea = $this->guardarDatos("tareas", "", $usuario_datos_tarea);

		if ($registrar_tarea) {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Tarea registrada",
				"texto" => "La tarea se registro con exito",
				"icono" => "success"
			];
		} else {

			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No se pudo registrar la tarea, por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	public function actualizarUsuarioControlador()
	{

		$id = $this->limpiarCadena($_POST['usuario_id']);

		# Verificando usuario #
		$datos = $this->ejecutarConsulta("usuarios", $id);
		if (empty($datos)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No hemos encontrado el usuario en el sistema",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		} else {
			$datos;
		}

		# Almacenando datos#
		$nombre = $this->limpiarCadena($_POST['usuario_nombre']);
		$apellido = $this->limpiarCadena($_POST['usuario_apellido']);

		$usuario = $this->limpiarCadena($_POST['usuario_usuario']);
		$email = $this->limpiarCadena($_POST['usuario_email']);
		$clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
		$clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);

		# Verificando campos obligatorios #
		if ($nombre == "" || $apellido == "" || $usuario == "") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El NOMBRE no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El APELLIDO no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El USUARIO no coincide con el formato solicitado, solo se permiten letras y numeros, minimo 4 carecteres",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando email #
		if ($email != "" && $datos['email'] != $email) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$check_email = $this->existeEnUsuarioBD("email", $email);
				if ($check_email) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Ocurrió un error inesperado",
						"texto" => "El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
						"icono" => "error"
					];
					return json_encode($alerta);
					exit();
				}
			} else {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Ha ingresado un correo electrónico no valido",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}
		}

		# Verificando claves #
		if ($clave1 != "" || $clave2 != "") {
			if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {

				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "Las CLAVES no coinciden con el formato solicitado",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			} else {
				if ($clave1 != $clave2) {

					$alerta = [
						"tipo" => "simple",
						"titulo" => "Ocurrió un error inesperado",
						"texto" => "Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
						"icono" => "error"
					];
					return json_encode($alerta);
					exit();
				} else {
					$clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
				}
			}
		} else {
			$clave = $datos['clave'];
		}

		# Verificando usuario #
		if ($datos['usuario'] != $usuario) {
			$check_usuario = $this->existeEnUsuarioBD("usuario", $usuario);
			if ($check_usuario) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
					"icono" => "error"
				];
				return json_encode($alerta);
				exit();
			}
		}

		$usuario_datos_up = [
			'nombre' => $nombre,
			'apellido' => $apellido,
			'email' => $email,
			'usuario' => $usuario,
			'clave' => $clave,
			'actualizado' => date("Y-m-d H:i:s"),
		];

		if ($this->actualizarDatos("usuarios", $id, $usuario_datos_up)) {

			if ($id == $_SESSION['id']) {
				$_SESSION['nombre'] = $nombre;
				$_SESSION['apellido'] = $apellido;
				$_SESSION['usuario'] = $usuario;
			}

			$alerta = [
				"tipo" => "recargar",
				"titulo" => "Usuario actualizado",
				"texto" => "Los datos del usuario " . $datos['nombre'] . " " . $datos['apellido'] . " se actualizaron correctamente",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No hemos podido actualizar los datos del usuario " . $datos['nombre'] . " " . $datos['apellido'] . ", por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	/*----------  Controlador actualizar tarea  ----------*/

	public function actualizarTareaControlador()
	{

		$id = $this->limpiarCadena($_POST['id_tarea']);
		# Almacenando datos#
		$tarea = $this->limpiarCadena($_POST['usuario_tarea']);
		$tarea_fecha = $this->limpiarCadena($_POST['usuario_tarea_fecha']);
		$tarea_estado = $this->limpiarCadena($_POST['usuario_tarea_estado']);


		# Verificando campos obligatorios #
		if ($tarea == "" || $tarea_fecha == "" || $tarea_estado == "") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $tarea)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La descripcion de la TAREA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("\d{4}-\d{2}-\d{2}", $tarea_fecha)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La FECHA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $tarea_estado)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El ESTADO no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		$tarea_datos_up = [
			"tarea" => $tarea,
			"estado" => $tarea_estado,
			"fecha" => $tarea_fecha
		];

		if ($this->actualizarDatosEspecificos("tareas", $id, $tarea_datos_up)) {
			$alerta = [
				"tipo" => "recargar",
				"titulo" => "Tarea actualizada",
				"texto" => "Los datos de la tarea se actualizaron correctamente",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No hemos podido actualizar los datos de la tarea, por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	public function registarEquipoControlador()
	{
		$nombre_equipo = $this->limpiarCadena($_POST['usuario_equipo']);
		$descripcion_equipo = $this->limpiarCadena($_POST['usuario_equipo_descripción']);
		$integrantes_equipo = $_POST['equipo_integrantes'];
		$nombre_usuario = $_POST['usuario_nombre'];
		$id_usuario = $_POST['usuario_id'];
		$integrantes_equipo = explode(",", $integrantes_equipo);
		# Verificando campos obligatorios #
		if ($nombre_equipo == "" || $descripcion_equipo == "" || empty($integrantes_equipo)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $nombre_equipo)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La descripcion de la TAREA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $descripcion_equipo)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La descripcion de la TAREA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}
		//Verifica si en el arreglo se encuentra el mismo creador del arreglo para eliminarlo
		// Buscar la clave del elemento en el arreglo
		$clave = array_search($nombre_usuario, $integrantes_equipo);
		if ($clave !== false) {
			// Eliminar el elemento si se encontró
			unset($integrantes_equipo[$clave]);
		}
		if (is_array($integrantes_equipo)) {
			// Crear un nuevo arreglo asociativo donde cada usuario es una clave y su rol es 'integrante'
			$usuariosConRoles = [];
			foreach ($integrantes_equipo as $usuario) {
				$usuariosConRoles[$usuario] = "integrante";
			}
		}

		//Agrega el nombre de usuario del creador con su Rol
		$usuariosConRoles[$nombre_usuario] = "creador";

		$equipo_datos_reg = [
			'nombre' => $nombre_equipo,
			'descripcion' => $descripcion_equipo,
			'creador' => $id_usuario,
			'integrantes' => $usuariosConRoles,
			'creado' => date("Y-m-d H:i:s"),
			'actualizado' => date("Y-m-d H:i:s"),
		];

		$registrar_equipo = $this->guardarDatos("equipos", null, $equipo_datos_reg);

		if ($registrar_equipo != null) {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Equipo creado",
				"texto" => "El equipo " . $nombre_equipo . " se creo con exito",
				"icono" => "success"
			];
		} else {

			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No se pudo registrar el equipo, por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	public function confirmarUsuarioControlador()
	{
		# Almacenando datos#
		$usuario = $this->limpiarCadena($_POST['nombre_usuario']);

		# Verificando campos obligatorios #
		if ($usuario == "") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "El USUARIO no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando usuario #
		$check_usuario = $this->existeEnUsuarioBD("usuario", $usuario);
		if ($check_usuario) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Usuario encontrado",
				"texto" => "El USUARIO se encuentra registrado",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Usuario no encontrado",
				"texto" => "El USUARIO NO se encuentra registrado",
				"icono" => "error"
			];
		}
		return json_encode($alerta);
	}

	public function actualizarEquipoControlador()
	{
		$nombre_equipo = $this->limpiarCadena($_POST['usuario_equipo']);
		$descripcion_equipo = $this->limpiarCadena($_POST['usuario_equipo_descripción']);
		$integrantesRoles = json_decode($_POST['equipo_integrantes'], true);
		$id_equipo = $_POST['equipo_id'];

		# Verificando campos obligatorios #
		if ($nombre_equipo == "" || $descripcion_equipo == "" || empty($integrantesRoles)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $nombre_equipo)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La descripcion de la TAREA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		# Verificando integridad de los datos #
		if ($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ,.:;¿?¡!\-\s]*", $descripcion_equipo)) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "La descripcion de la TAREA no coincide con el formato solicitado",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		$equipo_datos_reg = [
			'nombre' => $nombre_equipo,
			'descripcion' => $descripcion_equipo,
			'integrantes' => $integrantesRoles,
			'actualizado' => date("Y-m-d H:i:s"),
		];

		$actualizar_equipo = $this->actualizarDatosEspecificos("equipos", $id_equipo, $equipo_datos_reg);

		if ($actualizar_equipo != null) {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Equipo actualizado",
				"texto" => "El equipo " . $nombre_equipo . " se actualizo con exito",
				"icono" => "success"
			];
		} else {

			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No se pudo actualizar el equipo, por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	public function eliminarEquipoControlador()
	{
		$id_equipo = $_POST['equipo_id'];
		$nombre_equipo = $this->limpiarCadena($_POST['usuario_equipo']);

		$eliminar_equipo = $this->eliminarDocumento("equipos", $id_equipo);
		$eliminar_tareas = $this->eliminarTareasEquipo("tareas", $id_equipo);

		if ($eliminar_equipo != null && $eliminar_tareas != null) {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Equipo eliminado",
				"texto" => "El equipo " . $nombre_equipo . " se eliminado con exito",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No se pudo eliminar equipo, por favor intente nuevamente",
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}

	public function salirEquipoControlador()
	{
		$usuario = $this->limpiarCadena($_POST['nombre_usuario']);
		$integrantesRoles = json_decode($_POST['equipo_integrantes'], true);
		$id_equipo = $_POST['equipo_id'];

		# Verificando campos obligatorios #
		if ($usuario == "") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No has llenado todos los campos que son obligatorios",
				"icono" => "error"
			];
			return json_encode($alerta);
			exit();
		}

		// Verificar si la llave existe antes de eliminarla
		if (array_key_exists($usuario, $integrantesRoles)) {
			// Eliminar el elemento por su llave
			unset($integrantesRoles[$usuario]);

			$equipo_datos_reg = [
				'integrantes' => $integrantesRoles,
				'actualizado' => date("Y-m-d H:i:s"),
			];
			$eliminar_usuario = $this->actualizarDatosEspecificos("equipos", $id_equipo, $equipo_datos_reg);
			$cambiar_responsable_tareas = $this->actualizarResponsableTareas("tareas", $id_equipo, $usuario);
			if ($eliminar_usuario != null) {
				$alerta = [
					"tipo" => "limpiar",
					"titulo" => "Has salido del equipo",
					"texto" => "Has salido del equipo de manera exitosa",
					"icono" => "success"
				];
			} else {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No se te pudo eliminar del equipo, por favor intente nuevamente",
					"icono" => "error"
				];
			}
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ya no formas parte del equipo actualmente",
				"texto" => $usuario,
				"icono" => "error"
			];
		}

		return json_encode($alerta);
	}
	public function traerMiembrosEquipo()
	{
		$id_equipo = $_POST['equipo_id'];
		$miembros_equipo = $this->seleccionarDatos("Unico", "equipos", "", $id_equipo);
		if ($miembros_equipo) {
			$miembros = $miembros_equipo['documento']['integrantes'];
			return json_encode($miembros);
		} else {
			$alerta = [
				'error' => true,
				'mensaje' => 'Error al obtener los miembros del equipo'
			];
			return json_encode($alerta);
		}
	}
	public function realizarTareaControlador()
	{
		$id_tarea = $_POST['id_tarea'];
		$estado_tarea = [
			'estado' => 'Realizada'
		];
		$actualizar_estado = $this->actualizarDatosEspecificos('tareas', $id_tarea, $estado_tarea);
		if ($actualizar_estado != null) {
			$alerta = [
				"tipo" => "recargar",
				"titulo" => "Tarea realizada",
				"texto" => "La tarea ha sido actualizada como realizada",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Ocurrió un error inesperado",
				"texto" => "No se pudo actualizar la tarea por favor intente nuevamente",
				"icono" => "error"
			];
		}
		return json_encode($alerta);
	}
}
