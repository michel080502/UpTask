<?php
// Registra una función de autocarga personalizada
spl_autoload_register(function ($clase) {
    // Construye la ruta del archivo de la clase
    $archivo = __DIR__ . "/" . $clase . ".php";
    
    // Reemplaza las barras invertidas en el nombre de la clase con barras inclinadas para la compatibilidad con diferentes sistemas operativos
    $archivo = str_replace("\\", "/", $archivo);

    // Verifica si el archivo de la clase existe
    if (is_file($archivo)) {
        // Incluye el archivo de la clase si existe
        require_once $archivo;
    }
});

