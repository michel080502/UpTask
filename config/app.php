<?php
#DEFINIMOS INFROMACION DE LA APLICACION

$url = 'http://'.gethostbyname(gethostname()).'/UpTask/';
define("APP_URL", value: $url);

//const APP_URL="http://localhost/UpTask/";
const APP_NAME="UpTask";
const APP_SESSION_NAME="CRUD";

date_default_timezone_set("America/Mexico_City");
