<?php

/**
 * 
 * Carga Inicial de la Tabla ModOcupa y la correspondiente Tabla de Equivalencias
 */
include_once __DIR__ . '/../funcionesDAO.php';


function insertEqModOcupa($eqModOcupa) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_eq_modocupa "
                . " (edificio_id, codigo_loc, modocupa_id ) values  ( :edificio_id, :codigo_loc, :modocupa_id )";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $eqModOcupa["edificio_id"],
            ":codigo_loc" => $eqModOcupa["codigo_loc"],
            ":modocupa_id" => $eqModOcupa["modocupa_id"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT GUMS_EQ_MODOCUPA EDIFICIO_ID= " . $eqModOcupa["edificio_id"] . " CODIGO_LOC = " . $eqModOcupa["codigo_loc"] . "\n";
            return null;
        }
        echo "GENERADA EQUIVALENCIA GUMS_EQ_MODOCUPA EDIFICIO_ID = " . $eqModOcupa["edificio_id"] . " CODIGO_LOC = " . $eqModOcupa["codigo_loc"] . "\n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_EQ_MODOCUPA " . $ex->getMessage() . "\n";
        return null;
    }
}

/*
 * Cuerpo Principal 
 */
echo " -- CARGA INICIAL TABLA: GUMS_MODOCUPA " . "\n";
echo 'entra';


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
    $sentencia = " delete from gums_eq_modocupa";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_EQ_MODOCUPA " . $ex->getMessage() . "\n";
    exit(1);
}

try {
    $sentencia = " delete from gums_modocupa";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_MODOCUPA " . $ex->getMessage() . "\n";
    exit(1);
}


try {
    $sentencia = " select * from modocupa";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $modOcupaAll = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN LA SELECT DE MODOCUPA BASE DE DATOS UNIFICADA " . $ex->getMessage() . "\n";
    exit(1);
}

$query = " select * from comun_edificio where area = 'S'";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

echo "==>Registros a Cargar = " . count($modOcupaAll) . "\n\n";
foreach ($modOcupaAll as $modOcupa) {
    try {
        $sentencia = " insert into gums_modocupa "
                . " ( codigo, descrip, fie ) values ( :codigo,:descrip, :fie) ";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $modOcupa["CODIGO"],
            ":descrip" => $modOcupa["DESCRIP"],
            ":fie" => $modOcupa["FIE"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_MODOCUPA CODIGO= " . $modOcupa["CODIGO"] . " \n";
            continue;
        }
        $modOcupa["ID"] = $JanoControl->lastInsertId();
        echo " CREADO GUMS_MODOCUPA ID= " . $modOcupa["ID"] . " CODIGO= " . $modOcupa["CODIGO"] . " " . $modOcupa["DESCRIP"] . "\n";

        /**
         * se crean todas las equivalencias, si no existe en control se crea como X
         */
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_modocupa where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $modOcupa["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $eqModOcupaAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($eqModOcupaAll) == 0) {
                $eqModOcupa["edificio_id"] = $Edificio["id"];
                $eqModOcupa["edificio"] = $Edificio["codigo"];
                $eqModOcupa["codigo_loc"] = "X";
                $eqModOcupa["codigo_uni"] = $modOcupa["CODIGO"];
                $eqModOcupa["modocupa_id"] = $modOcupa["ID"];
                $eqModOcupa["enuso"] = "X";
                insertEqModOcupa($eqModOcupa);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                foreach ($eqModOcupaAll as $row) {
                    $eqModOcupa["edificio_id"] = $Edificio["id"];
                    $eqModOcupa["edificio"] = $Edificio["codigo"];
                    $eqModOcupa["codigo_loc"] = $row["CODIGO_LOC"];
                    $eqModOcupa["codigo_uni"] = $modOcupa["CODIGO"];
                    $eqModOcupa["modocupa_id"] = $modOcupa["ID"];
                    $eqModOcupa["enuso"] = 'S'; /* NO EXISTE ESTE CAMPO EN LA BASE DE DATOS PERO LO CREAMOS PARA QUE SEA COMO TODAS LAS TABLAS */
                    insertEqModOcupa($eqModOcupa);
                }
            }
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_MODOCUPA CODIGO= " . $modOcupa["CODIGO"] . " " . $ex->getMessage() . "\n";
    }
}

echo " TERMINADA LA CARGA DE MODOCUPA " . "\n";
exit(0);