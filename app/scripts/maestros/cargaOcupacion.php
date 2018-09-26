<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqOcupacion($EqOcupacion) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_ocupacion (edificio_id, ocupacion_id, codigo_loc)  "
                . " values (:edificio_id, :ocupacion_id, :codigo_loc) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqOcupacion["edificio_id"],
            ":ocupacion_id" => $EqOcupacion["ocupacion_id"],
            ":codigo_loc" => $EqOcupacion["codigo_loc"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_ocupacion EDIFICIO: " . $EqOcupacion["edificio"]
            . " OCUPACION=" . $EqOcupacion["codigo_uni"]
            . " CODIGO_LOC= " . $EqOcupacion["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_ocupacion EDIFICIO: " . $EqOcupacion["edificio"]
        . " OCUPACION=" . $EqOcupacion["codigo_uni"]
        . " CODIGO_LOC= " . $EqOcupacion["codigo_loc"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_ocupacion EDIFICIO: " . $row["EDIFICIO"]
        . " OCUPACION=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function insertOcupacion($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_ocupacion ("
                . " codigo "
                . " ,descripcion"
                . " ) values ( "
                . " :codigo"
                . " ,:descripcion )";

        $query = $JanoControl->prepare($sentencia);

        $params = array(
            ":codigo" => $row['CODIGO'],
            ":descripcion" => $row['DESCRIPCION'],
        );

        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT OCUPACION () CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA OCUPACION () ID= " . $row["ID"] . " OCUPACION:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT OCUPACION () CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL OCUPACIONES (OCUPACION) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_ocupacion";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA OCUPACION (gums_eq_ocupacion) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_ocupacion";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA OCUPACION (gums_ocupacion) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA OCUPACION
 */
$sentencia = " select * from ocupacion";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertOcupacion($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_ocupacion where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqOcupacionAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqOcupacionAll) == 0) {
                $EqOcupacion["edificio_id"] = $Edificio["id"];
                $EqOcupacion["edificio"] = $Edificio["codigo"];
                $EqOcupacion["codigo_loc"] = "XXXX";
                $EqOcupacion["codigo_uni"] = $row["CODIGO"];
                $EqOcupacion["ocupacion_id"] = $row["ID"];
                insertEqOcupacion($EqOcupacion);
            } else {
                foreach ($EqOcupacionAll as $rowEq) {
                    $EqOcupacion["edificio_id"] = $Edificio["id"];
                    $EqOcupacion["edificio"] = $Edificio["codigo"];
                    $EqOcupacion["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqOcupacion["codigo_uni"] = $rowEq["CODIGO_UNI"];
                    $EqOcupacion["ocupacion_id"] = $row["ID"];
                    insertEqOcupacion($EqOcupacion);
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL OCUPACION  +++++++++++++ \n";
exit($gblError);
