<?php

include_once __DIR__ . '/../funcionesDAO.php';

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

echo " +++++++++++ COMIENZA PROCESOCARGA  EQUIVALENCIAS UNIDADES FUNCIONALES +++++++++++ \n";
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
    echo "==> ENTORNO: PRODUCCIÓN **** \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo "==> ENTORNO: VALIDACIÓN **** \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

$sentencia = " select * from eq_centros where vista = 'U'";
$query = $JanoInte->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */
foreach ($resultSet as $row) {
	$Uf = selectUfByCodigo($row['CODIGO_UNI']);
    $edificio_id = selectEdificioId($row["EDIFICIO"]);
	try {
        $sentencia = " insert into ccap_eq_uf "
                . " ( uf_id, edificio_id, codigo_loc, enuso) "
                . " values  "
                . " ( :uf_id,:edificio_id,:codigo_loc, :enuso)";

        $insert = $JanoControl->prepare($sentencia);

        $params = [":uf_id" => $Uf["id"],
            ":edificio_id" => $edificio_id,
	        ":codigo_loc" => $row["CODIGO_LOC"],
            ":enuso" => $row["ENUSO"]];
	    $res = $insert->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT ccap_eq_UF \n";
        } else {
            echo " CREADA equivalencia UF:" . $row["CODIGO_UNI"] . " "
            . " OFICIAL:" . $row["OFICIAL"] . " EDIFICIO:" . $row["EDIFICIO"] . "\n";
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT UNIDADES FUNCIONALES ERROR=" . $ex->getMessage()."\n";
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INIICAL DE  UNIDADES FUNCIONALES +++++++++++++ \n";

exit();
