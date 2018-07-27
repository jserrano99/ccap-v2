<?php

include_once __DIR__ . '/funcionesDAO.php';
include_once __DIR__ . '/funcionesCateg.php';

function procesoInsert() {
    global $JanoUnif, $JanoControl, $tipobd, $edificio, $CATEG, $EQCATEG;
    $conexion = conexionEdificio($edificio, $tipobd);
    
    if (insertCategAreas($CATEG,$conexion, $edificio)) {
        insertEqCateg();
    }

    return true;
}


function insertEqCateg() {
    global $JanoInte, $EQCATEG;
    try {
        $sentencia = " insert into eq_categ "
                . " ( edificio, codigo_loc, codigo_uni ) "
                . " values "
                . " ( :edificio, :codigo_loc, :codigo_uni ) "
        ;
        $insert = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $EQCATEG["edificio"],
            ":codigo_loc" => $EQCATEG["codigo_loc"],
            ":codigo_uni" => $EQCATEG["codigo_uni"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT EQ_CATEG EDIFICIO = " . $EQCATEG["edificio"]
            . " CODIGO_LOC = " . $EQCATEG["codigo_loc"]
            . " CODIGO_UNI = " . $EQCATEG["codigo_uni"] . "\n";
            return false;
        }

        echo "-EQUIVALENCIA GENERADA EDIFICIO= " . $area
        . " CODIGO_LOC = " . $EQCATEG["codigo_loc"]
        . " CODIGO_UNI = " . $EQCATEG["codigo_uni"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT EQ_CATEG EDIFICIO = " . $EQCATEG["edificio"]
        . " CODIGO_LOC = " . $EQCATEG["codigo_loc"]
        . " CODIGO_UNI = " . $EQCATEG["codigo_uni"] . "\n"
        . $ex->getMessage()
        . "\n";
        return false;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */

$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO REPLICA DE CATEGORIA PROFESIONAL (CATEG) ++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$eqCateg_id = $argv[2];
$edificio = $argv[3];

if ($tipo == 'REAL') {
    echo " **** PRODUCCIÓN **** \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo " ++++ VALIDACIÓN ++++ \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

$EQCATEG = selectEqCategById($eqCateg_id);
$CATEG = selectCateg($EQCATEG["categ_id"]);
$CATEG["codigo"] = $EQCATEG["codigo_loc"]; /* PONEMOS COMO CODIGO DE CATEGORIA LA EQUIVALENCIA CORRESPONDIENTE */

echo "-CATEGORIA PROFESIONAL : ID=" . $CATEG["id"]
 . " CODIGO= " . $CATEG["codigo"]
 . " DESCRIPCION= " . $CATEG["descripcion"]
 . " CATGEN= " . $CATEG["catgen"]
 . " CATANEXO= " . $CATEG["catanexo"]
 . " GRUPOCOT= " . $CATEG["grupocot"]
 . " GRUPOPROF= " . $CATEG["grupoprof"]
 . " GRUPOCOBRO= " . $CATEG["grupocobro"]
 . " OCUPACION= " . $CATEG["ocupacion"]
 . " EPIACC= " . $CATEG["epiacc"]
 . " \n";

echo "-EDIFICIO = " . $edificio . "\n";

if (!procesoInsert()) {
    echo "  ******* TERMINA PROCESO CREAR CATEGORIA CON ERRORES ***** \n";
    exit(1);
}

echo "  ---- TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL --- \n";
exit(0);
