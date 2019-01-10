<?php

include_once __DIR__ . '/../funcionesDAO.php';

/**
 * @param $Plaza
 * @return bool
 */
function amortizaPlazaArea($Plaza)
{
	global $tipobd, $gblError;
	$conexionArea = conexionEdificio($Plaza["edificio"], $tipobd);
	if ($conexionArea == null) {
		return false;
	}
	try {
		$sentencia = "update plazas set  "
			. " f_amortiza= :f_amortiza"
			. ", observaciones = :observaciones"
			. " where cias = :cias ";

		$query = $conexionArea->prepare($sentencia);
		$params = [":cias" => $Plaza["cias"],
			":f_amortiza" => $Plaza["f_amortiza"],
			":observaciones" => $Plaza["observaciones"],
		];

		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR AMORTIZACION PLAZA EN EDIFICIO= " . $Plaza["edificio"] . " cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo "==> PLAZA CIAS= (" . $Plaza["cias"] . ") AMORTIZADA EN LA BASE DE DATOS AREA \n";
		return true;

	} catch (PDOException $ex) {
		echo "***PDOERROR EN AMORTIZACION PLAZAS CIAS= (" . $Plaza["cias"] . ") \n" . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Plaza
 * @return bool
 */
function amortizaPlazaUnif($Plaza)
{
	global $JanoUnif, $gblError;
	try {
		$sentencia = "update plazas set  "
			. " f_amortiza= :f_amortiza"
			. ", observaciones = :observaciones"
			. " where cias = :cias ";
		$query = $JanoUnif->prepare($sentencia);

		$params = [":cias" => $Plaza["cias"],
			":f_amortiza" => $Plaza["f_amortiza"],
			":observaciones" => $Plaza["observaciones"],
		];

		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR AMORTIZACION PLAZA EN EDIFICIO= " . $Plaza["edificio"] . " cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo "==> PLAZA CIAS= (" . $Plaza["cias"] . ") AMORTIZADA EN LA BASE DE DATOS UNIFICADA \n";
		return true;

	} catch (PDOException $ex) {
		echo "***PDOERROR EN AMORTIZACION PLAZAS CIAS= (" . $Plaza["cias"] . ") \n" . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $conexion
 * @param $cias
 * @return bool
 */
function selectCecoCias($conexion, $cias)
{
	try {
		$sentencia = "select * from cecocias where cias = :cias";
		$query = $conexion->prepare($sentencia);
		$params = [":cias" => $cias];
		$query->execute($params);
		$res = $query->fetch(PDO::FETCH_ASSOC);
		if ($res) {
			return true;
		} else {
//            echo " NO EXISTE RELACIÓN CECOCIAS PARA CIAS= " . $cias . "\n";
			return false;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR SELECT CECOCIAS CIAS= (" . $cias . ") ERROR= " . $ex->getMessage() . " \n";
		return false;
	}
}

/**
 * @param $conexion
 * @param $cias
 * @param $ceco
 */
function procesoCecoCias($conexion, $cias, $ceco)
{
	if (selectCecoCias($conexion, $cias)) {
		updateCecoCias($conexion, $cias, $ceco);
	} else {
		insertCecoCias($conexion, $cias, $ceco);
	}
}

/**
 * @param $conexion
 * @param $cias
 * @param $ceco
 * @return bool
 */
function insertCecoCias($conexion, $cias, $ceco)
{
	global $gblError;
	try {
		$sentencia = "insert into cecocias ( "
			. "  cias "
			. " ,ceco "
			. " ) values ("
			. "  :cias "
			. " ,:ceco "
			. " )";
		$query = $conexion->prepare($sentencia);
		$params = [":cias" => $cias,
			":ceco" => $ceco];
		$res = $query->execute($params);
		if ($res == 0) {
			echo "**ERROR INSERCIÓN CECOCIAS CIAS= (" . $cias . ") CECO= (" . $ceco . ") \n";
			$gblError = 1;
			return false;
		} else {
			echo "==>INSERCIÓN CECOCIAS CIAS= (" . $cias . ") CECO= (" . $ceco . ") \n";
			return true;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN INSERCIÓN CECOCIAS CIAS=" . $cias . " CECO=" . $ceco . " " . $ex->getMessage() . " \n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $conexion
 * @param $cias
 * @param $ceco
 * @return bool
 */
function updateCecoCias($conexion, $cias, $ceco)
{
	global $gblError;
	try {
		$sentencia = "update cecocias set "
			. " ceco  = :ceco "
			. " where cias =  :cias ";
		$query = $conexion->prepare($sentencia);
		$params = [":cias" => $cias,
			":ceco" => $ceco];
		$res = $query->execute($params);
		if ($res == 0) {
			echo "**ERROR UPDATE CECOCIAS CIAS= " . $cias . " CECO= " . $ceco . "\n";
			$gblError = 1;
			return false;
		}
		echo "==>UPDATE CECOCIAS CIAS= (" . $cias . ") CECO= (" . $ceco . ") \n";
		return true;
	} catch (PDOException $ex) {
		echo "**PDOERROR EN UPDATE CECOCIAS CIAS=" . $cias . " CECO=" . $ceco . " " . $ex->getMessage() . " \n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Plaza
 * @return bool
 */
function procesoInsert($Plaza)
{
	insertPlazaUnif($Plaza);
	insertPlazaArea($Plaza);

	return true;
}

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
			. "  ,fcreacion, hor_normal, h1ini, h1fin, h2ini, h2fin ) values ( "
			. "  :cias, :uf, :modalidad, :p_asist, :catgen"
			. "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno "
			. "  ,:f_creacion, :hor_normal, :h1ini, :h1fin, :h2ini, :h2fin )";

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
			":h2fin" => $Plaza["h2fin"]
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
			. "  ,fcreacion, hor_normal, tarj1, tarj2, tarj3, tarj4, tarj5, h1ini, h1fin, h2ini, h2fin ) values ( "
			. "  :cias, :uf, :modalidad, :p_asist, :catgen"
			. "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno "
			. "  ,:f_creacion, :hor_normal, :tarj1, :tarj2, :tarj3, :tarj4, :tarj5, :h1ini, :h1fin, :h2ini, :h2fin )";

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
			":h2fin" => $Plaza["h2fin"]
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
 * @param array $Plaza
 * @return bool
 */

function updatePlazaUnif($Plaza)
{
	global $JanoUnif, $gblError;

	try {
		$sentencia = "update plazas set  "
			. "  uf = :uf"
			. " ,modalidad= :modalidad"
			. ", p_asist= :p_asist"
			. ", catgen= :catgen"
			. "  ,ficticia= :ficticia"
			. ", refuerzo= :refuerzo"
			. ", catfp= :catfp"
			. ", plantilla= :plantilla"
			. ", f_amortiza= :f_amortiza"
			. ", colaboradora= :colaboradora"
			. ", fcreacion= :f_creacion"
			. ", observaciones = :observaciones"
			. ", turno = :turno"
			. ", cupequi = :cupequi"
			. ", hor_normal = :hor_normal"
			. ", h1ini = :h1ini"
			. ", h1fin = :h1fin"
			. ", h2ini = :h2ini"
			. ", h2fin = :h2fin"
			. " where cias = :cias ";
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
			":hor_normal" => $Plaza["horNormal"],
			":h1ini" => $Plaza["h1ini"],
			":h1fin" => $Plaza["h1fin"],
			":h2ini" => $Plaza["h2ini"],
			":h2fin" => $Plaza["h2fin"]
		];


		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "***Error en actualización base de datos unificada cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo " PLAZA " . $Plaza["cias"] . " MODIFICADA EN LA BASE DE DATOS UNIFICADA \n";
		if ($Plaza["ceco"] != null) {
			procesoCecoCias($JanoUnif, $Plaza["cias"], $Plaza["ceco"]);
		}
		return true;
	} catch (PDOException $ex) {
		echo "***PDOERRO EN  Actualicación " . $ex->getMessage() . " \n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Plaza
 * @return bool
 */
function updatePlazaArea($Plaza)
{
	global $tipobd, $gblError;
	$conexionArea = conexionEdificio($Plaza["edificio"], $tipobd);
	if ($conexionArea == null) {
		return false;
	}
	try {
		$sentencia = "update plazas set  "
			. "  uf = :uf"
			. " ,modalidad= :modalidad"
			. ", p_asist= :p_asist"
			. ", catgen= :catgen"
			. "  ,ficticia= :ficticia"
			. ", refuerzo= :refuerzo"
			. ", catfp= :catfp"
			. ", plantilla= :plantilla"
			. ", f_amortiza= :f_amortiza"
			. ", colaboradora= :colaboradora"
			. ", fcreacion= :f_creacion"
			. ", observaciones = :observaciones"
			. ", turno = :turno"
			. ", cupequi = :cupequi"
			. ", hor_normal = :hor_normal"
			. ", h1ini = :h1ini"
			. ", h1fin = :h1fin"
			. ", h2ini = :h2ini"
			. ", h2fin = :h2fin"
			. " where cias = :cias ";
		$query = $conexionArea->prepare($sentencia);
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
			":h1ini" => $Plaza["h1ini"],
			":h1fin" => $Plaza["h1fin"],
			":h2ini" => $Plaza["h2ini"],
			":h2fin" => $Plaza["h2fin"]
		];

		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR UPDATE PLAZA EN EDIFICIO= " . $Plaza["edificio"] . " cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo "==> PLAZA CIAS= (" . $Plaza["cias"] . ") MODIFICADA EN LA BASE DE DATOS AREA (".$Plaza["edificio"].")  \n";

		if ($Plaza["ceco"] != null) {
			procesoCecoCias($conexionArea, $Plaza["cias"], $Plaza["ceco"]);
		}

		return true;
	} catch (PDOException $ex) {
		echo "***PDOERROR EN UPDATE PLAZAS CIAS= (" . $Plaza["cias"] . ") \n" . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Plaza
 * @return bool
 */
function deletePlazaArea($Plaza)
{
	global $tipobd, $gblError;
	$conexionArea = conexionEdificio($Plaza["edificio"], $tipobd);
	if ($conexionArea == null) {
		return false;
	}
	try {
		$sentencia = "delete from plazas where cias = :cias ";
		$query = $conexionArea->prepare($sentencia);

		$params = [":cias" => $Plaza["cias"]];
		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR DELETE PLAZA EN EDIFICIO= " . $Plaza["edificio"] . " cias= " . $Plaza["cias"] . "\n";
			$gblError = 1;
			return false;
		}
		echo "==> PLAZA CIAS= (" . $Plaza["cias"] . ") ELIMINADA EN LA BASE DE DATOS AREA (".$Plaza["edificio"].") \n";
		return true;

	} catch (PDOException $ex) {
		echo "***PDOERROR EN DELETE PLAZAS CIAS= (" . $Plaza["cias"] . ") \n" . $ex->getMessage() . "\n";
		$gblError = 1;
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
	$JanoInte = conexionPDO(selectBaseDatos(2, "I"));
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
} else {
	echo " ENTORNO : VALIDACIÓN \n";
	$tipobd = 1;
	$JanoInte = conexionPDO(selectBaseDatos(1, 'I'));
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
}


$Plaza = selectPlazaById($id);


if ($Plaza == null) {
	echo " No existe plaza id=" . $id . "\n";
	echo " ***** TERMINA EN ERROR(1) ********\n";
	exit(1);
}

echo " ==> PLAZA: ID =(" . $Plaza["id"]. ") CIAS =(" . $Plaza["cias"].") "."\n";
echo "          UF =(" . $Plaza["uf"]. ") \n";
echo "          MODALIDAD =(" . $Plaza["modalidad"]. ") \n";
echo "          P_ASIST =(" . $Plaza["pa"]. ") \n";
echo "          CATGEN =(" . $Plaza["catgen"]. ") \n";
echo "          FICTICIA = (" . $Plaza["ficticia"]. ") \n";
echo "          REFUERZO =(" . $Plaza["refuerzo"]. ") \n";
echo "          CATFP =(" . $Plaza["catfp"]. ") \n";
echo "          CUPEQUI =(" . $Plaza["cupequi"]. ") \n";
echo "          PLANTILLA =(" . $Plaza["plantilla"]. ") \n";
echo "          F_AMORTIZA =(" . $Plaza["f_amortiza"]. ") \n";
echo "          COLABORADA =(" . $Plaza["colaboradora"]. ") \n";
echo "          F_CREACIÓN =(" . $Plaza["f_creacion"]. ") \n";
echo "          EDIFICIO =(" . $Plaza["edificio"]. ") \n";
echo "          TURNO =(" . $Plaza["turno"]. ") \n";
echo "          CECO ACTUAL =(" . $Plaza["ceco"]. ") \n";
echo " ==> ACTUACIÓN =(" . $actuacion . ") \n\n";

if ($actuacion == 'INSERT') {
	procesoInsert($Plaza);
}
if ($actuacion == 'UPDATE') {
	procesoUpdate($Plaza);
}
if ($actuacion == 'DELETE') {
	procesoDelete($Plaza);
}
//if ($actuacion == 'AMORTIZACION') {
//	procesoAmortizacion($Plaza);
//}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN PLAZA +++++++++++++ \n";
exit($gblError);
