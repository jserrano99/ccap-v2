<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqEpiAcc($EqEpiAcc) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_epiacc (edificio_id, epiacc_id, codigo_loc)  "
                . " values (:edificio_id, :epiacc_id, :codigo_loc) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqEpiAcc["edificio_id"],
            ":epiacc_id" => $EqEpiAcc["epiacc_id"],
            ":codigo_loc" => $EqEpiAcc["codigo_loc"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_epiacc EDIFICIO: " . $EqEpiAcc["edificio"]
            . " EPIACC=" . $EqEpiAcc["codigo_uni"]
            . " CODIGO_LOC= " . $EqEpiAcc["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_epiacc EDIFICIO: " . $EqEpiAcc["edificio"]
        . " EPIACC=" . $EqEpiAcc["codigo_uni"]
        . " CODIGO_LOC= " . $EqEpiAcc["codigo_loc"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_epiacc EDIFICIO: " . $row["EDIFICIO"]
        . " EPIACC=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function insertEpiAcc($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_epiacc ("
                . " codigo "
                . " ,ilt"
                . " ,ims"
                . " ,ilt_ant"
                . " ,ims_ant"
                . " ) values ( "
                . " :codigo"
                . " ,:ilt"
                . " ,:ims"
                . " ,:ilt_ant"
                . " ,:ims_ant)";

        $query = $JanoControl->prepare($sentencia);

        $params = array(":codigo" => $row['EPIGRAFE'],
            ":ilt" => $row['ILT'],
            ":ims" => $row['IMS'],
            ":ilt_ant" => $row['ILT_ANT'],
            ":ims_ant" => $row['IMS_ANT']);

        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT EPIACC () CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA EPIACC () ID= " . $row["ID"] . " EPIACC:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT EPIACC () CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL EPIACCES (EPIACC) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_epiacc";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EPIACC (gums_eq_epiacc) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_epiacc";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EPIACC (gums_epiacc) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA EPIACC
 */
$sentencia = " select * from epiacc";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertEpiAcc($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_epiacc where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["EPIGRAFE"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqEpiAccAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqEpiAccAll) == 0) {
                $EqEpiAcc["edificio_id"] = $Edificio["id"];
                $EqEpiAcc["edificio"] = $Edificio["codigo"];
                $EqEpiAcc["codigo_loc"] = "XXXX";
                $EqEpiAcc["codigo_uni"] = $row["EPIGRAFE"];
                $EqEpiAcc["epiacc_id"] = $row["ID"];
                insertEqEpiAcc($EqEpiAcc);
            } else {
                foreach ($EqEpiAccAll as $rowEq) {
                    $EqEpiAcc["edificio_id"] = $Edificio["id"];
                    $EqEpiAcc["edificio"] = $Edificio["codigo"];
                    $EqEpiAcc["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqEpiAcc["codigo_uni"] = $rowEq["CODIGO_UNI"];
                    $EqEpiAcc["epiacc_id"] = $row["ID"];
                    insertEqEpiAcc($EqEpiAcc);
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL EPIACC  +++++++++++++ \n";
exit($gblError);
