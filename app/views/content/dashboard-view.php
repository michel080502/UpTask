<div class="container-fluid mt-5">
    <div class="row justify-content-center align-items-center mt-6">
        <div class="col-auto text-center">
            <figure class="figure" style="max-width: 150px;">
                <?php
                if (is_file("./app/views/fotos/" . $_SESSION['foto'])) {
                    echo '<img class="rounded-circle img-thumbnail" src="' . APP_URL . 'app/views/fotos/' . $_SESSION['foto'] . '" alt="Foto de perfil">';
                } else {
                    echo '<img class="rounded-circle img-thumbnail" src="' . APP_URL . 'app/views/fotos/default.png" alt="Foto de perfil por defecto">';
                }
                ?>
            </figure>
        </div>
    </div>
    <div class="row justify-content-center">
        <h2 class="h3 text-center">¡Bienvenido a nuestra aplicación de gestión de tareas, <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>!</h2>
        <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <p class="card-text">Con nuestra aplicación, puedes mantener todas tus tareas organizadas y bajo control, sin importar lo ocupado que esté tu día.</p>
                <p class="card-text">Aquí puedes registrar nuevas tareas, actualizar su estado (pendiente, en proceso o completada) y seguir avanzando hacia tus objetivos. Nos esforzamos por brindarte una experiencia sin complicaciones y eficiente para que puedas concentrarte en lo que más importa.</p>
                <p class="card-text">No importa si tienes una lista interminable de tareas o solo unas pocas pendientes, estamos aquí para ayudarte a hacerlas todas. ¡Empecemos!</p>
            </div>
        </div>
    </div>
    </div>
</div>