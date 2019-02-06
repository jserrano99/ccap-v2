<?php

include_once __DIR__ . '/../funcionesDAO.php';

/**
 * @return array|null
 */
function selectEqCatFpAll()
{
	global $CatFp, $JanoControl, $gblError;
	try {
		$sentencia = " select t1.id, t1.codigo_loc, t2.codigo as edificio, t2.id as edificio_id,  t1.enuso as enuso "
			. " from gums_eq_catfp as t1 "
			. " inner join comun_edificio as t2 on t1.edificio_id = t2.id "
			. " where t1.catfp_id = :id"
			. " and t1.enuso = 'S' ";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $CatFp["id"]];
		$query->execute($params);
		$EqCatFpAll = $query->fetchAll(PDO::FETCH_ASSOC);
		return $EqCatFpAll;
	} catch (PDOException $ex) {
		echo "***PDOERROR EN SELECT gums_eq_catfp ID = " . $CatFp["id"] . " CODIGO_UNI = " . $CatFp["codigo"] . "\n"
			. $ex->getMessage() . "\n";
		$gblError = 1;
		return null;
	}
}

/**
 * @param $eqcatfp_id
 * @return array|null
 */
function selectEqCatFpById($eqcatfp_id)
{
	global $CatFp, $JanoControl, $gblError;
	try {
		$sentencia = " select t1.id, t1.codigo_loc, t2.codigo as edificio, t2.id as edificio_id,  t1.enuso as enuso "
			. " from gums_eq_catfp as t1 "
			. " inner join comun_edificio as t2 on t1.edificio_id = t2.id "
			. " where t1.id = :id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":id" => $eqcatfp_id];
		$query->execute($params);
		$EqCatFpAll = $query->fetch(PDO::FETCH_ASSOC);
		return $EqCatFpAll;
	} catch (PDOException $ex) {
		echo "***PDOERROR EN SELECT gums_eq_catfp ID = " . $CatFp["id"] . " CODIGO_UNI = " . $CatFp["codigo"] . "\n"
			. $ex->getMessage() . "\n";
		$gblError = 1;
		return null;
	}
}

/**
 *
 */
function insertEqCatFp()
{
	global $JanoControl, $CatFp, $JanoInte, $EqCatFp, $gblError;
	try {
		$sentencia = " insert into eq_catfp (edificio, codigo_loc, codigo_uni ) values (:edificio, :codigo_loc, :codigo_uni)";
		$query = $JanoInte->prepare($sentencia);
		$params = [":edificio" => $EqCatFp["edificio"],
			"codigo_loc" => $CatFp["codigo"],
			"codigo_uni" => $CatFp["codigo"]];
		$ins = $query->execute($params);
		if ($ins) {
			try {
				$sentencia = " update gums_eq_catfp set codigo_loc = :codigo_loc, "
					. " enuso = 'S' "
					. " where catfp_id = :catfp_id "
					. " and edificio_id = :edificio_id ";
				$query = $JanoControl->prepare($sentencia);
				$params = [":catfp_id" => $CatFp["id"],
					":edificio_id" => $EqCatFp["edificio_id"]];
				$res = $query->execute($params);
				if ($res == 0) {
					echo "**ERROR EN UPDATE CCAP_EQ_CATGP CATFP_ID=" . $CatFp["id"] . " EDIFICIO_ID= " . $EqCatFp["edificio_id"] . "\n";
					$gblError = 1;
				}
			} catch (PDOException $ex) {
				echo "**PDOERROR EN UPDATE CCAP_EQ_CATGP CATFP_ID=" . $CatFp["id"] . " EDIFICIO_ID= " . $EqCatFp["edificio_id"] . "\n"
					. $ex->getMessage() . "\n";
				$gblError = 1;
			}
		} else {
			echo "**ERROR EN INSERT EQ_CATGP CATFP_ID=" . $CatFp["id"] . " EDIFICIO_ID= " . $EqCatFp["edificio_id"] . "\n";
			$gblError = 1;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN INSERT EQ_CATGP CATFP_ID=" . $CatFp["id"] . " EDIFICIO_ID= " . $EqCatFp["edificio_id"] . "\n"
			. $ex->getMessage() . "\n";
		$gblError = 1;
	}
}

/**
 * @param $conexion
 * @return bool|null
 */
function insertCatFp($conexion)
{
	global $CatFp, $gblError;
	try {
		$sentencia = "insert into catfp ( codigo, descrip, enuso) values (:codigo, :descripcion, :enuso) ";
		$query = $conexion->prepare($sentencia);
		$params = [":codigo" => $CatFp["codigo"],
			":descripcion" => $CatFp["descripcion"],
			":enuso" => $CatFp["enuso"]];
		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR EN CATFP  : ID= " . $CatFp["id"] . " CODIGO= " . $CatFp["codigo"] . " DESCRIPCION= " . $CatFp["descripcion"] . "\n";
			$gblError = 1;
			return null;
		}
		echo "==>INSERT EN CATFP  : ID= " . $CatFp["id"] . " CODIGO= " . $CatFp["codigo"] . " DESCRIPCION= " . $CatFp["descripcion"] . "\n";
		return true;
	} catch (PDOException $ex) {
		echo "**PDOERROR EN INSERT CATFP : ID= " . $CatFp["id"] . " CODIGO= " . $CatFp["codigo"] . " DESCRIPCION= " . $CatFp["descripcion"] . "\n"
			. $ex->getMessage() . "\n";
		$gblError = 1;
		return null;
	}
}

/**
 * @param $conexion
 * @param $codigo
 * @return bool|null
 */
function updateCatFp($conexion, $codigo)
{
	global $CatFp, $gblError;
	try {
		$sentencia = " update catfp set descrip = :descripcion , enuso = :enuso where codigo = :codigo";
		$query = $conexion->prepare($sentencia);
		$params = [":codigo" => $codigo,
			":descripcion" => $CatFp["descripcion"],
			":enuso" => $CatFp["enuso"]];
		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR EN UPDATE CATFP : ID= " . $CatFp["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatFp["descripcion"] . "\n";
			$gblError = 1;
			return null;
		}
		echo "==>UPDATE CATFP : ID= " . $CatFp["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatFp["descripcion"] . "\n";
		return true;
	} catch (PDOException $ex) {
		echo "**PDOERROR EN UPDATE CATFP : ID= " . $CatFp["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatFp["descripcion"] . "\n"
			. $ex->getMessage() . "\n";
		$gblError = 1;
		return null;
	}
}

/**
 * @return bool|null
 */
function updateEqCatFpControl()
{
	global $CatFp, $EqCatFp, $JanoControl, $gblError;
	try {
		$sentencia = " update gums_eq_catfp set enuso = :enuso where catfp_id = :catfp_id and edificio_id = :edificio_id";
		$query = $JanoControl->prepare($sentencia);
		$params = [":catfp_id" => $CatFp["id"],
			":edificio_id" => $EqCatFp["edificio_id"],
			":enuso" => $EqCatFp["enuso"]];
		$ins = $query->execute($params);
		if ($ins == 0) {
			echo "**ERROR EN UPDATE gums_eq_catfp : ID= " . $CatFp["id"] . " CODIGO= " . $CatFp . " DESCRIPCION= " . $CatFp["descripcion"] . "\n";
			$gblError = 1;
			return null;
		}
		echo "==>UPDATE gums_eq_catfp : ID= " . $CatFp["id"]
			. " CODIGO= " . $CatFp["codigo"] . " DESCRIPCION= " . $CatFp["descripcion"]
			. " EDIFICIO= " . $EqCatFp["edificio"]
			. " ENUSO= " . $EqCatFp["enuso"] . "\n";
		return true;
	} catch (PDOException $ex) {
		echo "**PDOERROR EN UPDATE gums_catfp : ID= " . $CatFp["id"] . " CODIGO= " . $CatFp["codigo"] . " DESCRIPCION= " . $CatFp["descripcion"] . "\n"
			. $ex->getMessage() . "\n";
		$gblError = 1;
		return null;
	}
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */

echo " +++++++++++ COMIENZA PROCESO REPLICA DE CATEGORIA CATFP  ++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$tipo = $argv[1];
$catfp_id = $argv[2];
$actuacion = $argv[3];
$eqcatfp_id = $argv[4];

$gblError = 0;

if ($tipo == 'REAL') {
	echo "==> ENTORNO : PRODUCCIÓN  \n";
	$JanoInte = conexionPDO(selectBaseDatos(2, 'I'));
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
	$tipobd = 2;
} else {
	echo "==> ENTORNO : VALIDACIÓN  \n";
	$JanoInte = conexionPDO(selectBaseDatos(1, 'I'));
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
	$tipobd = 1;
}

$CatFp = selectCatFpById($catfp_id);
if (!$CatFp) {
	exit(1);
}
echo "==> CATEGORIA FP : ID=" . $CatFp["id"]
	. " CODIGO= " . $CatFp["codigo"]
	. " DESCRIPCION= " . $CatFp["descripcion"]
	. " EQCATFP_ID: " . $eqcatfp_id
	. " ACTUACION : " . $actuacion
	. "\n";

if ($actuacion == 'INSERT') {
	if (insertCatFp($JanoUnif)) {
		$EqCatFpAll = selectEqCatFpAll();
		foreach ($EqCatFpAll as $EqCatFp) {
			$conexion = conexionEdificio($EqCatFp["edificio"], $tipobd);
			if ($conexion) {
				if (insertCatFp($conexion)) {
					insertEqCatFp();
					$EqCatFp["enuso"] = 'S';
					updateEqCatFpControl();
				}
			}
		}
	}
}

if ($actuacion == 'UPDATE') {
	if (updateCatFp($JanoUnif, $CatFp["codigo"])) {
		$EqCatFpAll = selectEqCatFpAll();
		foreach ($EqCatFpAll as $EqCatFp) {
			$conexion = conexionEdificio($EqCatFp["edificio"], $tipobd);
			if ($conexion) {
				updateCatFp($conexion, $EqCatFp["codigo_loc"]);
			}
		}
	}
}

if ($actuacion == 'ACTIVAR') {
	$EqCatFp = selectEqCatFpById($eqcatfp_id);
	$conexion = conexionEdificio($EqCatFp["edificio"], $tipobd);
	$CatFp["enuso"] = 'S';
	if ($conexion) {
		updateCatFp($conexion, $EqCatFp["codigo_loc"]);
		$EqCatFp["enuso"] = 'S';
		updateEqCatFpControl();
	}
}

if ($actuacion == 'DESACTIVAR') {
	$EqCatFp = selectEqCatFpById($eqcatfp_id);
	$conexion = conexionEdificio($EqCatFp["edificio"], $tipobd);
	$CatFp["enuso"] = 'N';
	if ($conexion) {
		updateCatFp($conexion, $EqCatFp["codigo_loc"]);
		$EqCatFp["enuso"] = 'N';
		updateEqCatFpControl();
	}
}

if ($actuacion == 'CREAR') {
	$EqCatFp = selectEqCatFpById($eqcatfp_id);
	var_dump($EqCatFp);
	$conexion = conexionEdificio($EqCatFp["edificio"], $tipobd);
	$CatFp["codigo"] = $EqCatFp["codigo_loc"];
	if ($conexion) {
		insertCatFp($conexion);
		$EqCatFp["enuso"] = 'S';
		updateEqCatFpControl();
	}
}

echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit($gblError);
