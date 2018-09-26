<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqFco($EqFco) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_fco (edificio_id, fco_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :fco_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqFco["edificio_id"],
            ":fco_id" => $EqFco["fco_id"],
            ":codigo_loc" => $EqFco["codigo_loc"],
            ":enuso" => $EqFco["enUso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_fco EDIFICIO: " . $EqFco["edificio"]
            . " FCO=" . $EqFco["codigo_uni"]
            . " CODIGO_LOC= " . $EqFco["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_fco EDIFICIO: " . $EqFco["edificio"]
        . " FCO=" . $EqFco["codigo_uni"]
        . " CODIGO_LOC= " . $EqFco["codigo_loc"]
        . " EN USO = " . $EqFco["enUso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_fco EDIFICIO: " . $row["EDIFICIO"]
        . " FCO=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectFcoEnUso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from fco as t1 "
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
        echo "**PDOERROR NO EN SELECT FCO=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertFco($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_fco "
                . " (codigo, descripcion, propietario, soli_origen, enuso, fcorptid, fcorpt_codigo, fcorpt_descripcion) "
                . " values "
                . " (:codigo, :descripcion, :propietario, :soli_origen, :enuso, :fcorptid, :fcorpt_codigo, :fcorpt_descripcion) ";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $row["CODIGO"],
            ":descripcion" => $row["DESCRIP"],
            ":propietario" => $row["PROPIETARIO"],
            ":soli_origen" => $row["SOLI_ORIGEN"],
            ":enuso" => $row["ENUSO"],
            ":fcorptid" => $row["FCORPTID"], 
            ":fcorpt_codigo" => $row["FCORPT_CODIGO"], 
            ":fcorpt_descripcion" => $row["FCORPT_DESCRIPCION"]);
    
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT FORMA COBERTURA FCO CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA FORMA COBERTURA (FCO) ID= " . $row["ID"] . " FCO:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT FCO CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage()."\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL FORMAS DE COBERTURA (FCO) +++++++++++ \n";
/*
 * Conexión a la base de datos de Control en Mysql 
 */
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}
/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];
$gblError = 0;
if ($tipo == 'REAL') {
    echo "==> ENTORNO: PRODUCCIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo "==> ENTORNO: VALIDACIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}
/*
 * INICIALIZAMOS LA TABLA Y LA CORRESPONDIENTE TABLA DE EQUIVALENCIAS
 */
$sentencia = " delete from gums_eq_fco";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (GUMS_EQ_FCO) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_fco";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA FORMAS COBERTURA (FCO) ESTADO=(" . $rows . ") \n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA FCO
 */
$sentencia = " select * from fco";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertFco($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_fco where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqFcoAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqFcoAll) == 0) {
                $EqFco["edificio_id"] = $Edificio["id"];
                $EqFco["edificio"] = $Edificio["codigo"];
                $EqFco["codigo_loc"] = "XXXX";
                $EqFco["codigo_uni"] = $row["CODIGO"];
                $EqFco["fco_id"] = $row["ID"];
                $EqFco["enUso"] = "X";
                insertEqFco($EqFco);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqFcoAll as $rowEq) {
                        $EqFco["edificio_id"] = $Edificio["id"];
                        $EqFco["edificio"] = $Edificio["codigo"];
                        $EqFco["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqFco["codigo_uni"] = $row["CODIGO"];
                        $EqFco["fco_id"] = $row["ID"];
                        $EqFco["enUso"] = selectFcoEnUso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqFco($EqFco);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL FCO  +++++++++++++ \n";
exit($gblError);
