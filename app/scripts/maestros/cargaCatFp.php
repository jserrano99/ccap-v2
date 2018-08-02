<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqCatFp($EqCatFp) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into ccap_eq_catfp (edificio_id, catfp_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :catfp_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqCatFp["edificio_id"],
            ":catfp_id" => $EqCatFp["catfp_id"],
            ":codigo_loc" => $EqCatFp["codigo_loc"],
            ":enuso" => $EqCatFp["enUso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT CCAP_EQ_CATFP EDIFICIO: " . $EqCatFp["edificio"]
            . " CATFP=" . $EqCatFp["codigo_uni"]
            . " CODIGO_LOC= " . $EqCatFp["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT CCAP_EQ_CATFP EDIFICIO: " . $EqCatFp["edificio"]
        . " CATFP=" . $EqCatFp["codigo_uni"]
        . " CODIGO_LOC= " . $EqCatFp["codigo_loc"]
        . " EN USO = " . $EqCatFp["enUso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CCAP_EQ_CATFP EDIFICIO: " . $row["EDIFICIO"]
        . " CATFP=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectCatFpEnUso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from catfp as t1 "
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
        echo "**PDOERROR NO EN SELECT CATFP=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertCatFp($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into ccap_catfp "
                . " (codigo, descripcion, enuso )"
                . " values  "
                . " (:codigo, :descripcion, :enuso)";

        $insert = $JanoControl->prepare($sentencia);
        $params = [":codigo" => $row["CODIGO"],
            ":descripcion" => $row["DESCRIP"],
            ":enuso" => $row["ENUSO"]];
        $res = $insert->execute($params);
        $id = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT CATFPORIA CATFP CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA CATEGORIA (CATFP) ID= " . $row["ID"] . " CATFP:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $id;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CATEGORIA CATFP CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage();
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los puntos asistenciales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL CATFPORIAS (CATFP) +++++++++++ \n";
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
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
    echo "==> ENTORNO: PRODUCCIÓN \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
    echo "==> ENTORNO: VALIDACIÓN \n";
}
/*
 * INICIALIZAMOS LA TABLA Y LA CORRESPONDIENTE TABLA DE EQUIVALENCIAS
 */
$sentencia = " delete from ccap_eq_catfp";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (CCAP_EQ_CATFP) REGISTROS: " . $rows . "\n";

$sentencia = " delete from ccap_catfp";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA CATEGORIA CATFP (CCAP_CATFP) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA CATFP
 */
$queryCatFp = " select codigo, descrip, enuso from catfp ";
$query = $JanoUnif->prepare($queryCatFp);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertCatFp($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_catfp where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqCatFpAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqCatFpAll) == 0) {
                $EqCatFp["edificio_id"] = $Edificio["id"];
                $EqCatFp["edificio"] = $Edificio["codigo"];
                $EqCatFp["codigo_loc"] = "XXXX";
                $EqCatFp["codigo_uni"] = $row["CODIGO"];
                $EqCatFp["catfp_id"] = $row["ID"];
                $EqCatFp["enUso"] = "X";
                insertEqCatFp($EqCatFp);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd); 
                if ($conexion) {
                    foreach ($EqCatFpAll as $rowEq) {
                        $EqCatFp["edificio_id"] = $Edificio["id"];
                        $EqCatFp["edificio"] = $Edificio["codigo"];
                        $EqCatFp["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqCatFp["codigo_uni"] = $row["CODIGO"];
                        $EqCatFp["catfp_id"] = $row["ID"];
                        $EqCatFp["enUso"] = selectCatFpEnUso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqCatFp($EqCatFp);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL CATFP  +++++++++++++ \n";
exit($gblError);
