<?php

include_once __DIR__ . '/../funcionesDAO.php';
/**
 * @return bool
 */
function insertCategUnif()
{
	global $JanoUnif, $Categ;
	try {
		$sentencia = " insert into categ "
			. " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
			. " ,ocupacion, mir, condicionado, directivo ) values "
			. " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :GrupoCot, :epiacc, :grupoprof, :enuso, :grupocobro "
			. " ,:ocupacion, :mir, :condicionado, :directivo )";
		$insert = $JanoUnif->prepare($sentencia);

		$params = [":codigo" => $Categ["codigo"],
			":catgen" => $Categ["catgen"],
			":descrip" => $Categ["descripcion"],
			":fsn" => $Categ["fsn"],
			":catanexo" => $Categ["catanexo"],
			":GrupoCot" => $Categ["GrupoCot"],
			":epiacc" => $Categ["epiacc"],
			":grupoprof" => $Categ["grupoprof"],
			":enuso" => $Categ["enuso"],
			":grupocobro" => $Categ["grupocobro"],
			":ocupacion" => $Categ["ocupacion"],
			":mir" => $Categ["mir"],
			":condicionado" => $Categ["condicionado"],
			":directivo" => $Categ["directivo"]];

		$res = $insert->execute($params);
		if ($res) {
			echo " --> CATEGORIA " . $Categ["codigo"] . " " . $Categ["descripcion"] . " CREADA EN LA B.D. UNIFICADA \n";
			return true;
		} else {
			echo "**ERROR EN INSERT CATEGORIA " . $Categ["codigo"] . " " . $Categ["descripcion"] . "\n";
			return false;
		}
	} catch (PDOException $ex) {
		if ($ex->getCode() == '23000') {
			updateCategUnif($Categ);
			return true;
		} else {
			echo "***PDOERROR EN INSERT BASE DE DATOS UNIFICADA CATEGORIA " . $Categ["codigo"] . " " . $Categ["descripcion"] . "\n"
				. $ex->getMessage() . "\n";
			return false;
		}
	}
}

/**
 * @param array $Categ
 * @param $edificio
 * @return null
 */
function equivalenciasCateg($Categ, $edificio)
{
	$edificio_id = selectEdificio($edificio);
	$catgen = selectEqCatGen($Categ["catgen_id"], $edificio_id);
	$catanexo = selectEqCatAnexo($Categ["catanexo_id"], $edificio_id);
	$GrupoCot = selectEqGrupoCot($Categ["grupocot_id"], $edificio_id);
	//$epiacc = selectEqEpiAcc($Categ["epiacc"],$edificio);
	$grupoprof = selectEqGrupoProf($Categ["grupoprof_id"], $edificio_id);
	$grupocobro = selectEqGrupoCobro($Categ["grupocobro_id"], $edificio_id);
	$ocupacion = selectEqOcupacion($Categ["ocupacion_id"], $edificio_id);

	if ($catgen && $catanexo && $GrupoCot && $grupoprof && $grupocobro && $ocupacion) {
		$equivalenciasCateg["catgen"] = $catgen;
		$equivalenciasCateg["catanexo"] = $catanexo;
		$equivalenciasCateg["GrupoCot"] = $GrupoCot;
		$equivalenciasCateg["grupoprof"] = $grupoprof;
		$equivalenciasCateg["grupocobro"] = $grupocobro;
		$equivalenciasCateg["ocupacion"] = $ocupacion;

		echo "EQUIVALENCIAS (UNIFICADA)/(AREA)\n";
		echo "-------------\n";
		echo " catgen = " . $Categ["catgen"] . "/" . $catgen . "\n";
		echo " catanexo = " . $Categ["catanexo"] . "/" . $catanexo . "\n";
		echo " grupocot = " . $Categ["grupocot"] . "/" . $GrupoCot . "\n";
		echo " grupoprof = " . $Categ["grupoprof"] . "/" . $grupoprof . "\n";
		echo " grupocobro = " . $Categ["grupocobro"] . "/" . $grupocobro . "\n";
		echo " ocupacion = " . $Categ["ocupacion"] . "/" . $ocupacion . "\n";

		return $equivalenciasCateg;
	} else {
		return null;
	}
}

/**
 * @param array $Categ
 * @param \PDO $conexion
 * @param string $edificio
 * @return bool
 */
function insertCategAreas($Categ, $conexion, $edificio)
{
	try {
		$sentencia = " insert into categ "
			. " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
			. " ,ocupacion, mir, condicionado, directivo ) values "
			. " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :grupocot, :epiacc, :grupoprof, :enuso, :grupocobro "
			. " ,:ocupacion, :mir, :condicionado, :directivo )";

		$insert = $conexion->prepare($sentencia);

		$params = parametrosCateg($Categ, $edificio);

		if (!$params)
			return false;

		$res = $insert->execute($params);
		if ($res) {
			echo " => INSERT CATEGORIA PROFESIONAL(CATEG) " . $Categ["codigo"] . " " . $Categ["descripcion"] . "\n";
			return true;
		} else {
			echo "**ERROR EN INSERT CATEG CODIGO=" . $Categ["codigo"] . " " . $Categ["descrip"] . "\n";
			return false;
		}
	} catch (PDOException $ex) {
		echo "**PDOERROR EN INSERT CATEG CODIGO= " . $Categ["codigo"] . " " . $Categ["descrip"] . $ex->getMessage() . "\n";
		return false;
	}
}

/**
 * @param array $Categ
 * @param string $edificio
 * @return array|null
 */
function parametrosCateg($Categ, $edificio)
{

	$equivalenciasCateg = equivalenciasCateg($Categ, $edificio);
	if (!$equivalenciasCateg) {
		echo "**ERROR AL ESTABLECER LAS EQUIVALENCIAS \n";
		return null;
	}

	$params = [":codigo" => $Categ["codigo"],
		":catgen" => $equivalenciasCateg["catgen"],
		":descrip" => $Categ["descripcion"],
		":fsn" => $Categ["fsn"],
		":catanexo" => $equivalenciasCateg["catanexo"],
		":grupocot" => $equivalenciasCateg["grupocot"],
		":epiacc" => $Categ["epiacc"],
		":grupoprof" => $equivalenciasCateg["grupoprof"],
		":enuso" => $Categ["enuso"],
		":grupocobro" => $equivalenciasCateg["grupocobro"],
		":ocupacion" => $equivalenciasCateg["ocupacion"],
		":mir" => $Categ["mir"],
		":condicionado" => $Categ["condicionado"],
		":directivo" => $Categ["directivo"]];

	return $params;
}

/**
 * @return bool
 */
function updateEqCategControl()
{
	global $JanoControl, $Categ, $EqCateg;
	try {
		$sql = " update gums_eq_categ set "
			. " codigo_loc = :codigo_loc"
			. " ,enuso = :enuso"
			. " where id = :id ";
		$query = $JanoControl->prepare($sql);
		$params = [":codigo_loc" => $Categ["codigo"],
			":id" => $EqCateg["id"],
			":enuso" => $EqCateg["enuso"]];
		$res = $query->execute($params);
		if ($res == 0) {
			echo "**ERROR EN UPDATE GUMS_EQ_CATEG CODIGO_LOC=(" . $EqCateg["codigo_loc"] . ") EDIFICIO=(" . $EqCateg["edificio"] . ") Uso= (" . $EqCateg["enuso"] . ") \n";
			return null;
		}
		echo "-->UPDATE GUMS_EQ_CATEG CODIGO_LOC=(" . $EqCateg["codigo_loc"] . ") EDIFICIO=(" . $EqCateg["edificio"] . ") Uso= (" . $EqCateg["enuso"] . ") \n";
		return true;
	} catch (PDOException $ex) {
		echo "***PDOERROR EN UPDATE GUMS_EQ_CATEG CATEG= " . $Categ["codigo"] . " EDIFICIO=" . $EqCateg["edificio"] . "\n"
			. $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @return bool
 */
function procesoUpdate()
{
	global $Categ, $tipobd;
	/*
	 * Insert en la tabla categ de la base de datos unificada
	 */
	if (!updateCategUnif($Categ)) {
		echo "****ERROR EN LA ACTUALIZACIÓN EN LA BASE DE DATOS UNIFICADA \n";
		return false;
	}
	$inicio = 0;
	$fin = 12;

	if ($Categ["replica"] == 1) { /* SE REPLICA EN TODAS LAS BASES DE DATOS */
		$inicio = 0;
		$fin = 12;
	}
	if ($Categ["replica"] == 2) { /* SE REPLICA SOLO EN EL AREA ÚNICA */
		$inicio = 0;
		$fin = 1;
	}
	if ($Categ["replica"] == 3) { /* SE REPLICA EN TODAS LAS AREAS EXCEPTO EN EL AREA ÚNICA */
		$inicio = 1;
		$fin = 12;
	}

	for ($i = $inicio; $i < $fin; $i++) {
		echo "-->(" . $i . ") Equivalencia Código " . $Categ["codigo"] . " \n";
		$codigo = selectEqCateg($Categ["id"], selectEdificio($i));
		if ($codigo) {
			echo "-->Codigo = " . $Categ["codigo"] . "/" . $codigo . "\n";
			$conexion = conexionEdificio($i, $tipobd);
			if ($conexion) {
				updateCategAreas($Categ, $conexion, $codigo, $i);
			}
		}
	}

	return true;
}

/**
 * @param array $Categ
 * @return bool
 */
function updateCategUnif($Categ)
{
	global $JanoUnif;
	try {
		$sentencia = " update categ set"
			. "  catgen = :catgen "
			. " ,descrip = :descrip"
			. " ,fsn = :fsn "
			. " ,catanexo = :catanexo"
			. " ,grupcot = :grupocot"
			. " ,epiacc = :epiacc"
			. " ,grupoprof = :grupoprof"
			. " ,enuso = :enuso"
			. " ,grupocobro = :grupocobro "
			. " ,ocupacion = :ocupacion"
			. " ,mir = :mir "
			. " ,condicionado = :condicionado"
			. " ,directivo = :directivo"
			. " where codigo = :codigo ";
		$update = $JanoUnif->prepare($sentencia);

		$params = [":codigo" => $Categ["codigo"],
			":catgen" => $Categ["catgen"],
			":descrip" => $Categ["descripcion"],
			":fsn" => $Categ["fsn"],
			":catanexo" => $Categ["catanexo"],
			":grupocot" => $Categ["grupocot"],
			":epiacc" => $Categ["epiacc"],
			":grupoprof" => $Categ["grupoprof"],
			":enuso" => $Categ["enuso"],
			":grupocobro" => $Categ["grupocobro"],
			":ocupacion" => $Categ["ocupacion"],
			":mir" => $Categ["mir"],
			":condicionado" => $Categ["condicionado"],
			":directivo" => $Categ["directivo"]];

		$res = $update->execute($params);
		if ($res) {
			echo "--> CATEGORIA " . $Categ["codigo"] . " " . $Categ["descripcion"] . " MODIFICADA BASE DE DATOS UNIFICADA\n";
			return true;
		} else {
			echo "****ERROR EN UPDATE BASE DE DATOS UNIFICADA CATEGORIA " . $Categ["codigo"] . " " . $Categ["descrip"] . "\n";
			return false;
		}
	} catch (PDOException $ex) {
		echo "****PDOERROR EN UPDATE BASE DE DATOS UNIFICADA CATEGORIA " . $Categ["codigo"] . " " . $Categ["descrip"] . $ex->getMessage() . "\n";
		return false;
	}
}

/**
 * @param array $Categ
 * @param $conexion
 * @param $codigo
 * @param $edificio
 * @return bool
 */
function updateCategAreas($Categ, $conexion, $codigo, $edificio)
{
	try {
		$sentencia = " update categ set "
			. "  catgen = :catgen "
			. " ,descrip = :descrip"
			. " ,fsn = :fsn "
			. " ,catanexo = :catanexo"
			. " ,grupcot = :grupocot"
			. " ,epiacc = :epiacc"
			. " ,grupoprof = :grupoprof"
			. " ,enuso = :enuso"
			. " ,grupocobro = :grupocobro "
			. " ,ocupacion = :ocupacion "
			. " ,mir = :mir "
			. " ,condicionado = :condicionado"
			. " ,directivo = :directivo"
			. " where codigo = :codigo ";
		var_dump("update");
		$update = $conexion->prepare($sentencia);

		$params = parametrosCateg($Categ, $edificio);

		if (!$params)
			return false;

		$res = $update->execute($params);
		if ($res) {
			echo " => CATEGORIA " . $codigo . " " . trim($Categ["descripcion"]) . " MODIFICADA \n";
			return true;
		} else {
			echo "***ERROR EN UPDATE CATEGORIA " . $codigo . " " . $Categ["descrip"] . "\n";
			return false;
		}
	} catch (PDOException $ex) {
		echo "***PDOERROR EN UPDATE CATEGORIA PROFESIONAL (CATEG) " . $codigo . " " . $Categ["descrip"] . $ex->getMessage() . "\n";
		return false;
	}
}

/**
 * @return bool|null
 */
function procesoInsert()
{
	global $tipobd, $Categ;
	/*
	 * Insert en la tabla categ de la base de datos unificada
	 */
	if (!insertCategUnif()) {
		echo "***ERROR EN LA INSERT EN LA BASE DE DATOS UNIFICADA \n";
		return null;
	}
	/*
	 * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas
	 */
	$inicio = 0;
	$fin = 12;
	if ($Categ["replica"] == 1) { /* SE REPLICA EN TODAS LAS BASES DE DATOS */
		$inicio = 0;
		$fin = 12;
	}
	if ($Categ["replica"] == 2) { /* SE REPLICA SOLO EN EL AREA ÚNICA */
		$inicio = 0;
		$fin = 1;
	}
	if ($Categ["replica"] == 3) { /* SE REPLICA EN TODAS LAS AREAS EXCEPTO EN EL AREA ÚNICA */
		$inicio = 1;
		$fin = 12;
	}

	for ($i = $inicio; $i < $fin; $i++) {
		$conexion = conexionEdificio($i, $tipobd);
		if ($conexion) {
			if (insertCategAreas($Categ, $conexion, $i)) {
				insertEqCateg($Categ, $i);
				updateEqCategControl();
			}
		}
	}

	return true;
}

/**
 * @param array $Categ
 * @param $area
 * @return bool
 */
function insertEqCateg($Categ, $area)
{
	global $JanoInte;
	try {
		$sentencia = " insert into eq_categ "
			. " ( edificio, codigo_loc, codigo_uni ) "
			. " values "
			. " ( :edificio, :codigo_loc, :codigo_uni ) ";
		$insert = $JanoInte->prepare($sentencia);
		$params = [":edificio" => $area,
			":codigo_loc" => $Categ["codigo"],
			":codigo_uni" => $Categ["codigo"]];
		$res = $insert->execute($params);
		if ($res) {
			echo " EQUIVALENCIA GENERADA EDIFICIO= " . $area
				. " CODIGO_LOC = " . $Categ["codigo"]
				. " CODIGO_UNI = " . $Categ["codigo"] . "\n";
			return true;
		} else {
			echo "  ERROR EN INSERT EQ_CATEG " . $Categ["codigo"] . "\n";
			return false;
		}
	} catch (PDOException $ex) {
		echo "  PDOERROR EN INSERT EQ_CATEG " . $Categ["codigo"] . $ex->getMessage() . "\n";
		return false;
	}
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO REPLICA DE CATEGORIA PROFESIONAL (CATEG) ++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
	exit(1);
}

$tipo = $argv[1];
$categ_id = $argv[2];
$actuacion = $argv[3];
$eqcateg_id = $argv[4];

if ($tipo == 'REAL') {
	echo " ++++ PRODUCCIÓN ++++ \n";
	$JanoInte = conexionPDO(selectBaseDatos(2, 'I'));
	$JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
	$tipobd = 2;
} else {
	echo " ++++ VALIDACIÓN ++++ \n";
	$JanoInte = conexionPDO(selectBaseDatos(1, 'I'));
	$JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
	$tipobd = 1;
}
$Categ = selectCateg($categ_id);

echo "==> CATEGORIA PROFESIONAL(CATEG) : ID=" . $Categ["id"]
	. " CODIGO= " . $Categ["codigo"]
	. " DESCRIPCION= " . $Categ["descripcion"]
	. " CATGEN= " . $Categ["catgen"]
	. " CATANEXO= " . $Categ["catanexo"]
	. " GRUPOCOT= " . $Categ["grupocot"]
	. " GRUPOPROF= " . $Categ["grupoprof"]
	. " GRUPOCOBRO= " . $Categ["grupocobro"]
	. " OCUPACION= " . $Categ["ocupacion"]
	. " EPIACC= " . $Categ["epiacc"]
	. " \n";

echo "==> ACTUACION= (" . $actuacion . ") REPLICA= (" . $Categ["replica"] . ") EQUIVALENCIA(EQCATEG_ID)= " . $eqcateg_id . "\n";


if ($actuacion == 'INSERT') {
	if (!procesoInsert()) {
		echo "  +++++++++++ TERMINA PROCESO INSERT EN ERROR +++++++++++++ \n";
		exit(1);
	}
}

if ($actuacion == 'UPDATE') {
	if (!procesoUpdate()) {
		echo "  +++++++++++ TERMINA PROCESO UPDATE EN ERROR +++++++++++++ \n";
		exit(1);
	}
}

if ($actuacion == 'ACTIVAR') {
	$EqCateg = selectEqCategById($eqcateg_id);
	$conexion = conexionEdificio($EqCateg["edificio"], $tipobd);
	$Categ["enuso"] = 'S';
	if ($conexion) {
		updateCategAreas($Categ, $conexion, $EqCateg["codigo_loc"], $EqCateg["edificio"]);
		$EqCateg["enuso"] = 'S';
		updateEqCategControl();
	}
}

if ($actuacion == 'DESACTIVAR') {
	$EqCateg = selectEqCategById($eqcateg_id);
	$conexion = conexionEdificio($EqCateg["edificio"], $tipobd);
	$Categ["enuso"] = 'N';

	if ($conexion) {
		updateCategAreas($Categ, $conexion, $EqCateg["codigo_loc"], $EqCateg["edificio"]);
		$EqCateg["enuso"] = 'N';
		updateEqCategControl();
	}
}

if ($actuacion == 'CREAR') {
	$EqCateg = selectEqCategById($eqcateg_id);
	$conexion = conexionEdificio($EqCateg["edificio"], $tipobd);
	$Categ["codigo"] = $EqCateg["codigo_loc"];
	if ($conexion) {
		if (insertCategAreas($Categ, $conexion, $EqCateg["edificio"])) {
			$EqCateg["enuso"] = 'S';
			updateEqCategControl();
		}
	}
}

echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit(0);
