<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <div class="mb-md-5 mt-md-4 pb-5">
                            <h2 class="fw-bold mb-2 text-uppercase">Inicia Sesión</h2>
                            <p class="text-white-50 mb-5">Porfavor ingresa tu usuario y contraseña</p>
                            <form action="" method="POST">
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <input type="text" name="login_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required class="form-control form-control-lg" />
                                    <label class="form-label" for="login_usuario">Usuario</label>
                                </div>

                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <input type="password" name="login_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required class="form-control form-control-lg" />
                                    <label class="form-label" for="login_clave">Password</label>
                                </div>
                                <div>
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5 mb-5" type="submit">Login</button>
                                </div>
                                
                                <div>
                                    <p class="mb-0">¿No tienes una cuenta? <a href="<?php echo APP_URL?>userRegister/" class="text-white-50 fw-bold"> Registrate ahora</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
	if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
		$insLogin->iniciarSesionControlador();
	}
?>