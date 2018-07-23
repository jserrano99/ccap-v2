<?php

include_once __DIR__ . '/funcionesDAO.php';

function fselectCeco($ceco) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id from ccap_cecos as t1 "
                . " where t1.codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $ceco);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ERROR NO EN SELECT CECO=" . $ceco . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de tabla de relación entre cecos y cias (CECOCIAS)
 *
 * * ** */

echo " ++++++ COMIENZA PROCESO SINCRONIZACIÓN CECOS +++++++++++ \n";
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
    $sentencia = "select sociedad,division,ceco, descripcion from cecos";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo " ERROR EN SELECT CECOS " . $ex->getMessage() . "\n";
    exit(1);
}
echo " REGISTROS A CARGAR: " . count($resultSet), "\n";
foreach ($resultSet as $row) {
    $ceco = fselectCeco($row["CECO"]);
    if ($ceco) {
        continue;
    }
    try {
        $sentencia = " insert into ccap_cecos "
                . " (sociedad, division, codigo, descripcion) "
                . " values "
                . " (:sociedad, :division, :codigo, :descripcion) ";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":sociedad" => $row["SOCIEDAD"], 
            ":division" => $row["DIVISION"],
            ":codigo" => $row["CECO"],
            ":descripcion" => $row["DESCRIPCION"]);
        $insert = $query->execute($params);
        if ($insert == 0) {
            echo " error insert ccap_cecos "
            . " CECO=" . $row["CECO"] . "\n";
        } else {
            echo " GENERADO CECO=" . $row["CECO"]. "DESCRIPCION=".$row["DESCRIPCION"] . "\n";
        }
    } catch (PDOException $ex) {
        echo " error PDO insert ccap_cecos "
        . " CECO=" . $row["CECO"] . " " . $ex->getMessage() . "\n";
    }
}

echo " +++++ TERMINA PROCESO SYNCRONIZACIÓN CECOS ++++ \n";
exit(0);
