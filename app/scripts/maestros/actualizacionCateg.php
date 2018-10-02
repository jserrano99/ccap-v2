<?php

include_once __DIR__ . '../../funcionesDAO.php';

function insertCategUnif() {
    global $JanoUnif, $CATEG;
    try {
        $sentencia = " insert into categ "
                . " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
                . " ,ocupacion, mir, condicionado, directivo ) values "
                . " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :GrupoCot, :epiacc, :grupoprof, :enuso, :grupocobro "
                . " ,:ocupacion, :mir, :condicionado, :directivo )";
        $insert = $JanoUnif->prepare($sentencia);

        $params = array(":codigo" => $CATEG["codigo"],
            ":catgen" => $CATEG["catgen"],
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $CATEG["catanexo"],
            ":GrupoCot" => $CATEG["GrupoCot"],
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
            echo " --> CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " CREADA EN LA B.D. UNIFICADA \n";
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

function equivalenciasCateg($CATEG, $edificio) {
    $edificio_id = selectEdificio($edificio);

    $catgen = selectEqCatGen($CATEG["catgen_id"], $edificio_id);
    $catanexo = selectEqCatAnexo($CATEG["catanexo_id"], $edificio_id);
    $GrupoCot = selectEqGrupoCot($CATEG["grupocot_id"], $edificio_id);
    //$epiacc = selectEqEpiAcc($CATEG["epiacc"],$edificio);
    $grupoprof = selectEqGrupoProf($CATEG["grupoprof_id"], $edificio_id);
    $grupocobro = selectEqGrupoCobro($CATEG["grupocobro_id"], $edificio_id);
    $ocupacion = selectEqOcupacion($CATEG["ocupacion_id"], $edificio_id);

    if ($catgen && $catanexo && $GrupoCot && $grupoprof && $grupocobro && $ocupacion) {
        $equivalenciasCateg["catgen"] = $catgen;
        $equivalenciasCateg["catanexo"] = $catanexo;
        $equivalenciasCateg["GrupoCot"] = $GrupoCot;
        $equivalenciasCateg["grupoprof"] = $grupoprof;
        $equivalenciasCateg["grupocobro"] = $grupocobro;
        $equivalenciasCateg["ocupacion"] = $ocupacion;

        echo "EQUIVALENCIAS\n";
        echo "-------------\n";
        echo " catgen = " . $CATEG["catgen"] . "/" . $catgen . "\n";
        echo " catanexo = " . $CATEG["catanexo"] . "/" . $catanexo . "\n";
        echo " grupocot = " . $CATEG["grupocot"] . "/" . $GrupoCot . "\n";
        echo " grupoprof = " . $CATEG["grupoprof"] . "/" . $grupoprof . "\n";
        echo " grupocobro = " . $CATEG["grupocobro"] . "/" . $grupocobro . "\n";
        echo " ocupacion = " . $CATEG["ocupacion"] . "/" . $ocupacion . "\n";

        return $equivalenciasCateg;
    } else {
        return null;
    }
}

function insertCategAreas($CATEG, $conexion, $edificio) {
    try {
        $sentencia = " insert into categ "
                . " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
                . " ,ocupacion, mir, condicionado, directivo ) values "
                . " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :grupocot, :epiacc, :grupoprof, :enuso, :grupocobro "
                . " ,:ocupacion, :mir, :condicionado, :directivo )";

        $insert = $conexion->prepare($sentencia);

        $params = parametrosCateg($CATEG, $edificio);

        if (!$params)
            return false;

        $res = $insert->execute($params);
        if ($res) {
            echo " => INSERT CATEGORIA PROFESIONAL(CATEG) " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . "\n";
            return true;
        } else {
            echo "**ERROR EN INSERT CATEG CODIGO=" . $CATEG["codigo"] . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CATEG CODIGO= " . $CATEG["codigo"] . " " . $CATEG["descrip"] . $ex->getMessage() . "\n";
        return false;
    }
}

function parametrosCateg($CATEG, $edificio) {

    $equivalenciasCateg = equivalenciasCateg($CATEG, $edificio);
    if (!$equivalenciasCateg) {
        echo "**ERROR AL ESTABLECER LAS EQUIVALENCIAS \n";
        return null;
    }

    $params = array(":codigo" => $CATEG["codigo"],
        ":catgen" => $equivalenciasCateg["catgen"],
        ":descrip" => $CATEG["descripcion"],
        ":fsn" => $CATEG["fsn"],
        ":catanexo" => $equivalenciasCateg["catanexo"],
        ":grupocot" => $equivalenciasCateg["grupocot"],
        ":epiacc" => $CATEG["epiacc"],
        ":grupoprof" => $equivalenciasCateg["grupoprof"],
        ":enuso" => $CATEG["enuso"],
        ":grupocobro" => $equivalenciasCateg["grupocobro"],
        ":ocupacion" => $equivalenciasCateg["ocupacion"],
        ":mir" => $CATEG["mir"],
        ":condicionado" => $CATEG["condicionado"],
        ":directivo" => $CATEG["directivo"]);

    return $params;
}

function updateEqCategControl($Categ) {
    global $JanoControl;
    try {
        $sql = " update gums_eq_categ set "
                . " codigo_loc = :codigo_loc"
                . " ,enuso = :enuso"
                . " where id = :id ";
        $query = $JanoControl->prepare($sql);
        $params = array(":codigo_loc" => $Categ["codigo"],
            ":id" => $EqCateg["id"],
            ":enuso" => $EqCateg["enuso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE GUMS_EQ_CATEG CODIGO_LOC=(" . $EqCateg["codigo_loc"] . ") EDIFICIO=(" . $EqCateg["edificio"] . ") Uso= (" . $EqCateg["uso"] . ") \n";
            return null;
        }
        echo "-->UPDATE GUMS_EQ_CATEG CODIGO_LOC=(" . $EqCateg["codigo_loc"] . ") EDIFICIO=(" . $EqCateg["edificio"] . ") Uso= (" . $EqCateg["uso"] . ") \n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE GUMS_EQ_CATEG CATEG= " . $codigo . " EDIFICIO=" . $edificio . "\n"
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
        echo "****ERROR EN LA ACTUALIZACIÓN EN LA BASE DE DATOS UNIFICADA \n";
        return false;
    }

    if ($CATEG["replica"] == 1) { /* SE REPLICA EN TODAS LAS BASES DE DATOS */
        $inicio = 0;
        $fin = 12;
    }
    if ($CATEG["replica"] == 2) { /* SE REPLICA SOLO EN EL AREA ÚNICA */
        $inicio = 0;
        $fin = 1;
    }
    if ($CATEG["replica"] == 3) { /* SE REPLICA EN TODAS LAS AREAS EXCEPTO EN EL AREA ÚNICA */
        $inicio = 1;
        $fin = 12;
    }

    for ($i = $inicio; $i < $fin; $i++) {
        echo "-->(" . $i . ") Equivalencia Código " . $CATEG["codigo"] . " \n";
        $codigo = selectEqCateg($CATEG["id"], selectEdificio($i));
        if ($codigo) {
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
                . " ,grupcot = :grupocot"
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
            ":grupocot" => $CATEG["grupocot"],
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
            echo "--> CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " MODIFICADA BASE DE DATOS UNIFICADA\n";
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
                . " ,grupcot = :grupocot"
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
            echo " => CATEGORIA " . $codigo . " " . $CATEG["descripcion"] . " MODIFICADA \n";
            return true;
        } else {
            echo "***ERROR EN UPDATE CATEGORIA " . $codigo . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE CATEGORIA PROFESIONAL (CATEG) " . $codigo . " " . $CATEG["descrip"] . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoInsert() {
    global $tipobd, $CATEG, $modo;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!insertCategUnif()) {
        echo "***ERROR EN LA INSERT EN LA BASE DE DATOS UNIFICADA \n";
        return null;
    }
    /*
     * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas 
     */
    if ($CATEG["replica"] == 1) { /* SE REPLICA EN TODAS LAS BASES DE DATOS */
        $inicio = 0;
        $fin = 12;
    }
    if ($CATEG["replica"] == 2) { /* SE REPLICA SOLO EN EL AREA ÚNICA */
        $inicio = 0;
        $fin = 1;
    }
    if ($CATEG["replica"] == 3) { /* SE REPLICA EN TODAS LAS AREAS EXCEPTO EN EL AREA ÚNICA */
        $inicio = 1;
        $fin = 12;
    }

    for ($i = $inicio; $i < $fin; $i++) {
        $conexion = conexionEdificio($i, $tipobd);
        if ($conexion) {
            if (insertCategAreas($CATEG, $conexion, $i)) {
                insertEqCateg($CATEG, $i);
                updateEqCategControl($CATEG, $i);
            }
        }
    }

    return true;
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
$eqcateg_id = $argv[4];

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

echo "==> CATEGORIA PROFESIONAL(CATEG) : ID=" . $CATEG["id"]
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

echo "==> ACTUACION= (" . $actuacion . ") REPLICA= (" . $CATEG["replica"] . ") EQUIVALENCIA(EQCATEG_ID)= " . $eqcateg_id . "\n";


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

if ($actuacion == 'ACTIVAR') {
    $EqCateg = selectEqCategById($eqcateg_id);
    $conexion = conexionEdificio($EqCateg["edificio"], $tipobd);
    $Categ["enuso"] = 'S';
    if ($conexion) {
        updateCateg($conexion, $EqCateg["codigo_loc"]);
        $EqCateg["enuso"] = 'S';
        updateEqCategControl($EqCateg);
    }
}

if ($actuacion == 'DESACTIVAR') {
    $EqCateg = selectEqCategById($eqcateg_id);
    $conexion = conexionEdificio($EqCateg["edificio"], $tipobd);
    $Categ["enuso"] = 'N';
    if ($conexion) {
        updateCateg($conexion, $EqCateg["codigo_loc"]);
        $EqCateg["enuso"] = 'N';
        updateEqCategControl($EqCateg);
    }
}

if ($actuacion == 'CREAR') {
    $EqCateg = selectEqCategById($eqcateg_id);
    $conexion = conexionEdificio($EqCateg["edificio"], $tipobd);
    $Categ["codigo"] = $EqCateg["codigo_loc"];
    if ($conexion) {
        if (insertCategAreas($CATEG, $conexion, $EqCateg["edificio"])) {
            $EqCateg["enuso"] = 'S';
            updateEqCategControl($EqCateg);
        }
    }
}


echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit(0);
