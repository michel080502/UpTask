// Seleccionar todos los formularios con la clase "FormularioAjax"
const formularios_ajax = document.querySelectorAll(".FormularioAjax");

// Iterar sobre cada formulario seleccionado
formularios_ajax.forEach((formulario) => {
  // Agregar un evento de envío al formulario
  formulario.addEventListener("submit", function (e) {
    // Evitar el comportamiento predeterminado de envío del formulario
    e.preventDefault();

    // Mostrar un cuadro de diálogo de confirmación usando SweetAlert
    Swal.fire({
      title: "¿Estás seguro?",
      text: "Quieres realizar la acción solicitada",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, realizar",
      cancelButtonText: "No, cancelar",
    }).then((result) => {
      // Si el usuario confirma la acción
      if (result.isConfirmed) {
        Swal.fire({
          title: "Procesando...",
          text: "Espere un momento por favor.",
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          },
        });
        // Obtener los datos del formulario
        let data = new FormData(this);

        // Obtener el método y la acción del formulario
        let method = this.getAttribute("method");
        let action = this.getAttribute("action");

        // Configurar los encabezados para la solicitud fetch
        let encabezados = new Headers();

        // Configurar la solicitud fetch
        let config = {
          method: method,
          headers: encabezados,
          mode: "cors",
          cache: "no-cache",
          body: data,
        };

        // Realizar la solicitud fetch al servidor
        fetch(action, config)
          .then((respuesta) => {
            // Imprime la respuesta completa en la consola
            console.log("Respuesta del servidor:", respuesta);
            // Intentamos leer la respuesta como texto
            return respuesta.text().then((text) => {
              try {
                // Intentamos parsear como JSON
                const jsonResponse = JSON.parse(text);
                console.log("Respuesta como JSON:", jsonResponse);
                return jsonResponse; // Devuelve el JSON si es válido
              } catch (error) {
                // Si no es un JSON válido, mostramos el texto sin parsear
                console.log("Respuesta no es JSON:", text);
                return text; // Devuelve el texto sin modificaciones
              }
            });
          })
          .then((respuesta) => {
            // Cerrar la alerta de carga
            Swal.close();
            return alertas_ajax(respuesta);
          });
      }
    });
  });
});

function alertas_ajax(alerta) {
  // Comprobar el tipo de alerta y mostrar el mensaje correspondiente
  if (alerta.tipo == "simple") {
    // Alerta simple
    Swal.fire({
      icon: alerta.icono,
      title: alerta.titulo,
      text: alerta.texto,
      confirmButtonText: "Aceptar",
    });
  } else if (alerta.tipo == "recargar") {
    // Alerta para recargar la página
    Swal.fire({
      icon: alerta.icono,
      title: alerta.titulo,
      text: alerta.texto,
      confirmButtonText: "Aceptar",
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload(); // Recargar la página
      }
    });
  } else if (alerta.tipo == "limpiar") {
    // Alerta para limpiar el formulario
    Swal.fire({
      icon: alerta.icono,
      title: alerta.titulo,
      text: alerta.texto,
      confirmButtonText: "Aceptar",
    }).then((result) => {
      if (result.isConfirmed) {
        document.querySelector(".FormularioAjax").reset(); // Limpiar el formulario
      }
    });
  } else if (alerta.tipo == "redireccionar") {
    // Redireccionar a una URL específica
    window.location.href = alerta.url;
  }
}

/* Boton cerrar sesion */
let btn_exit = document.getElementById("btn_exit");

btn_exit.addEventListener("click", function (e) {
  e.preventDefault();
  Swal.fire({
    title: "¿Quieres salir del sistema?",
    text: "La sesión actual se cerrará y saldrás del sistema",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, salir",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      let url = this.getAttribute("href");
      window.location.href = url;
    }
  });
});

/*Apartado para el manejo de integrantes en la creacion de una tarea */
function cargarMiembrosEquipo(equipoId) {
  const selectMiembros = document.getElementById("miembro_id");

  // Resetear el select de miembros
  selectMiembros.innerHTML = '<option value="">Seleccione un miembro</option>';

  // Si no hay equipo seleccionado, no hacemos nada más
  if (!equipoId) return;

  // Preparar los datos para la petición
  const formData = new FormData();
  formData.append("modulo_equipo", "obtener_miembros_equipo");
  formData.append("equipo_id", equipoId);
  // Realizar la petición AJAX
  fetch("../app/ajax/equipoAjax.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Error:", data.mensaje);
        return;
      }
      // Agregar los miembros al select
      Object.entries(data).forEach(([nombre, rol]) => {
        const option = document.createElement("option");
        option.value = nombre; // El valor será el nombre del usuario
        option.textContent = `${nombre} (${rol})`; // Mostramos nombre y rol
        selectMiembros.appendChild(option);
      });
    })
    .catch((error) => {
      console.error("Error en la petición:", error);
    });
}
