<?php

include_once __DIR__ . '/../funcionesDAO.php';



/**
 * @param $Plaza
 * @return bool
 */
function procesoUpdate($Plaza)
{

	if (!updatePlazaUnif($Plaza)) {
		return false;
	}

	if (!updatePlazaArea($Plaza)) {
		return false;
	}

	return true;
}

/**
 * @param $Plaza
 * @return bool
 */
function procesoDelete($Plaza)
{

	if (!deletePlazaUnif($Plaza)) {
		return false;
	}

	if (!deletePlazaArea($Plaza)) {
		return false;
	}

	return true;
}

/**
 * @param $Plaza
 * @return mixed
 */
function equivalenciasPlaza($Plaza)
{
	$Equi["uf"] = selectEqCentro($Plaza["uf"], $Plaza["edificio"], "U");
	$Equi["p_asist"] = selectEqCentro($Plaza["pa"], $Plaza["edificio"], "P");
	$Equi["catgen"] = selectEqCatGen($Plaza["catgen_id"], $Plaza["edificio_id"]);
	$Equi["catfp"] = selectEqCatFp($Plaza["catfp_id"], $Plaza["edificio_id"]);
	$Equi["turno"] = selectEqTurno($Plaza["turno_id"], $Plaza["edificio_id"]);

	echo "EQUIVALENCIAS (UNIF)/(AREA)\n";
	echo "---------------------------\n";
	echo "   UNIDAD FUNCIONAL= (" . $Plaza["uf"] . ") / (" . $Equi["uf"] . ")\n";
	echo "   PUNTO ASISTENCIAL= (" . $Plaza["pa"] . ") / (" . $Equi["p_asist"] . ")\n";
	echo "   CATEGORIA GENERAL= (" . $Plaza["catgen"] . ") / (" . $Equi["catgen"] . ")\n";
	echo "   CATEGORIA FP= (" . $Plaza["catfp"] . ") / (" . $Equi["catfp"] . ")\n";
	echo "   TURNO = (" . $Plaza["turno"] . ") / (" . $Equi["turno"] . ")\n";

	return $Equi;
}

/**
 * @param $Plaza
 * @return bool
 */
function insertPlazaUnif($Plaza)
{
	global $JanoUnif, $gblerror;
	try {
		$sentencia = " delete from plazas where cias = :cias";
		$query = $JanoUnif->prepare($sentencia);
		$params = [":cias" => $Plaza["cias"]];
		$query->execute($params);

		$sentencia = "insert into plazas ( cias, uf, modalidad, p_asist, catgen"
			. "  ,ficticia, refuerzo, catfp, cupequi, plantilla, f_amortiza, colaboradora, observaciones,turno"
			. "  ,fcreacion, hor_normal, h1ini, h1fin, h2ini, h2fin, unidad_organizativa_id ) values ( "
			. "  :cias, :uf, :modalidad, :p_asist, :catgen"
			. "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno"
			. "  ,:f_creacion, :hor_normal, :h1ini, :h1fin, :h2ini, :h2fin, :unidad_organizativa_id )";

		$query = $JanoUnif->prepare($sentencia);
		$params = [":cias" => $Plaza["cias"],
			":uf" => $Plaza["uf"],
			":modalidad" => $Plaza["modalidad"],
			":p_asist" => $Plaza["pa"],
			":catgen" => $Plaza["catgen"],
			":ficticia" => $Plaza["ficticia"],
			":refuerzo" => $Plaza["refuerzo"],
			":catfp" => $Plaza["catfp"],
			":cupequi" => $Plaza["cupequi"],
			":plantilla" => $Plaza["plantilla"],
			":f_amortiza" => $Plaza["f_amortiza"],
			":colaboradora" => $Plaza["colaboradora"],
			":f_creacion" => $Plaza["f_creacion"],
			":observaciones" => $Plaza["observaciones"],
			":turno" => $Plaza["turno"],
			":hor_normal" => $Plaza["horNormal"] == "" ?: 'N',
			":h1ini" => $Plaza["h1ini"],
			":h1fin" => $Plaza["h1fin"],
			":h2ini" => $Plaza["h2ini"],
			":h2fin" => $Plaza["h2fin"],
			":unidad_organizativa_id" => $Plaza["unidad_organizativa_id"]

		];

		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR EN INSERCIÓN EN LA BASE DE DATOS UNIFICADA CIAS=" . $Plaza["cias"] . "\n";
			$gblerror = 1;
			return false;
		}

		echo "==> PLAZA " . $Plaza["cias"] . " CREADA EN LA BASE DE DATOS UNIFICADA \n";

		if ($Plaza["ceco"] != null) {
			procesoCecoCias($JanoUnif, $Plaza["cias"], $Plaza["ceco"]);
		}
	} catch (PDOException $ex) {
		echo "****PDOERROR EN INSERT BASE DE DATOS UNIFICADA " . $ex->getMessage() . " \n";
		$gblerror = 1;
		return false;
	}

	return true;
}

/**
 * @param $Plaza
 * @return bool
 */
function insertPlazaArea($Plaza)
{
	global $tipobd, $gblError, $tipo;

	$baseDatos = selectBaseDatosEdificio($tipobd, $Plaza["edificio"]);
	if ($baseDatos == null) {
		echo "***ERROR EN NO EXISTE DEFINICIÓN PARA LA BASE DE DATOS EDIFICIO=" . $Plaza["edificio"] . " ENTORNO = " . $tipo . " \n";
		$gblError = 1;
		return false;
	}
	$datosConexion["maquina"] = $baseDatos["maquina"];
	$datosConexion["puerto"] = $baseDatos["puerto"];
	$datosConexion["servidor"] = $baseDatos["servidor"];
	$datosConexion["esquema"] = $baseDatos["esquema"];
	$datosConexion["usuario"] = $baseDatos["usuario"];
	$datosConexion["password"] = $baseDatos["password"];
	$conexionArea = conexionPDO($datosConexion);
	if ($conexionArea == null) {
		return false;
	}

	try {
		$sentencia = " delete from plazas where cias = :cias";
		$query = $conexionArea->prepare($sentencia);
		$params = [":cias" => $Plaza["cias"]];
		$query->execute($params);

		$sentencia = "insert into plazas ( cias, uf, modalidad, p_asist, catgen"
			. "  ,ficticia, refuerzo, catfp, cupequi, plantilla, f_amortiza, colaboradora, observaciones, turno"
			. "  ,fcreacion, hor_normal, tarj1, tarj2, tarj3, tarj4, tarj5, h1ini, h1fin, h2ini, h2fin, unidad_organizativa ) values ( "
			. "  :cias, :uf, :modalidad, :p_asist, :catgen"
			. "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno "
			. "  ,:f_creacion, :hor_normal, :tarj1, :tarj2, :tarj3, :tarj4, :tarj5, :h1ini, :h1fin, :h2ini, :h2fin, :unidad_organizativa)";

		$query = $conexionArea->prepare($sentencia);
//        var_dump($query);
		$Equi = equivalenciasPlaza($Plaza);
		$params = [":cias" => $Plaza["cias"],
			":uf" => $Equi["uf"],
			":modalidad" => $Plaza["modalidad"],
			":p_asist" => $Equi["p_asist"],
			":catgen" => $Equi["catgen"],
			":ficticia" => $Plaza["ficticia"],
			":refuerzo" => $Plaza["refuerzo"],
			":catfp" => $Equi["catfp"],
			":cupequi" => $Plaza["cupequi"],
			":plantilla" => $Plaza["plantilla"],
			":f_amortiza" => $Plaza["f_amortiza"],
			":colaboradora" => $Plaza["colaboradora"],
			":f_creacion" => $Plaza["f_creacion"],
			":observaciones" => $Plaza["observaciones"],
			":turno" => $Equi["turno"],
			":hor_normal" => $Plaza["horNormal"],
			":tarj1" => 0,
			":tarj2" => 0,
			":tarj3" => 0,
			":tarj4" => 0,
			":tarj5" => 0,
			":h1ini" => $Plaza["h1ini"],
			":h1fin" => $Plaza["h1fin"],
			":h2ini" => $Plaza["h2ini"],
			":h2fin" => $Plaza["h2fin"],
			":unidad_organizativa_id" => $Plaza["unidad_organizativa_id"]
		];
//        var_dump($params);

		$ins = $query->execute($params);

		if ($ins == 0) {
			echo "***ERROR EN INSERCION CIAS=" . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}

		echo "==> PLAZA " . $Plaza["cias"] . " CREADA EN AREA \n";

		if ($Plaza["ceco"] != null) {
			procesoCecoCias($conexionArea, $Plaza["cias"], $Plaza["ceco"]);
		}
		return true;
	} catch (PDOException $ex) {
		echo "***PDOERROR EN INSERCION EN BASE DE DATOS AREA  " . $ex->getMessage() . " \n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Plaza
 * @return bool
 */
function deletePlazaUnif($Plaza)
{
	global $JanoUnif, $gblError;

	try {
		$sentencia = "delete from plazas "
			. " where cias = :cias ";
		$query = $JanoUnif->prepare($sentencia);
		$params = [":cias" => $Plaza["cias"]];

		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "***Error en delete base de datos unificada cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo " PLAZA " . $Plaza["cias"] . " ELIMINADA EN LA BASE DE DATOS UNIFICADA \n";
		return true;
	} catch (PDOException $ex) {
		echo "***PDOERROR EN DELETE " . $ex->getMessage() . " \n";
		$gblError = 1;
		return false;
	}
}


/**
 * @param $conexion
 * @return bool
 */
function updatePlaza($conexion)
{
	global $Plaza;

	try {
		$sentencia = "update plazas set  "
			. " unidad_organizativa_id = :unidad_organizativa_id"
			. " where cias = :cias ";
		$query = $conexion->prepare($sentencia);
		$params = [":cias" => $Plaza["cias"],
			":unidad_organizativa_id" => $Plaza["unidad_organizativa_id"]
		];


		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "***Error en actualización base de datos unificada cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo " PLAZA " . $Plaza["cias"] . " MODIFICADA EN LA BASE DE DATOS  \n";
	} catch (PDOException $ex) {
		echo "***PDOERRO EN  Actualicación " . $ex->getMessage() . " \n";
		return false;
	}
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$modo = $argv[1];
$id = $argv[2];
$actuacion = $argv[3];
$gblError = 0;

if ($modo == 'REAL') {
	echo " ENTORNO : PRODUCCIÓN \n";
	$tipobd = 2;
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
} else {
	echo " ENTORNO : VALIDACIÓN \n";
	$tipobd = 1;
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
}


$Plaza = selectPlazaById($id);


if ($Plaza == null) {
	echo " No existe plaza id=" . $id . "\n";
	echo " ***** TERMINA EN ERROR(1) ********\n";
	exit(1);
}

echo " ==> PLAZA: ID =(" . $Plaza["id"]. ") CIAS =(" . $Plaza["cias"].") "."\n";
echo "          UNIDAD_ORGANIZATIVA_ID =(" . $Plaza["unidad_organizativa_id"]. ") \n";

$ConexionArea12 = conexionEdificio(0, $tipobd);

updatePlaza($ConexionArea12);
updatePlaza($JanoUnif);

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN PLAZA +++++++++++++ \n";
exit($gblError);
