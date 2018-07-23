<?php

include_once __DIR__ . '/funcionesDAO.php';

function existeCatFp($codigo) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id from ccap_catfp as t1 "
                . " where t1.codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo " ERROR NO EN SELECT CATFP=" . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los puntos asistenciales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */

echo " +++++++++++ COMIENZA PROCESO SINCRONIZACIÓN CATFP +++++++++++ \n";
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
    $JanoInte = conexionPDO(SelectBaseDatos($JanoControl, 2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos($JanoControl, 2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos($JanoControl, 1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos($JanoControl, 1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

$queryCatFp = " select codigo, descrip, enuso from catfp ";

$query = $JanoUnif->prepare($queryCatFp);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */
foreach ($resultSet as $row) {
    $codigo = $row['CODIGO'];
    $existeCatFp = existeCatFp($codigo);
    if (!$existeCatFp) {
        try {
            $sentencia = " insert into ccap_catfp "
                    . " (codigo, descripcion, enuso )"
                    . " values  "
                    . " (:codigo, :descripcion, :enuso)";

            $insert = $JanoControl->prepare($sentencia);
            $params = [":codigo" => $row["CODIGO"],
                ":descripcion" => $row["DESCRIP"],
                ":enuso" => $row["ENUSO"]];
            $res = $insert->execute($params);
            if ($res == 0) {
                echo " ERROR EN INSERT ccap_CATFP \n";
            } else {
                echo " CREADO CATFP:" . $row["CODIGO"] . " " . $row["DESCRIP"] ."\n";
            }
        } catch (PDOException $ex) {
            echo " ERROR EN INSERT ccap_CATFP ERROR=" . $ex->getMessage();
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO SYNCRONIZACIÓN CATFP  +++++++++++++ \n";
exit(0);
