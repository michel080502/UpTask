let integrantesRoles = {};
let integrantesNuevos = [];
/*Apartado para el manejo de los integrantes y su roles */
// Función para cargar los integrantes en el arreglo al iniciar
function cargarIntegrantes() {
  document
    .querySelectorAll("#integrantes_editables .integrante-rol")
    .forEach((input) => {
      const nombre = input.getAttribute("data-nombre");
      const rol = input.value;
      integrantesRoles[nombre] = rol;
    });
  console.log(integrantesRoles);
}

function cargarIntegrantes_2() {
  // Selecciona todas las filas del cuerpo de la tabla
  document.querySelectorAll("table tbody tr").forEach((row) => {
    const nombre = row.cells[0].textContent; // Obtiene el nombre del integrante
    const rol = row.cells[1].textContent; // Obtiene el rol del integrante
    integrantesRoles[nombre] = rol; // Agrega el nombre y rol al objeto
  });

  console.log(integrantesRoles); // Imprime el objeto en la consola
}

// Ejecutar la carga inicial
cargarIntegrantes();
cargarIntegrantes_2();
// Escuchar los cambios en los roles y actualizar el arreglo
document
  .querySelectorAll("#integrantes_editables .integrante-rol")
  .forEach((input) => {
    input.addEventListener("input", function () {
      const nombre = this.getAttribute("data-nombre");
      integrantesRoles[nombre] = this.value;
      console.log(integrantesRoles); // Ver el arreglo actualizado en la consola
    });
  });

const agregarIntegranteBtn = document.getElementById("agregar_integrante_btn");
// Botón para agregar un integrante
if (agregarIntegranteBtn) {
  agregarIntegranteBtn.addEventListener("click", function () {
    let integranteInput = document.getElementById("integrante_input");
    let nombreIntegrante = integranteInput.value.trim();
    // Si hay texto en el input, agregar al arreglo
    if (
      nombreIntegrante !== "" &&
      !integrantesRoles.hasOwnProperty(nombreIntegrante)
    ) {
      fetch("../app/ajax/equipoAjax.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "nombre_usuario=" + encodeURIComponent(nombreIntegrante),
      })
        // Convertir la respuesta a JSON
        .then((respuesta) => respuesta.json())
        .then((data) => {
          // Mostrar alerta basada en la respuesta del servidor
          Swal.fire({
            icon: data.icono, // 'success' o 'error'
            title: data.titulo,
            text: data.texto,
            confirmButtonText: "Aceptar",
          });
          // Si el usuario existe, agregar al arreglo
          if (data.icono === "success") {
            integrantesNuevos.push(nombreIntegrante);
            integrantesRoles[nombreIntegrante] = "integrante";
            actualizarListaIntegrantes();
            integranteInput.value = ""; // Limpiar el campo
            console.log(integrantesRoles);
          } else {
            integranteInput.value = ""; // Limpiar el campo
          }
        });
    } else if (integrantesRoles.hasOwnProperty(nombreIntegrante)) {
      // Alerta en caso de duplicado
      Swal.fire({
        icon: "warning",
        title: "Usuario duplicado",
        text: "Este usuario ya está en la lista de integrantes.",
        confirmButtonText: "Aceptar",
      });
    }
  });
}

// Función para eliminar un integrante del arreglo
function eliminarIntegrante(index) {
  let usuario = integrantesNuevos[index];
  integrantesNuevos.splice(index, 1); // Eliminar el integrante por su índice
  delete integrantesRoles[usuario];
  console.log(usuario);
  actualizarListaIntegrantes(); // Actualizar la lista visualmente
  console.log(integrantesRoles);
}

// Función para actualizar la lista de integrantes visualmente
function actualizarListaIntegrantes() {
  let lista = document.getElementById("integrantes_list");
  lista.innerHTML = ""; // Limpiar la lista

  // Añadir cada integrante como un nuevo elemento de lista con un botón de eliminar
  integrantesNuevos.forEach(function (integrante, index) {
    let li = document.createElement("li");
    li.className =
      "list-group-item d-flex justify-content-between align-items-center";
    li.textContent = integrante;

    // Botón para eliminar
    let btnEliminar = document.createElement("button");
    btnEliminar.className = "btn btn-danger btn-sm";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.addEventListener("click", function () {
      eliminarIntegrante(index); // Llamar a la función para eliminar el integrante
    });

    li.appendChild(btnEliminar);
    lista.appendChild(li);
  });
}

/*Apartado para el envio de request al servidor y actualizar los datos del equipo */
const formulario_team_ajax = document.querySelector(
  ".FormularioAjaxTeamUpdate"
);
if (formulario_team_ajax) {
  // Agregar un evento de envío al formulario
  formulario_team_ajax.addEventListener("submit", function (e) {
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
        // Obtener los datos del formulario
        let data = new FormData(this);
        // Convertir el arreglo asociativo a string JSON
        const integrantesRolesJSON = JSON.stringify(integrantesRoles);
        // Agregar al FormData
        data.append("equipo_integrantes", integrantesRolesJSON);
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
          // Convertir la respuesta a JSON
          .then((respuesta) => respuesta.json())
          .then((data) => {
            // Mostrar alerta basada en la respuesta del servidor
            Swal.fire({
              icon: data.icono, // 'success' o 'error'
              title: data.titulo,
              text: data.texto,
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.reload();
              }
            });
          });
      }
    });
  });
}
/*Apartado para el envio de request al servidor y eliminar el equipo de la base de datos */
const formulario_team_ajax_eliminar = document.querySelector(
  ".FormularioAjaxTeamEliminar"
);
if (formulario_team_ajax_eliminar) {
  formulario_team_ajax_eliminar.addEventListener("submit", function (e) {
    // Evitar el comportamiento predeterminado de envío del formulario
    e.preventDefault();
    // Mostrar un cuadro de diálogo de confirmación usando SweetAlert
    Swal.fire({
      title: "¿Estás seguro?",
      text: "Quieres eliminar el equipo de manera permanente",
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
          // Convertir la respuesta a JSON
          .then((respuesta) => respuesta.json())
          .then((data) => {
            // Cerrar la alerta de carga
            Swal.close();
            // Mostrar alerta basada en la respuesta del servidor
            Swal.fire({
              icon: data.icono, // 'success' o 'error'
              title: data.titulo,
              text: data.texto,
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "../userTeam/";
              }
            });
          });
      }
    });
  });
}
/*Apartado para el envio de request al servidor y eliminar el equipo de la base de datos */
const formulario_team_ajax_salirEquipo = document.querySelector(
  ".FormularioAjaxTeamSalir"
);
if (formulario_team_ajax_salirEquipo) {
  formulario_team_ajax_salirEquipo.addEventListener("submit", function (e) {
    // Evitar el comportamiento predeterminado de envío del formulario
    e.preventDefault();
    // Mostrar un cuadro de diálogo de confirmación usando SweetAlert
    Swal.fire({
      title: "¿Estás seguro?",
      text: "Quieres salir del equipo",
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
        // Convertir el arreglo asociativo a string JSON
        const integrantesRolesJSON = JSON.stringify(integrantesRoles);
        // Agregar al FormData
        data.append("equipo_integrantes", integrantesRolesJSON);
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
          // Convertir la respuesta a JSON
          .then((respuesta) => respuesta.json())
          .then((data) => {
            // Cerrar la alerta de carga
            Swal.close();
            // Mostrar alerta basada en la respuesta del servidor
            Swal.fire({
              icon: data.icono, // 'success' o 'error'
              title: data.titulo,
              text: data.texto,
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "../userTeam/";
              }
            });
          });
      }
    });
  });
}
