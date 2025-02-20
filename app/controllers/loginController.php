<?php

namespace app\controllers;

use app\models\mainModel;

class loginController extends mainModel
{


	/*---------- Controlador iniciar sesion ----------*/
	public function iniciarSesionControlador()
	{

		// Obtener el usuario y la clave del formulario
		$usuario = $this->limpiarCadena($_POST['login_usuario']);
		$clave = $this->limpiarCadena($_POST['login_clave']);

		// Verificar si los campos obligatorios están llenos
		if ($usuario == "" || $clave == "") {
			echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ocurrió un error inesperado',
                    text: 'No has llenado todos los campos que son obligatorios'
                });
            </script>";
		} else {
			// Verificar si el usuario coincide con el formato solicitado
			if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
				echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Ocurrió un error inesperado',
                        text: 'El USUARIO no coincide con el formato solicitado solo se permiten letras y 4-20 caracteres'
                    });
                </script>";
			} else {
				// Verificar si la clave coincide con el formato solicitado
				if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
					echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Ocurrió un error inesperado',
                            text: 'La CLAVE no coincide con el formato solicitado minimo 7 caracteres'
                        });
                    </script>";
				} else {
					// Verificar el usuario en la base de datos
					$check_usuario = $this->obtenerUsuario($usuario);
					if ($check_usuario != null) {
						// Verificar si el usuario y la clave son correctos
						if ($check_usuario['datosUsuario']['usuario'] == $usuario && password_verify($clave, $check_usuario['datosUsuario']['clave'])) {

							// Iniciar sesión y guardar datos en la sesión
							$_SESSION['id'] = $check_usuario['idUsuario'];
							$_SESSION['nombre'] = $check_usuario['datosUsuario']['nombre'];
							$_SESSION['apellido'] = $check_usuario['datosUsuario']['apellido'];
							$_SESSION['usuario'] = $check_usuario['datosUsuario']['usuario'];
							$_SESSION['foto'] = $check_usuario['datosUsuario']['foto'];

							// Redireccionar al dashboard
							if (headers_sent()) {
								echo "<script> window.location.href='" . APP_URL . "dashboard/'; </script>";
							} else {
								header("Location: " . APP_URL . "dashboard/");
							}
						} else {
							// Mostrar mensaje de error si el usuario o la clave son incorrectos
							echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ocurrió un error inesperado',
                                    text: 'Usuario o clave incorrectos'
                                });
                            </script>";
						}
					} else {
						// Mostrar mensaje de error si el usuario no existe
						echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió un error inesperado',
                                text: 'Usuario incorrecto'
                            });
                        </script>";
					}
				}
			}
		}
	}

	/*----------  Controlador cerrar sesion  ----------*/
	public function cerrarSesionControlador()
	{

		session_destroy();

		if (headers_sent()) {
			echo "<script> window.location.href='" . APP_URL . "login/'; </script>";
		} else {
			header("Location: " . APP_URL . "login/");
		}
	}
}
