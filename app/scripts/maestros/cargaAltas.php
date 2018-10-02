<?php

include_once __DIR__ . '../../funcionesDAO.php';

function selectAltasEnuso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from altas as t1 "
                . " where t1.codigo = :codigo";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["ENUSO"];
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR NO EN SELECT ALTAS=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}



function insertEqAltas($EqAltas) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_eq_altas "
                . " (edificio_id, codigo_loc, altas_id, enuso ) values  ( :edificio_id, :codigo_loc, :altas_id, :enuso )";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqAltas["edificio_id"],
            ":codigo_loc" => $EqAltas["codigo_loc"],
            ":altas_id" => $EqAltas["altas_id"],
            ":enuso" => $EqAltas["enuso"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT GUMS_EQ_ALTAS EDIFICIO= " . $EqAltas["edificio"] . " CODIGO_LOC = " . $EqAltas["codigo_loc"] . " Uso= (" . $EqAltas["enuso"] . ") \n";
        }
        echo "GENERADA EQUIVALENCIA GUMS_EQ_ALTAS EDIFICIO= (" . $EqAltas["edificio"] . ") CODIGO_LOC= (" . $EqAltas["codigo_loc"] . ") Uso= (" . $EqAltas["enuso"] . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_EQ_ALTAS " . $ex->getMessage() . "\n";
        return null;
    }
}

function insertAltas($Alta) {
    global $JanoControl;
    try {

        $sentencia = " insert into gums_altas "
                . " ( codigo, descripcion, btc_mcon_codigo, btc_tipocon, subaltas_afi "
                . "  , subaltas_certi, certificar, enuso, motivoaltarptid"
                . "  ,malrpt_codigo, malrpt_descripcion, l13, rel_juridica, pagar_tramo"
                . "  ,destino, modocupa_id, modopago_id, movipat_id ) values "
                . " ( :codigo, :descripcion, :btc_mcon_codigo, :btc_tipocon, :subaltas_afi "
                . "  ,:subaltas_certi, :certificar, :enuso, :motivoaltarptid"
                . "  ,:malrpt_codigo, :malrpt_descripcion, :l13, :rel_juridica, :pagar_tramo"
                . "  ,:destino, :modocupa_id, :modopago_id, :movipat_id )";

        $query = $JanoControl->prepare($sentencia);

        $params = array(':codigo' => $Alta["CODIGO"]
            , ':descripcion' => $Alta["DESCRIP"]
            , ':btc_mcon_codigo' => $Alta["BTC_MCON_CODIGO"]
            , ':btc_tipocon' => $Alta["BTC_TIPOCON"]
            , ':subaltas_afi' => $Alta["SUBALTAS_AFI"]
            , ':subaltas_certi' => $Alta["SUBALTAS_CERTI"]
            , ':certificar' => $Alta["CERTIFICAR"]
            , ':enuso' => $Alta["ENUSO"]
            , ':motivoaltarptid' => $Alta["MOTIVOALTARPTID"]
            , ':malrpt_codigo' => $Alta["MALRPT_CODIGO"]
            , ':malrpt_descripcion' => $Alta["MALRPT_DESCRIPCION"]
            , ':l13' => $Alta["L13"]
            , ':rel_juridica' => $Alta["REL_JURIDICA"]
            , ':pagar_tramo' => $Alta["PAGAR_TRAMO"]
            , ':destino' => $Alta["DESTINO"]
            , ':modocupa_id' => selectModOcupa($Alta["MODOCUPA"])
            , ':modopago_id' => selectModoPago($Alta["MODOPAGO"])
            , ':movipat_id' => selectMoviPat($Alta["PATRONAL"]));

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_ALTAS CODIGO= " . $Alta["CODIGO"] . " \n";
            return null;
            ;
        }
        $Alta["ID"] = $JanoControl->lastInsertId();
        echo " CREADO GUMS_ALTAS ID= " . $Alta["ID"] . " CODIGO= " . $Alta["CODIGO"] . " " . $Alta["DESCRIP"] . "\n";
        return $Alta["ID"];
    } catch (PDOException $ex) {
        echo "**ERROR EN INSERT GUMS_ALTAS CODIGO= " . $Alta["CODIGO"] . $ex->getMessage() . " \n";
        return null;
    }
}

echo " -- CARGA INICIAL TABLA: GUMS_ALTAS " . "\n";
$JanoControl = jano_ctrl();
if (!$JanoControl) {
    exit(1);
}

/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];

if ($tipo == 'REAL') {
    echo " ENTORNO = PRODUCCIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo " ENTORNO = VALIDACIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

try {
    $sentencia = " delete from gums_eq_altas";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_EQ_ALTAS " . $ex->getMessage() . "\n";
    exit(1);
}

try {
    $sentencia = " delete from gums_altas";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_ALTAS " . $ex->getMessage() . "\n";
    exit(1);
}

/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);


try {
    $sentencia = " select * from altas";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $AltaAll = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN LA SELECT DE ALTAS BASE DE DATOS UNIFICADA " . $ex->getMessage() . "\n";
    exit(1);
}

echo " Registros a Cargar = " . count($AltaAll) . "\n";
foreach ($AltaAll as $Alta) {
    $id = insertAltas($Alta);
    $Alta["ID"]=$id;
    if ($id == null) {
        continue;
    }
    foreach ($EdificioAll as $Edificio) {
        $sql = " select * from eq_altas where codigo_uni = :codigo_uni and edificio = :edificio";
        $query = $JanoInte->prepare($sql);
        $params = array(":codigo_uni" => $Alta["CODIGO"],
            ":edificio" => $Edificio["codigo"]);
        $query->execute($params);
        $EqAltasAll = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($EqAltasAll) == 0) {
            $EqAltas["edificio_id"] = $Edificio["id"];
            $EqAltas["edificio"] = $Edificio["codigo"];
            $EqAltas["codigo_loc"] = "XXX";
            $EqAltas["codigo_uni"] = $Alta["CODIGO"];
            $EqAltas["altas_id"] = $Alta["ID"];
            $EqAltas["enuso"] = "X";
            insertEqAltas($EqAltas);
        } else {
            $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
            if ($conexion) {
                foreach ($EqAltasAll as $rowEq) {
                    $EqAltas["edificio_id"] = $Edificio["id"];
                    $EqAltas["edificio"] = $Edificio["codigo"];
                    $EqAltas["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqAltas["codigo_uni"] = $Alta["CODIGO"];
                    $EqAltas["altas_id"] = $Alta["ID"];
                    $EqAltas["enuso"] = selectAltasEnuso($conexion, $rowEq["CODIGO_LOC"]);
                    insertEqAltas($EqAltas);
                }
            }
        }
    }
}

    echo " TERMINADA LA CARGA DE ALTAS " . "\n";
    exit(0);

    