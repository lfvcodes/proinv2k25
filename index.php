<?php
session_start();
if (!isset($_SESSION['pro'])):
  header("Location: ./views/login/");
else:
  #header("Location: ./app/herr/miempresa");
  header("Location: ./views/inicio/");
endif;

/*
#CODE_BY_LUIZ
date_default_timezone_set("America/Caracas");
require_once './app/util/cls_connection.php';
require_once './app/util/misc.php';
$conn = new Cls_connection;
$bdname = 'bd_proinv';
$rs = $conn->consultar("SHOW DATABASES LIKE '$bdname'");

$error = '<p>No se ha encontrado la base de datos del sistema en el servidor<br>'.
'Por favor cominiquese con el servicio tecnico autorizado del sistema para solucionar este problema</p>';

if ($rs->rowCount() > 0):
    
    $tablename = "pro_2empresa";
    $rs2 = $conn->consultar("SHOW TABLES LIKE '$tablename'");
    if($rs2->rowCount() > 0){
        $rs3 = $conn->consultar("SELECT rif_empresa,nom_empresa FROM pro_2empresa WHERE 1");
        if($rs3->rowCount() > 0){
            session_start();
            header( (isset($_SESSION['pro'])) ? "Location: ./app/inicio" : "Location: ./app/login/login" );
        }else{
            copyFile('./assets/vendor/css/theme-default-bak.css','./assets/vendor/css/theme-default.css');
            header("Location: ./app/herr/miempresa");
        }	
        
    }else{
        copyFile('./assets/vendor/css/theme-default-bak.css','./assets/vendor/css/theme-default.css');
        header("Location: ./app/herr/miempresa");
    }
    
else:
    echo $error;
endif;
*/