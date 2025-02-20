<div class="container-fluid mb-6">
    <h1 class="display-4">Tareas</h1>
    <h2 class="lead">Ingresa la fecha por la cual estas buscando las tareas</h2>
</div>

<div class="container-fluid pb-6 pt-6">
    <?php

    use app\controllers\userController;

    $insUsuario = new userController();

    if (!isset($_SESSION[$url[0]]) && empty($_SESSION[$url[0]])) {
    ?>
        <div class="row">
            <div class="col">
                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off">
                    <input type="hidden" name="modulo_buscador" value="buscar">
                    <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                    <div class="input-group">
                        <input class="form-control rounded" type="date" name="date_buscador" required>
                        <div class="input-group-append">
                            <button class="btn btn-info" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col">
                <form class="text-center mt-6 mb-6 FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off">
                    <input type="hidden" name="modulo_buscador" value="eliminar">
                    <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>" <p>Estás buscando <strong>“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                    <br>
                    <button type="submit" class="btn btn-danger rounded-pill mb-4">Eliminar búsqueda</button>
                </form>
            </div>
        </div>
    <?php
    }
    ?>
</div>