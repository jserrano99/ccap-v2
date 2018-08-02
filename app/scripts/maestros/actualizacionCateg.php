<?php

include_once __DIR__ . '/funcionesDAO.php';
include_once __DIR__ . '/funcionesCateg.php';

function updateEqCategControl($codigo, $edificio) {
    global $JanoControl;
    $edificio_id = selectEdificioId();
    $categ_id = selectCategId($codigo);

    try {
        $sql = " update ccap_eq_categ set "
                . " codigo_loc = :codigo_loc"
                . " where categ_id = :categ_id "
                . " and edificio_id = :edificio_id";
        $query = $JanoControl->prepare($sql);
        $params = array(":codigo_loc" => $codigo,
            ":categ_id" => $categ_id,
            ":edificio_id" => $edificio_id);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE CCAP_EQ_CATEG CATEG= " . $codigo . " EDIFICIO=" . $edificio . "\n";
            return null;
        }
        echo "UPDATE CCAP_EQ_CATEG CATEG = " . $codigo . " EDIFICIO=" . $edificio . "\n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE CCAP_EQ_CATEG CATEG= " . $codigo . " EDIFICIO=" . $edificio . "\n"
        . $ex->getMessage() . "\n";
        return null;
    }
}

function procesoUpdate() {
    global $CATEG, $JanoUnif, $JanoControl, $tipobd;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!updateCategUnif($CATEG)) {
        echo " ERROR EN LA ACTUALIZACIÓN EN LA BASE DE DATOS UNIFICADA \n";
        return false;
    }

    if ($CATEG["replica"] == 1) { /* SE REPLICA EN TODAS LAS BASES DE DATOS */
        $inicio = 0;
        $fin = 11;
    }
    if ($CATEG["replica"] == 2) { /* SE REPLICA SOLO EN EL AREA ÚNICA */
        $inicio = 0;
        $fin = 1;
    }
    if ($CATEG["replica"] == 3) { /* SE REPLICA EN TODAS LAS AREAS EXCEPTO EN EL AREA ÚNICA */
        $inicio = 1;
        $fin = 11;
    }

    for ($i = $inicio; $i < $fin; $i++) {
        $codigo = selectEqCateg($CATEG["codigo"], $i);
        if ($codigo) {
            echo "-->Equivalencia Código \n";
            echo "-->Codigo = " . $CATEG["codigo"] . "/" . $codigo . "\n";
            $conexion = conexionEdificio($i, $tipobd);
            if ($conexion) {
                updateCategAreas($CATEG, $conexion, $codigo, $i);
            }
        }
    }

    return true;
}

function updateCategUnif($CATEG) {
    global $JanoUnif;
    try {
        $sentencia = " update categ set"
                . "  catgen = :catgen "
                . " ,descrip = :descrip"
                . " ,fsn = :fsn "
                . " ,catanexo = :catanexo"
                . " ,grupcot = :grupcot"
                . " ,epiacc = :epiacc"
                . " ,grupoprof = :grupoprof"
                . " ,enuso = :enuso"
                . " ,grupocobro = :grupocobro "
                . " ,ocupacion = :ocupacion"
                . " ,mir = :mir "
                . " ,condicionado = :condicionado"
                . " ,directivo = :directivo"
                . " where codigo = :codigo ";
        $update = $JanoUnif->prepare($sentencia);

        $params = array(":codigo" => $CATEG["codigo"],
            ":catgen" => $CATEG["catgen"],
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $CATEG["catanexo"],
            ":grupcot" => $CATEG["grupocot"],
            ":epiacc" => $CATEG["epiacc"],
            ":grupoprof" => $CATEG["grupoprof"],
            ":enuso" => $CATEG["enuso"],
            ":grupocobro" => $CATEG["grupocobro"],
            ":ocupacion" => $CATEG["ocupacion"],
            ":mir" => $CATEG["mir"],
            ":condicionado" => $CATEG["condicionado"],
            ":directivo" => $CATEG["directivo"]);

        $res = $update->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " MODIFICADA BASE DE DATOS UNIFICADA\n";
            return true;
        } else {
            echo "****ERROR EN UPDATE BASE DE DATOS UNIFICADA CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "****PDOERROR EN UPDATE BASE DE DATOS UNIFICADA CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"] . $ex->getMessage() . "\n";
        return false;
    }
}

function updateCategAreas($CATEG, $conexion, $codigo, $edificio) {
    try {
        $sentencia = " update categ set "
                . "  catgen = :catgen "
                . " ,descrip = :descrip"
                . " ,fsn = :fsn "
                . " ,catanexo = :catanexo"
                . " ,grupcot = :grupcot"
                . " ,epiacc = :epiacc"
                . " ,grupoprof = :grupoprof"
                . " ,enuso = :enuso"
                . " ,grupocobro = :grupocobro "
                . " ,ocupacion = :ocupacion "
                . " ,mir = :mir "
                . " ,condicionado = :condicionado"
                . " ,directivo = :directivo"
                . " where codigo = :codigo ";
        $update = $conexion->prepare($sentencia);

        $params = parametrosCateg($CATEG, $edificio);

        if (!$params)
            return false;

        $res = $update->execute($params);
        if ($res) {
            echo " CATEGORIA " . $codigo . " " . $CATEG["descripcion"] . " MODIFICADA EN LA B.D. \n";
            return true;
        } else {
            echo "  ERROR EN UPDATE CATEGORIA " . $codigo . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN UPDATE CATEGORIA " . $codigo . " " . $CATEG["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoInsert() {
    global $tipobd, $CATEG, $modo;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!insertCategUnif()) {
        echo " ERROR EN LA INSERT EN LA BASE DE DATOS UNIFICADA \n";
    }
    /*
     * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas 
     */
    if ($CATEG["replica"] == 1) { /* SE REPLICA EN TODAS LAS BASES DE DATOS */
        $inicio = 0;
        $fin = 11;
    }
    if ($CATEG["replica"] == 2) { /* SE REPLICA SOLO EN EL AREA ÚNICA */
        $inicio = 0;
        $fin = 1;
    }
    if ($CATEG["replica"] == 3) { /* SE REPLICA EN TODAS LAS AREAS EXCEPTO EN EL AREA ÚNICA */
        $inicio = 1;
        $fin = 11;
    }

    for ($i = $inicio; $i < $fin; $i++) {
        $conexion = conexionEdificio($i, $tipobd);
        if ($conexion) {
            if (insertCategAreas($CATEG, $conexion, $i)) {
                insertEqCateg($CATEG, $i);
                updateEqCategControl($CATEG["codigo"], $i);
            }
        }
    }

    return true;
}

function insertCategUnif() {
    global $JanoUnif, $CATEG;
    try {
        $sentencia = " insert into categ "
                . " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
                . " ,ocupacion, mir, condicionado, directivo ) values "
                . " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :grupcot, :epiacc, :grupoprof, :enuso, :grupocobro "
                . " ,:ocupacion, :mir, :condicionado, :directivo )";
        $insert = $JanoUnif->prepare($sentencia);

        $params = array(":codigo" => $CATEG["codigo"],
            ":catgen" => $CATEG["catgen"],
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $CATEG["catanexo"],
            ":grupcot" => $CATEG["grupocot"],
            ":epiacc" => $CATEG["epiacc"],
            ":grupoprof" => $CATEG["grupoprof"],
            ":enuso" => $CATEG["enuso"],
            ":grupocobro" => $CATEG["grupocobro"],
            ":ocupacion" => $CATEG["ocupacion"],
            ":mir" => $CATEG["mir"],
            ":condicionado" => $CATEG["condicionado"],
            ":directivo" => $CATEG["directivo"]);

        $res = $insert->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " CREADA EN LA B.D. UNIFICADA \n";
            return true;
        } else {
            echo "**ERROR EN INSERT CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        if ($ex->getCode() == '23000') {
            updateCategUnif($CATEG);
            return true;
        } else {
            echo "***PDOERROR EN INSERT BASE DE DATOS UNIFICADA CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . "\n"
            . $ex->getMessage() . "\n";
            return false;
        }
    }
}

function insertEqCateg($CATEG, $area) {
    global $JanoInte;
    try {
        $sentencia = " insert into eq_categ "
                . " ( edificio, codigo_loc, codigo_uni ) "
                . " values "
                . " ( :edificio, :codigo_loc, :codigo_uni ) "
        ;
        $insert = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $area,
            ":codigo_loc" => $CATEG["codigo"],
            ":codigo_uni" => $CATEG["codigo"]);
        $res = $insert->execute($params);
        if ($res) {
            echo " EQUIVALENCIA GENERADA EDIFICIO= " . $area
            . " CODIGO_LOC = " . $CATEG["codigo"]
            . " CODIGO_UNI = " . $CATEG["codigo"] . "\n";
            return true;
        } else {
            echo "  ERROR EN INSERT EQ_CATEG " . $CATEG["codigo"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN INSERT EQ_CATEG " . $CATEG["codigo"] . $ex->getMessage() . "\n";
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
$categ_id = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    echo " ++++ PRODUCCIÓN ++++ \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo " ++++ VALIDACIÓN ++++ \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

$CATEG = selectCateg($categ_id);

echo "==> CATEGORIA PROFESIONAL : ID=" . $CATEG["id"]
 . " CODIGO= " . $CATEG["codigo"]
 . " DESCRIPCION= " . $CATEG["descripcion"]
 . " CATGEN= " . $CATEG["catgen"]
 . " CATANEXO= " . $CATEG["catanexo"]
 . " GRUPOCOT= " . $CATEG["grupocot"]
 . " GRUPOPROF= " . $CATEG["grupoprof"]
 . " GRUPOCOBRO= " . $CATEG["grupocobro"]
 . " OCUPACION= " . $CATEG["ocupacion"]
 . " EPIACC= " . $CATEG["epiacc"]
 . " REPLICA = " . $CATEG["replica"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";


if ($actuacion == 'INSERT') {
    if (!procesoInsert()) {
        echo "  +++++++++++ TERMINA PROCESO INSERT EN ERROR +++++++++++++ \n";
    }
}

if ($actuacion == 'UPDATE') {
    if (!procesoUpdate()) {
        echo "  +++++++++++ TERMINA PROCESO UPDATE EN ERROR +++++++++++++ \n";
    }
}

echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit(0);
