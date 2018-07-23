<?php

include_once __DIR__ . '/funcionesDAO.php';

/*
 * Función para comprobar si ya existe el código del UNIDADES FUNCIONALES
 * en la base de datos de control 
 */

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los unidades funcionales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */

echo " +++++++++++ COMIENZA PROCESO SINCRONIZACIÓN UNIDADES FUNCIONALES +++++++++++ \n";
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

$queryUf = " select id, edificio, codigo_loc, codigo_uni, oficial, da from eq_centros "
        . " where vista = 'U' ";

$query = $JanoInte->prepare($queryUf);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */
foreach ($resultSet as $row) {
    $codigo_uni = $row['CODIGO_UNI'];
    $existeCentro = existeCentro($codigo_uni);
//    if ($codigo_uni == '302813') {
//        var_dump($codigo_uni);
//        var_dump($existeCentro);
//    }
    if (!$existeCentro) {
        $CentroUnif = selectCentro($codigo_uni);
        try {
            $sentencia = " insert into ccap_uf "
                    . " ( uf, edificio_id, descripcion, oficial, da_id "
                    . "   ,en_uso,fecha_creacion, fecha_baja) "
                    . " values  "
                    . " ( :uf,:edificio_id,:descripcion,:oficial,:da_id"
                    . "   ,:en_uso,:fecha_creacion, null)";
            $insert = $JanoControl->prepare($sentencia);
            $edificio_id = selectEdificio($row["EDIFICIO"]);
            $da_id = selectDa($row["DA"]);

            $params = [":uf" => $row["CODIGO_UNI"],
                ":edificio_id" => $edificio_id,
                ":descripcion" => $CentroUnif["DESCRIP"],
                ":oficial" => $row["OFICIAL"],
                ":da_id" => $da_id,
                ":en_uso" => $CentroUnif["ENUSO"],
                ":fecha_creacion" => $CentroUnif["F_CREACION"]];

            $res = $insert->execute($params);
            if ($res == 0) {
                echo " ERROR EN INSERT ccap_UF \n";
            } else {
                echo " CREADA UF:" . $row["CODIGO_UNI"] . " " . $desc
                . " OFICIAL:" . $row["OFICIAL"] . " EDIFICIO:" . $row["EDIFICIO"] . "\n";
            }
        } catch (PDOException $ex) {
            echo "*** ERROR EN INSERT ccap_UF ERROR=" . $ex->getMessage();
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO SYNCRONIZACIÓN UNIDADES FUNCIONALES +++++++++++++ \n";
exit(0);
