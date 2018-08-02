<?php

include_once __DIR__ . '/../funcionesDAO.php';
/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los puntos asistenciales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */

echo " +++++++++++ COMIENZA PROCESO SINCRONIZACIÓN PUNTOS ASISTENCIALES +++++++++++ \n";
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
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

$sentencia = "delete from ccap_pa";
$query = $JanoControl->prepare($sentencia);
$query->execute();


$gblError = 0;
$queryPA = " select id, edificio, codigo_loc, codigo_uni, oficial, da from eq_centros "
        . " where vista = 'P' ";

$query = $JanoInte->prepare($queryPA);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */
foreach ($resultSet as $row) {
    $codigo_uni = $row['CODIGO_UNI'];
    $CentroUnif = selectCentro($codigo_uni);
    try {
        $sentencia = " insert into ccap_pa "
                . " ( pa, edificio_id, descripcion, oficial, da_id "
                . "   ,enuso,fecha_creacion, fecha_baja) "
                . " values  "
                . " ( :pa,:edificio_id,:descripcion,:oficial,:da_id"
                . "   ,:enuso,:fecha_creacion, null)";
        $insert = $JanoControl->prepare($sentencia);
        $edificio_id = selectEdificio($row["EDIFICIO"]);
        $da_id = selectDa($row["DA"]);
        $params = [":pa" => $row["CODIGO_UNI"],
            ":edificio_id" => $edificio_id,
            ":descripcion" => $CentroUnif["DESCRIP"],
            ":oficial" => $row["OFICIAL"],
            ":da_id" => $da_id,
            ":enuso" => $CentroUnif["ENUSO"],
            ":fecha_creacion" => $CentroUnif["F_CREACION"]];
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT ccap_PA \n";
            $gblError = 1;
        } else {
            echo "==>CREADO PA:" . $row["CODIGO_UNI"] . " " . $desc
            . " OFICIAL:" . $row["OFICIAL"] . " EDIFICIO:" . $row["EDIFICIO"] . "\n";
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT CCAP_PA ERROR=" . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}
echo "  +++++++++++ TERMINA PROCESO SYNCRONIZACIÓN PUNTOS ASISTENCIALES +++++++++++++ \n";
exit($gblError);
