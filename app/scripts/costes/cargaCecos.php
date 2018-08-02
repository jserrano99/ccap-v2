<?php

include_once __DIR__ . '/../funcionesDAO.php';

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de tabla de relación entre cecos y cias (CECOCIAS)
 *
 * * ** */

echo " ++++++ COMIENZA PROCESO CARGA INICIAL DE CECOS +++++++++++ \n";
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

$sentencia = " delete from ccap_cecos";
$query = $JanoControl->prepare($sentencia);
$query->execute();


try {
    $sentencia = "select sociedad, division, ceco, descripcion from cecos";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo " ERROR EN SELECT CECOS " . $ex->getMessage() . "\n";
    exit(1);
}
echo " REGISTROS A CARGAR: " . count($resultSet), "\n";
foreach ($resultSet as $row) {
    $ceco = selectCeco($row["CECO"]);
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
            echo "** error insert ccap_cecos "
            . " CECO=" . $row["CECO"] . "\n";
        } else {
            echo " GENERADO CECO= " . $row["CECO"]. " DESCRIPCION=".$row["DESCRIPCION"] . "\n";
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR INSERT CCAP_CECOS"
        . " CECO=" . $row["CECO"] . " " . $ex->getMessage() . "\n";
    }
}

echo " +++++ TERMINA PROCESO CARGA INICIAL DE  CECOS ++++ \n";
exit(0);
