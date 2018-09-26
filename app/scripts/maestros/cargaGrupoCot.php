<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqGrupoCot($EqGrupoCot) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_grupocot (edificio_id, grupocot_id, codigo_loc)  "
                . " values (:edificio_id, :grupocot_id, :codigo_loc ) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqGrupoCot["edificio_id"],
            ":grupocot_id" => $EqGrupoCot["grupocot_id"],
            ":codigo_loc" => $EqGrupoCot["codigo_loc"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_GrupoCot EDIFICIO: " . $EqGrupoCot["edificio"]
            . " GRUPOCOT=" . $EqGrupoCot["codigo_uni"]
            . " CODIGO_LOC= " . $EqGrupoCot["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_GrupoCot EDIFICIO: " . $EqGrupoCot["edificio"]
        . " GRUPOCOT=" . $EqGrupoCot["codigo_uni"]
        . " CODIGO_LOC= " . $EqGrupoCot["codigo_loc"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_GrupoCot EDIFICIO: " . $row["EDIFICIO"]
        . " GRUPOCOT=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function insertGrupoCot($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_grupocot "
                . " (codigo, descripcion ) "
                . " values "
                . " (:codigo, :descripcion)";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $row["GRUPCOT"],
            ":descripcion" => $row["DESCRIP"]);
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT GRUPO COTIZACION GRUPOCOT CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA GRUPO COTIZACION (GRUPOCOT) ID= " . $row["ID"] . " GRUPOCOT:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT GRUPOCOT CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/**
 * CUERPO PRINCIPAL DEL SCRIPT
 */
echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL GRUPOS DE COTIZACION (GRUPOCOT) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_grupocot";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (gums_eq_grupocot) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_grupocot";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA GRUPO COTIZACION  (gums_grupocot) REGISTROS: " . $rows . "\n";

/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*   
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA BASES,DONDE ESTÁN RECOGIDOS LOS GRUPOS DE COTIZACIÓN
 */
$sentencia = " select * from bases";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertGrupoCot($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_grupcot where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["GRUPCOT"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqGrupoCotAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqGrupoCotAll) == 0) {
                $EqGrupoCot["edificio_id"] = $Edificio["id"];
                $EqGrupoCot["edificio"] = $Edificio["codigo"];
                $EqGrupoCot["codigo_loc"] = "XXXX";
                $EqGrupoCot["codigo_uni"] = $row["CODIGO"];
                $EqGrupoCot["grupocot_id"] = $row["ID"];
                insertEqGrupoCot($EqGrupoCot);
            } else {
                foreach ($EqGrupoCotAll as $rowEq) {
                    $EqGrupoCot["edificio_id"] = $Edificio["id"];
                    $EqGrupoCot["edificio"] = $Edificio["codigo"];
                    $EqGrupoCot["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqGrupoCot["codigo_uni"] = $rowEq["CODIGO_UNI"];
                    $EqGrupoCot["grupocot_id"] = $row["ID"];
                    insertEqGrupoCot($EqGrupoCot);
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL GRUPOCOT  +++++++++++++ \n";
exit($gblError);
