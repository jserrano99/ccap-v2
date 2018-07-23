<?php

include_once __DIR__ . '/funcionesDAO.php';

function procesoUpdate($CATGEN) {
    global $JanoUnif, $JanoControl, $tipo;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!updateCateg($CATGEN, $CATGEN["codigo"],$JanoUnif)) {
        echo " ERROR EN LA ACTUALIZACIÓN EN LA BASE DE DATOS UNIFICADA \n";
        return false;
    }
    /*
     * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas 
     */
    $BasesDatos = SelectBaseDatosAreas($JanoControl, $tipo);
    //var_dump($BasesDatos);
    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        echo " ==> Proceso para : " . $alias . "\n";
        $codigo = selectEqCatGen($CATGEN["codigo"],$baseDatos["edificio"]);
        echo " Equivalencia Código \n";
        echo " codigo = ".$CATGEN["codigo"]."/".$codigo."\n";
        if ($codigo) {
            updateCateg($CATGEN, $codigo, $conexion);
        }
    }
    return true;
}

function updateCateg($CATGEN,$codigo,$conexion) {
    try {
        $sentencia = " update catgen set "
                . "  descrip = :descrip"
                . ", btc_tbol_codigo = :btc_tbol_codigo"
                . ", enuso = :enuso"
                . ", plan_org = :plan_org"
                . ", cod_insalud = :cod_insalud"
                . ", des_insalud = :des_insalud"
                . ", especialidad = :especialidad"
                . ", codigo_sms = :codigo_sms"
                ." where codigo = :codigo";
                
        $update = $conexion->prepare($sentencia);
        
        $params = array(":codigo" => $CATGEN["codigo"],
            ":descrip" => $CATGEN["descripcion"],
            ":btc_tbol_codigo" => $CATGEN["btc_tbol_codigo"],
            ":enuso" => $CATGEN["enuso"],
            ":plan_org" => $CATGEN["plan_org"],
            ":cod_insalud" => $CATGEN["cod_insalud"],
            ":des_insalud" => $CATGEN["des_insalud"],
            ":especialidad" => $CATGEN["especialidad"],
            ":codigo_sms" => $CATGEN["especialidad"]);
        
        $res = $update->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATGEN["codigo"] . " " . $CATGEN["descripcion"] . " MODIFICADA \n";
            return true;
        } else {
            echo "  ERROR EN UPDATE CATEGORIA " . $CATGEN["codigo"]. " " . $CATGEN["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN UPDATE CATEGORIA " . $CATGEN["codigo"] . " " . $CATGEN["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoInsert($CATGEN) {
    global $JanoUnif, $JanoControl, $tipo;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!insertCateg($CATGEN,$JanoUnif)) {
        echo " ERROR EN LA INSERCIÓN EN LA BASE DE DATOS UNIFICADA \n";
        return false;
    }
    /*
     * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas 
     */

    for ($i = 0; $i < 11; $i++) {
        if (!insertEqCateg($CATGEN, $i)) {
            return false;
        }
    }
    /*
     * Insert en la tabla categ de en cada una de las areas 
     */
    $BasesDatos = SelectBaseDatosAreas($JanoControl, $tipo);
    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        echo " ==> Proceso para : " . $alias . "\n";
        insertCateg($CATGEN, $conexion);
    }
    
    return true;
}


function insertCateg($CATGEN,$conexion) {
    try {
        $sentencia = " insert into catgen "
                . " ( codigo, descrip, btc_tbol_codigo, enuso, plan_org, cod_insalud, des_insalud, especialidad, codigo_sms) values "
                . " ( :codigo, :descrip, :btc_tbol_codigo, :enuso, :plan_org, :cod_insalud, :des_insalud, :especialidad, :codigo_sms)";
                
        $insert = $conexion->prepare($sentencia);
        
        $params = array(":codigo" => $CATGEN["codigo"],
            ":descrip" => $CATGEN["descripcion"],
            ":btc_tbol_codigo" => $CATGEN["btc_tbol_codigo"],
            ":enuso" => $CATGEN["enuso"],
            ":plan_org" => $CATGEN["plan_org"],
            ":cod_insalud" => $CATGEN["cod_insalud"],
            ":des_insalud" => $CATGEN["des_insalud"],
            ":especialidad" => $CATGEN["especialidad"],
            ":codigo_sms" => $CATGEN["especialidad"]);
            
        $res = $insert->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATGEN["codigo"] . " " . $CATGEN["descripcion"] . "\n";
            return true;
        } else {
            echo "  ERROR EN INSERT CATEGORIA " . $CATGEN["codigo"] . " " . $CATGEN["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN INSERT CATEGORIA " . $CATGEN["codigo"] . " " . $CATGEN["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}

function insertEqCateg($CATGEN, $area) {
    global $JanoInte;
    try {
        $sentencia = " insert into eq_catgen "
                . " ( edificio, codigo_loc, codigo_uni ) "
                . " values "
                . " ( :edificio, :codigo_loc, :codigo_uni ) "
        ;
        $insert = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $area,
            ":codigo_loc" => $CATGEN["codigo"],
            ":codigo_uni" => $CATGEN["codigo"]);
        $res = $insert->execute($params);
        if ($res) {
            echo " EQUIVALENCIA GENERADA EDIFICIO= " . $area
            . " CODIGO_LOC = " . $CATGEN["codigo"]
            . " CODIGO_UNI = " . $CATGEN["codigo"] . "\n";
            return true;
        } else {
            echo "  ERROR EN INSERT EQ_CATGEN " . $CATGEN["codigo"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN INSERT EQ_CATGEN " . $CATGEN["codigo"] . $ex->getMessage() . "\n";
        return false;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO REPLICA DE CATEGORIA GENERAL (CATGEN) ++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$catgen = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos( 2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos( 1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

$CATGEN = selectCatGen($catgen);

echo "==> CATEGORIA PROF : ID=" . $CATGEN["id"]
 . " CODIGO= " . $CATGEN["codigo"]
 . " DESCRIPCION= " . $CATGEN["descripcion"]
 . " USO= ". $CATGEN["enuso"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ( $actuacion == 'INSERT') {
    if ( !procesoInsert($CATGEN) ) {
       echo "  +++++++++++ TERMINA PROCESO INSERT EN ERROR +++++++++++++ \n";
       exit(1);
    }
}

if ( $actuacion == 'UPDATE') {
    if ( !procesoUpdate($CATGEN) ) {
       echo "  +++++++++++ TERMINA PROCESO UPDATE EN ERROR +++++++++++++ \n";
       exit(1);
    }
}

echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit(0);
