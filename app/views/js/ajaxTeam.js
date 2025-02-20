// Arreglo para almacenar los nombres de los integrantes
let integrantes = [];
const agregarIntegranteBtn = document.getElementById("agregar_integrante_btn");
// Botón para agregar un integrante
if (agregarIntegranteBtn) {
  agregarIntegranteBtn.addEventListener("click", function () {
    let integranteInput = document.getElementById("integrante_input");
    let nombreIntegrante = integranteInput.value.trim();
    // Si hay texto en el input, agregar al arreglo
    if (nombreIntegrante !== "" && !integrantes.includes(nombreIntegrante)) {
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
            integrantes.push(nombreIntegrante);
            actualizarListaIntegrantes();
            integranteInput.value = ""; // Limpiar el campo
          } else {
            integranteInput.value = ""; // Limpiar el campo
          }
        });
    } else if (integrantes.includes(nombreIntegrante)) {
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
  integrantes.splice(index, 1); // Eliminar el integrante por su índice
  actualizarListaIntegrantes(); // Actualizar la lista visualmente
}

// Función para actualizar la lista de integrantes visualmente
function actualizarListaIntegrantes() {
  let lista = document.getElementById("integrantes_list");
  lista.innerHTML = ""; // Limpiar la lista

  // Añadir cada integrante como un nuevo elemento de lista con un botón de eliminar
  integrantes.forEach(function (integrante, index) {
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

const formulario_team_ajax = document.querySelector(".FormularioAjaxTeam");
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
      // Añadir el arreglo de usuarios a los datos del formulario
      data.append("equipo_integrantes", integrantes);
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
