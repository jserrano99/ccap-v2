<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqMoa($EqMoa) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_moa (edificio_id, moa_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :moa_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqMoa["edificio_id"],
            ":moa_id" => $EqMoa["moa_id"],
            ":codigo_loc" => $EqMoa["codigo_loc"],
            ":enuso" => $EqMoa["enUso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_moa EDIFICIO: " . $EqMoa["edificio"]
            . " MOA=" . $EqMoa["codigo_uni"]
            . " CODIGO_LOC= " . $EqMoa["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_moa EDIFICIO: " . $EqMoa["edificio"]
        . " MOA=" . $EqMoa["codigo_uni"]
        . " CODIGO_LOC= " . $EqMoa["codigo_loc"]
        . " EN USO = " . $EqMoa["enUso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_moa EDIFICIO: " . $row["EDIFICIO"]
        . " MOA=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectMoaEnUso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from moa as t1 "
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
        echo "**PDOERROR NO EN SELECT MOA=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertMoa($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_moa ("
                . " codigo "
                . " ,descripcion"
                . " ,enUso"
                . " ,eap "
                . " ) values ( "
                . " :codigo"
                . " ,:descripcion"
                . " ,:enUso"
                . " ,:eap )";

        $query = $JanoControl->prepare($sentencia);
        
        $params = array(
                     ":codigo" => $row['CODIGO'] ,
                     ":descripcion"=> $row['DESCRIP'],
                     ":enUso"=> $row['ENUSO'],
                     ":eap"=> $row['EAP'],
                     );
        
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT MODALIDAD (MOA) CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA MODALIDAD (MOA) ID= " . $row["ID"] . " MOA:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT MODALIDAD (MOA) CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL MODALIDADES (MOA) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_moa";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA MODALIDAD (gums_eq_moa) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_moa";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA MODALIDAD (gums_moa) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA MOA
 */
$sentencia = " select * from moa";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertMoa($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_moa where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqMoaAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqMoaAll) == 0) {
                $EqMoa["edificio_id"] = $Edificio["id"];
                $EqMoa["edificio"] = $Edificio["codigo"];
                $EqMoa["codigo_loc"] = "XXXX";
                $EqMoa["codigo_uni"] = $row["CODIGO"];
                $EqMoa["moa_id"] = $row["ID"];
                $EqMoa["enUso"] = "X";
                insertEqMoa($EqMoa);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqMoaAll as $rowEq) {
                        $EqMoa["edificio_id"] = $Edificio["id"];
                        $EqMoa["edificio"] = $Edificio["codigo"];
                        $EqMoa["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqMoa["codigo_uni"] = $row["CODIGO"];
                        $EqMoa["moa_id"] = $row["ID"];
                        $EqMoa["enUso"] = selectMoaEnUso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqMoa($EqMoa);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL MOA  +++++++++++++ \n";
exit($gblError);
