<?php

include_once __DIR__ . '/../funcionesDAO.php';

function selectCias($cias) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id from ccap_plazas as t1 "
                . " where t1.cias = :cias";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":cias" => $cias);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ERROR NO EN SELECT plaza=" . $cias . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de tabla de relación entre cecos y cias (CECOCIAS)
 *
 * * ** */

echo " ++++++ COMIENZA PROCESO SINCRONIZACIÓN CECOCIAS +++++++++++ \n";
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

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos( 2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos( 1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

try {
    $sentencia = "select cias, ceco from cecocias";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "**PDOERROR EN SELECT CECOCIAS " . $ex->getMessage() . "\n";
    exit(1);
}

echo " REGISTROS A CARGAR: " . count($resultSet), "\n";

foreach ($resultSet as $row) {
    $plaza_id = selectCias($row["CIAS"]);
    
    if (!$plaza_id) {
        echo " no existe el cias=" . $row["CIAS"] . "\n";
        continue;
    }
    $ceco = SelectCecobyCodigo($row["CECO"]);
    if (!$ceco) {
        echo " no existe el ceco=" . $row["CECO"] . "\n";
        continue;
    }

    try {
        $sentencia = " update ccap_plazas "
                . "set ceco_id = :ceco_id "
                . " where id = :plaza_id ";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":plaza_id" => $plaza_id,
            ":ceco_id" => $ceco["id"]);
        $insert = $query->execute($params);
        if ($insert == 0) {
            echo " error actualización ceco "
            . " CIAS=" . $row["CIAS"]
            . " CECO=" . $row["CECO"] . "\n";
        } else {
            echo " Modificada Plaza CIAS=" . $row["CIAS"] . " CECO=" . $row["CECO"] . "\n";
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR CCAP_PLAZAS"
        . " CIAS=" . $row["CIAS"]
        . " CECO=" . $row["CECO"] . " " . $ex->getMessage() . "\n";
    }
}
echo " +++++ TERMINA PROCESO SYNCRONIZACIÓN CECOCIAS ++++ \n";
exit(0);
