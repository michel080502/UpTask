<section class="vh-100 gradient-custom">
    <div class="container-fluid py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <div class="mb-md-5 mt-md-4 pb-5">
                            <h2 class="fw-bold mb-2 text-uppercase">Registrate</h2>
                            <p class="text-white-50 mb-5">Por favor ingresa tus datos para crear tu cuenta</p>
                            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                               
                            <input type="hidden" name="modulo_usuario" value="registrar">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline form-white">
                                            <input type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario_nombre">Nombre</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline form-white">
                                            <input type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario_apellido">Apellidos</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline form-white">
                                            <input type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario_usuario">Usuario</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <input type="email" name="usuario_email" maxlength="70" class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario_email">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <input type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario_clave_1">Clave</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <input type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required class="form-control form-control-lg" />
                                            <label class="form-label" for="usuario_clave_2">Repetir Clave</label>
                                        </div>
                                    </div>
                                </div>

                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5 mb-5" type="submit">Crear Cuenta</button>
                                <p class="mb-0">¿Todo listo?<a href="<?php echo APP_URL ?>" class="text-white-50 fw-bold"> Inicia Sesión</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>