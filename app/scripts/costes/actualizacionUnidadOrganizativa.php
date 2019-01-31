<?php

include_once __DIR__ . '/../funcionesDAO.php';

/**
 * @param $conexion
 */
function deleteUnidadOrganizativa($conexion)
{
	global $UnidadOrganizativa;
	try {
		$sentencia = "delete from ccap_unidad_organizativa where id = :id";
		$query = $conexion->prepare($sentencia);
		$params = [":id" => $UnidadOrganizativa["id"]];

		$ins = $query->execute($params);

		if ($ins == 0) {
			echo " *** error en delete \n";
		}

	} catch (PDOException $ex) {
		echo "**** PDOERROR " . $ex->getMessage() . "\n";
	}
}

/**
 * @param $conexion
 */
function insertUnidadOrganizativa($conexion)
{
	global $UnidadOrganizativa;
	try {
		$sentencia = "insert into ccap_unidad_organizativa (id, codigo, descripcion, orden, tipo_unidad_id, dependencia_id)"
			. " values "
			. " (:id, :codigo, :descripcion, :orden, :tipo_unidad_id, :dependencia_id)";
		$query = $conexion->prepare($sentencia);
		$params = [":id" => $UnidadOrganizativa["id"],
			":codigo" => $UnidadOrganizativa["codigo"],
			":descripcion" => $UnidadOrganizativa["descripcion"],
			":tipo_unidad_id" => $UnidadOrganizativa["tipo_unidad_id"],
			":dependencia_id" => $UnidadOrganizativa["dependencia_id"],
			":orden" => $UnidadOrganizativa["orden"]];
		$ins = $query->execute($params);

		if ($ins == 0) {
			echo " *** error en insert \n";
		}

	} catch (PDOException $ex) {
		echo "**** PDOERROR " . $ex->getMessage() . "\n";
	}
}

/**
 * @param $conexion
 */

function updateUnidadOrganizativa($conexion)
{
	global $UnidadOrganizativa;
	try {
		$sentencia = "update ccap_unidad_organizativa set "
		    ."codigo = :codigo,"
			."descripcion = :descripcion,"
			."orden = :orden,"
			."tipo_unidad_id = :tipo_unidad_id,"
			."dependencia_id = :dependencia_id "
			." where id = :id";
		$query = $conexion->prepare($sentencia);
		$params = [":id" => $UnidadOrganizativa["id"],
			":codigo" => $UnidadOrganizativa["codigo"],
			":descripcion" => $UnidadOrganizativa["descripcion"],
			":tipo_unidad_id" => $UnidadOrganizativa["tipo_unidad_id"],
			":dependencia_id" => $UnidadOrganizativa["dependencia_id"],
			":orden" => $UnidadOrganizativa["orden"]];
		$ins = $query->execute($params);

		if ($ins == 0) {
			echo " *** error en update \n";
		}

	} catch (PDOException $ex) {
		echo "**** PDOERROR " . $ex->getMessage() . "\n";
	}
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO ACTUALIZACIÓN DE UNIDAD FUNCIONAL (UF) +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$tipo = $argv[1];
$unidad_organizativa_id = $argv[2];
$actuacion = $argv[3];

$gblError = 0;

if ($tipo == 'REAL') {
	echo "==> ENTORNO : PRODUCCIÓN  \n";
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
	$tipobd = 2;
} else {
	echo "==> ENTORNO : VALIDACIÓN  \n";
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
	$tipobd = 1;
}

$UnidadOrganizativa = selectUnidadOrganizativaById($unidad_organizativa_id);

if ($UnidadOrganizativa == null) {
	echo "****ERROR NO EXISTE UNIDAD ORGANIZATIVA ID=" . $unidad_organizativa_id . "\n";
	echo "** TERMINA EN ERROR n";
	exit(1);
}


echo " => UNIDAD ORGANIZATIVA:  "
	. " ID = (" . $UnidadOrganizativa["id"] . ")"
	. " CODIGO = (" . $UnidadOrganizativa["codigo"] . ")"
	. " DESCRIPCIÓN = (" . $UnidadOrganizativa["descripcion"] . ")"
	. " TIPO UNIDAD = (" . $UnidadOrganizativa["tipo_unidad"] . ")"
	. " DEPENDENCIA = (" . $UnidadOrganizativa["dependencia"] . ")"
	. "\n";

echo "==> ACTUACION : " . $actuacion . "\n";

$ConexionArea12 = conexionEdificio(0, $tipobd);

if ($actuacion == 'INSERT') {
	insertUnidadOrganizativa($ConexionArea12);
	insertUnidadOrganizativa($JanoUnif);
}

if ($actuacion == 'UPDATE') {
	updateUnidadOrganizativa($ConexionArea12);
	updateUnidadOrganizativa($JanoUnif);
}

if ($actuacion == 'DELETE') {
	deleteUnidadOrganizativa($ConexionArea12);
	deleteUnidadOrganizativa($JanoUnif);
	deleteUnidadOrganizativa($JanoControl);

}


echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN UNIDAD FUNCIONAL +++++++++++++ \n";
exit($gblError);
