<?php

include_once __DIR__ . '../../funcionesDAO.php';

function insertModoPago($ModoPago) {
    global $JanoControl;
    try {

        $sentencia = " insert into gums_modopago "
                . " ( codigo, descripcion, modopago_mes ) values ( :codigo,:descripcion, :modopago_mes) ";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $ModoPago["CODIGO"],
            ":descripcion" => $ModoPago["DESCRIP"],
            ":modopago_mes" => $ModoPago["MODOPAGO_MES"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_MODOPAGO CODIGO= " . $ModoPago["CODIGO"] . " \n";
            return null;
        }
        $ModoPago["ID"] = $JanoControl->lastInsertId();
        echo " CREADO GUMS_MODOPAGO ID= (" . $ModoPago["ID"] . ") CODIGO= (" . $ModoPago["CODIGO"] . ") DESCRIPCION= (" . $ModoPago["DESCRIP"] . ") \n";
        return $ModoPago["ID"];
    } catch (PDOException $ex) {
        echo "***ERROR EN INSERT GUMS_MODOPAGO ERROR= " . $ex->getMessage() . "\n";
        return null;
    }
}

function insertEqModoPago($EqModoPago) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_eq_modopago "
                . " (edificio_id, codigo_loc, modopago_id ) values  ( :edificio_id, :codigo_loc, :modopago_id )";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqModoPago["edificio_id"],
            ":codigo_loc" => $EqModoPago["codigo_loc"],
            ":modopago_id" => $EqModoPago["modopago_id"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT GUMS_EQ_MODOPAGO EDIFICIO= " . $EqModoPago["edificio"] . " CODIGO_LOC = " . $EqModoPago["codigo_loc"] . "\n";
        }
        echo "GENERADA EQUIVALENCIA GUMS_EQ_MODOPAGO EDIFICIO = " . $EqModoPago["edificio"] . " CODIGO_LOC = " . $EqModoPago["codigo_loc"] . "\n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_EQ_MODOPAGO " . $ex->getMessage() . "\n";
        return null;
    }
}

/*
 * Cuerpo Principal 
 */

echo " -- CARGA INICIAL TABLA:  GUMS_MODOPAGO " . "\n";
$JanoControl = jano_ctrl();
if (!$JanoControl) {
    exit(111);
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
    $sentencia = " delete from gums_eq_modopago";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_EQ_MODOPAGO " . $ex->getMessage() . "\n";
    exit(1);
}

try {
    $sentencia = " delete from gums_modopago";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_MODOPAGO " . $ex->getMessage() . "\n";
    exit(1);
}

$query = " select * from comun_edificio where area = 'S'";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);


try {
    $sentencia = " select * from modopago";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $ModoPagoAll = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN LA SELECT DE MODOPAGO BASE DE DATOS UNIFICADA " . $ex->getMessage() . "\n";
    exit(1);
}

echo " Registros a Cargar = " . count($ModoPagoAll) . "\n";
foreach ($ModoPagoAll as $ModoPago) {
    $id = insertModoPago($ModoPago);
    if ($id == null) {
        continue;
    }
    $ModoPago["ID"] = $id;
    foreach ($EdificioAll as $Edificio) {
        $sentencia = " select * from eq_modopago where codigo_uni = :codigo and edificio = :edificio";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":codigo" => $ModoPago["CODIGO"],
            ":edificio" => $Edificio["codigo"]);
        $query->execute($params);
        $EqModoPagoAll = $query->fetchALL(PDO::FETCH_ASSOC);
        if (count($EqModoPagoAll) == 0) {
            $EqModoPago["edificio_id"] = $Edificio["id"];
            $EqModoPago["edificio"] = $Edificio["codigo"];
            $EqModoPago["codigo_loc"] = "X";
            $EqModoPago["codigo_uni"] = $ModoPago["CODIGO"];
            $EqModoPago["modopago_id"] = $ModoPago["ID"];
            $EqModoPago["enuso"] = "X";
            insertEqModoPago($EqModoPago);
        } else {
            foreach ($EqModoPagoAll as $rowEq) {
                $EqModoPago["edificio_id"] = $Edificio["id"];
                $EqModoPago["edificio"] = $Edificio["codigo"];
                $EqModoPago["codigo_loc"] = $rowEq["CODIGO_LOC"];
                $EqModoPago["codigo_uni"] = $ModoPago["CODIGO"];
                $EqModoPago["modopago_id"] = $ModoPago["ID"];
                $EqModoPago["enuso"] = 'S'; /* NO EXISTE ESTE CAMPO EN LA BASE DE DATOS PERO LO CREAMOS PARA QUE SEA COMO TODAS LAS TABLAS */
                insertEqModoPago($EqModoPago);
            }
        }
    }
}

echo " TERMINADA LA CARGA DE MODOPAGO " . "\n";
exit(0);

