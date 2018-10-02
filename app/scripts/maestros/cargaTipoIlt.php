<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqTipoIlt($EqTipoIlt) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_tipo_ilt (edificio_id, tipo_ilt_id, codigo_loc)  "
                . " values (:edificio_id, :tipo_ilt_id, :codigo_loc) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqTipoIlt["edificio_id"],
            ":tipo_ilt_id" => $EqTipoIlt["tipo_ilt_id"],
            ":codigo_loc" => $EqTipoIlt["codigo_loc"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_tipo_ilt EDIFICIO: " . $EqTipoIlt["edificio"]
            . " TIPO_ILT=" . $EqTipoIlt["codigo_uni"]
            . " CODIGO_LOC= " . $EqTipoIlt["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_tipo_ilt EDIFICIO: " . $EqTipoIlt["edificio"]
        . " TIPO_ILT=" . $EqTipoIlt["codigo_uni"]
        . " CODIGO_LOC= " . $EqTipoIlt["codigo_loc"]
        . " EN USO = " . $EqTipoIlt["enuso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_tipo_ilt EDIFICIO: " . $row["EDIFICIO"]
        . " TIPO_ILT=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}


function insertTipoIlt($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_tipo_ilt "
                . " (codigo, descripcion )"
                . " values "
                . " (:codigo, :descripcion)";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $row["CODIGO"],
            ":descripcion" => $row["DESCRIP"]
            );
    
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT TIPO ILT TIPO_ILT CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA TIPO ILT (TIPO_ILT) ID= " . $row["ID"] . " TIPO_ILT:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT TIPO_ILT CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage()."\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL TIPO INCAPACIDAD TEMPORAL (TIPO_ILT) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_tipo_ilt";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (GUMS_EQ_TIPO_ILT) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_tipo_ilt";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA FORMAS COBERTURA (TIPO_ILT) ESTADO=(" . $rows . ") \n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA TIPO_ILT
 */
$sentencia = " select * from tipo_ilt";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertTipoIlt($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_tipo_ilt where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqTipoIltAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqTipoIltAll) == 0) {
                $EqTipoIlt["edificio_id"] = $Edificio["id"];
                $EqTipoIlt["edificio"] = $Edificio["codigo"];
                $EqTipoIlt["codigo_loc"] = "XXXX";
                $EqTipoIlt["codigo_uni"] = $row["CODIGO"];
                $EqTipoIlt["tipo_ilt_id"] = $row["ID"];
                insertEqTipoIlt($EqTipoIlt);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqTipoIltAll as $rowEq) {
                        $EqTipoIlt["edificio_id"] = $Edificio["id"];
                        $EqTipoIlt["edificio"] = $Edificio["codigo"];
                        $EqTipoIlt["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqTipoIlt["codigo_uni"] = $row["CODIGO"];
                        $EqTipoIlt["tipo_ilt_id"] = $row["ID"];
                        insertEqTipoIlt($EqTipoIlt);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL TIPO_ILT  +++++++++++++ \n";
exit($gblError);
