<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqCatAnexo($EqCatAnexo) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_catanexo (edificio_id, catanexo_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :catanexo_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqCatAnexo["edificio_id"],
            ":catanexo_id" => $EqCatAnexo["catanexo_id"],
            ":codigo_loc" => $EqCatAnexo["codigo_loc"],
            ":enuso" => $EqCatAnexo["enuso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_catanexo EDIFICIO: " . $EqCatAnexo["edificio"]
            . " CATANEXO=" . $EqCatAnexo["codigo_uni"]
            . " CODIGO_LOC= " . $EqCatAnexo["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_catanexo EDIFICIO: " . $EqCatAnexo["edificio"]
        . " CATANEXO=" . $EqCatAnexo["codigo_uni"]
        . " CODIGO_LOC= " . $EqCatAnexo["codigo_loc"]
        . " EN USO = " . $EqCatAnexo["enuso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_catanexo EDIFICIO: " . $row["EDIFICIO"]
        . " CATANEXO=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectCatAnexoEnuso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from catanexo as t1 "
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
        echo "**PDOERROR NO EN SELECT CATANEXO=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertCatAnexo($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_catanexo ("
                . " codigo "
                . " ,descripcion"
                . " ,enuso"
                . " ) values ( "
                . " :codigo"
                . " ,:descripcion"
                . " ,:enuso )";

        $query = $JanoControl->prepare($sentencia);
        
        $params = array(
                     ":codigo" => $row['CODIGO'] ,
                     ":descripcion"=> $row['DESCRIP'],
                     ":enuso"=> $row['ENUSO']);
          
        
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_CATANEXO CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA GUMS_CATANEXO  ID= " . $row["ID"] . " CATANEXO:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT GUMS_CATANEXO CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL GUMS_CATANEXO +++++++++++ \n";
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
$sentencia = " delete from gums_eq_catanexo";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (gums_eq_catanexo) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_catanexo";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA GUMS_CATANEXO  REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA CATANEXO
 */
$sentencia = " select * from catanexo";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertCatAnexo($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_catanexo where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqCatAnexoAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqCatAnexoAll) == 0) {
                $EqCatAnexo["edificio_id"] = $Edificio["id"];
                $EqCatAnexo["edificio"] = $Edificio["codigo"];
                $EqCatAnexo["codigo_loc"] = "XXXX";
                $EqCatAnexo["codigo_uni"] = $row["CODIGO"];
                $EqCatAnexo["catanexo_id"] = $row["ID"];
                $EqCatAnexo["enuso"] = "X";
                insertEqCatAnexo($EqCatAnexo);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqCatAnexoAll as $rowEq) {
                        $EqCatAnexo["edificio_id"] = $Edificio["id"];
                        $EqCatAnexo["edificio"] = $Edificio["codigo"];
                        $EqCatAnexo["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqCatAnexo["codigo_uni"] = $row["CODIGO"];
                        $EqCatAnexo["catanexo_id"] = $row["ID"];
                        $EqCatAnexo["enuso"] = selectCatAnexoEnuso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqCatAnexo($EqCatAnexo);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL CATANEXO  +++++++++++++ \n";
exit($gblError);
