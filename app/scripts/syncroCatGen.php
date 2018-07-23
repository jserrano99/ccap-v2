<?php

include_once __DIR__ . '/funcionesDAO.php';

function existeCatGen($codigo) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id from ccap_catgen as t1 "
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
        echo " ERROR NO EN SELECT CATGEN=" . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los puntos asistenciales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */
echo " +++++++++++ COMIENZA PROCESO SINCRONIZACIÓN CATEG_GEN +++++++++++ \n";
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

$queryCatGen = " select codigo, descrip, btc_tbol_codigo, enuso, "
        . "plan_org, cod_insalud, des_insalud, especialidad, codigo_sms "
        . " from catgen ";

$query = $JanoUnif->prepare($queryCatGen);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */

foreach ($resultSet as $row) {
    $codigo = $row['CODIGO'];
    $existeCatGen = existeCatGen($codigo);
    if (!$existeCatGen) {
        try {
            $sentencia = " insert into ccap_catgen "
                    . " (codigo, descripcion, btc_tbol_codigo, enuso, plan_org "
                    . " , cod_insalud, des_insalud, especialidad, cod_sms)"
                    . " values  "
                    . " (:codigo, :descripcion, :btc_tbol_codigo, :enuso, :plan_org "
                    . " , :cod_insalud, :des_insalud, :especialidad, :cod_sms)";
            $insert = $JanoControl->prepare($sentencia);
            $params = [":codigo" => $row["CODIGO"],
                ":descripcion" => $row["DESCRIP"],
                ":btc_tbol_codigo" => $row["BTC_TBOL_CODIGO"],
                ":enuso" => $row["ENUSO"],
                ":plan_org" => $row["PLAN_ORG"],
                ":cod_insalud" => $row["COD_INSALUD"],
                ":des_insalud" => $row["DES_INSALUD"],
                ":especialidad" => $row["ESPECIALIDAD"],
                ":cod_sms" => $row["CODIGO_SMS"]];
            $res = $insert->execute($params);
            if ($res == 0) {
                echo " ERROR EN INSERT ccap_CATGEN \n";
            } else {
                echo " CREADO CATGEN:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
            }
        } catch (PDOException $ex) {
            echo " ERROR EN INSERT ccap_CATGEN ERROR=" . $ex->getMessage();
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO SYNCRONIZACIÓN CATGEN  +++++++++++++ \n";
exit(0);
