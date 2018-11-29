<?php

include_once __DIR__ . '/../funcionesDAO.php';


/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL GRUPO COBROS (GRC) +++++++++++ \n";
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
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
/*
 * INICIALIZAMOS LA TABLA Y LA CORRESPONDIENTE TABLA DE EQUIVALENCIAS
 */
$sentencia = "select * from gums_grc";
$query = $JanoControl->prepare($sentencia);
$query->execute();

$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA GRC
 */

foreach ($resultSet as $row) {
	$sql = " select descrip from grc where codigo = :codigo";
	$query = $JanoUnif->prepare($sql);
	$params = [":codigo" => $row["codigo"]];
	$query->execute($params);
	$descrip = $query->fetch(PDO::FETCH_ASSOC);
	$sql = "update gums_grc set descripcion = :descripcion where id = :id";
	$query = $JanoControl->prepare($sql);
	var_dump($descrip["DESCRIP"]);
	$params = [":descripcion" => rtrim($descrip["DESCRIP"]),
				":id" =>$row["id"]];
	$query->execute($params);
}
exit;