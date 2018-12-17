<?php

include_once __DIR__ . '/../funcionesDAO.php';
/**
 * @param $Uf
 * @param $enuso
 * @return null
 */
function updateEqCentrosUf($Uf, $enuso)
{
	global $JanoInte;
var_dump($Uf);

	try {
		$sentencia = " update eq_centros set enuso = :enuso  "
			. "where codigo_uni = :codigo_uni"
			. " and edificio = :edificio "
			. " and codigo_loc = :codigo_loc "
			. " and vista = 'U'";
		$update = $JanoInte->prepare($sentencia);
		$params = [":enuso" => $enuso,
			":codigo_uni" => $Uf["uf"],
			":codigo_loc" => $Uf["codigoLoc"],
			":edificio" => $Uf["edificio"]];
		$res = $update->execute($params);
		if ($res == 0) {
			echo "**ERROR EN UPDATE EQ_CENTROS(U) CODIGO_UNI =(" . $Uf["uf"] . ") "
				. " CODIGO_LOC =(" . $Uf["codigoLoc"] . ") "
				. " EDIFICIO =(" . $Uf["edificio"] . ") "
				. "\n";
			return null;
		}
		echo "**MODIFICADO EQ_CENTROS(U) ENUSO =(" . $enuso . ") "
			. " CODIGO_UNI =(" . $Uf["uf"] . ") "
			. " CODIGO_LOC =(" . $Uf["codigoLoc"] . ") "
			. " EDIFICIO =(" . $Uf["edificio"] . ") "
			. "\n";

	} catch (PDOException $exception) {
		echo "**ERROR EN UPDATE EQ_CENTROS(U) CODIGO_UNI =(" . $Uf["uf"] . ") "
			. " CODIGO_LOC =(" . $Uf["codigoLoc"] . ") "
			. " EDIFICIO =(" . $Uf["edificio"] . ") "
			. $exception->getMessage()
			. "\n";
		return null;
	}
}

/**
 * @param $Uf
 * @param $equf_id
 * @param $enuso
 * @return null
 */
function updateEqUf($Uf,$equf_id, $enuso)
{
	global $JanoControl;

	try {
		$sentencia = " update ccap_eq_uf set enuso = :enuso  "
			. "where id = :id ";
		$update = $JanoControl->prepare($sentencia);
		$params = [":enuso" => $enuso,
			":id" => $equf_id];
		$res = $update->execute($params);

		if ($res == 0) {
			echo "**ERROR EN UPDATE CCAP_EQ_UF CODIGO_UNI =(" . $Uf["uf"] . ") "
				. " ID =(" . $equf_id. ") "
				. "\n";
			return null;
		}
		echo "**MODIFICADO CCAP_EQ_UF ENUSO =(" . $enuso . ") "
			. " CODIGO_UNI =(" . $Uf["uf"] . ") "
			. " ID =(" . $equf_id. ") "
			. "\n";

	} catch (PDOException $exception) {
		echo "**PDOERROR EN UPDATE CCAP_EQ_UF CODIGO_UNI =(" . $Uf["uf"] . ") "
			. " ID =(" . $equf_id. ") "
			. $exception->getMessage()
			. "\n";

	}
}

/**
 * @param $Uf
 * @return bool|null
 */
function crearCentroUnif($Uf)
{
	global $JanoUnif, $gblError;
	/*
	 * Se genera el registro en la tabla centros de la base de datos unificada
	 */
	try {
		$query = " insert into centros (codigo, descrip, vista, enuso, f_creacion, oficial, da, gerencia ) "
			. " values (:codigo, :descrip, :vista, :enuso, :f_creacion, :oficial, :da, :gerencia )";
		$query = $JanoUnif->prepare($query);
		$paramsCentros = [":codigo" => $Uf["uf"],
			":descrip" => $Uf["descripcion"],
			":vista" => 'U',
			":enuso" => 'S',
			":f_creacion" => $Uf["fecha_creacion"],
			":oficial" => $Uf["oficial"],
			":da" => $Uf["da"],
			":gerencia" => $Uf["gerencia"]];
		$insert = $query->execute($paramsCentros);
		if ($insert == 0) {
			echo "**ERROR EN INSERT CENTROS BD. UNIFICADA " . $Uf["uf"] . "\n";
			$gblError = 1;
		} else {
			echo "==>GENERADO CENTRO =" .
				$Uf["uf"] . " " .
				$Uf["descripcion"] .
				" EN LA BASE DE DATOS UNIFICADA " . "\n";
		}
		return true;
	} catch (PDOException $ex) {
		echo " *** PDOERROR CENTROS BD UNIFICADA CODIGO= " . $Uf["uf"] . "  \n" . $ex->getMessage() . "\n";
		return null;
	}
}

/**
 * @param $Uf
 * @return bool
 */
function crearEqCentroInte($Uf)
{
	global $JanoInte, $gblError;
	/*
	 * Se genera el registro en la tabla de equivalencias de centros de la base de datos intermedia
	 */
	try {
		$query = " insert into eq_centros (edificio, codigo_loc, codigo_uni, oficial, da, vista) "
			. " values (:edificio, :codigo_loc, :codigo_uni, :oficial, :da, :vista)";
		$query = $JanoInte->prepare($query);
		$params = [":edificio" => $Uf["edificio"],
			":codigo_loc" => $Uf["codigoLoc"],
			":codigo_uni" => $Uf["uf"],
			":oficial" => $Uf["oficial"],
			":da" => $Uf["da"],
			":vista" => 'U'];
		$insert = $query->execute($params);
		if (!$insert) {
			echo "***ERROR EN INSERT EQ_CENTROS BD. INTERMEDIA " . $Uf["codigoUnif"] . "\n";
			$gblError = 1;
		}
		echo " GENERADO EQ_CENTRO EDIF=" . $Uf["edificio"]
			. " CODIGO_LOC= " . $Uf["codigoLoc"]
			. " CODIGO_UNI= " . $Uf["uf"]
			. " OFICIAL= " . $Uf["oficial"]
			. " DA= " . $Uf["da"] . "\n";

		return true;
	} catch (PDOException $ex) {
		echo "PDOERROR EN EQ_CENTROS BD INTERMEDIA " . $Uf["codigoUnif"] . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Uf
 * @return bool|null
 */
function crearCentroArea($Uf)
{
	global $tipobd, $gblError;
	/*
	 * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente
	 */
	$conexion = conexionEdificio($Uf["edificio"], $tipobd);
	if ($conexion == null) {
		echo "*** ERROR NO EXISTE CONEXION PARA EDIFICIO: " . $Uf["edificio"] . " TIPO BD=" . $tipobd;
		$gblError = 1;
		return null;
	}

	try {
		$query = " insert into centros (codigo, descrip, vista, enuso, f_creacion ) "
			. " values (:codigo,:descrip, :vista, :enuso, :f_creacion )";
		$query = $conexion->prepare($query);
		$paramsCentros = [":codigo" => $Uf["codigoLoc"],
			":descrip" => $Uf["descripcion"],
			":vista" => 'U',
			":enuso" => 'S',
			":f_creacion" => $Uf["fecha_creacion"]
		];
		$insert = $query->execute($paramsCentros);
		if ($insert == 0) {
			echo "***ERROR INSERT CENTROS BD. AREA " . $Uf["edificio"] . " " . $Uf["uf"] . "\n";
			$gblError = 1;
		}
		echo "==>GENERADO CENTRO =" .
			$Uf["codigoLoc"] . " " .
			$Uf["descripcion"] .
			" EN LA BASE DE DATOS AREA " . $Uf["edificio"] . "\n";

		return true;
	} catch (PDOException $ex) {
		echo "***PDOERROR CENTROS BD AREA " . $Uf["edificio"] . " " . $Uf["uf"] . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
	}
}

/**
 * @param $Uf
 * @return null
 */
function updateCentroUnif($Uf)
{
	global $JanoUnif, $gblError;
	/*
	 * Se genera el registro en la tabla centros de la base de datos unificada
	 */
	try {
		$sentencia = "  update centros set  "
			. " descrip = :descrip "
			. " ,enuso = :enuso "
			. " ,f_creacion = :f_creacion"
			. " ,oficial = :oficial"
			. " ,da = :da"
			. " ,gerencia = :gerencia "
			. " where codigo = :codigo ";
		$query = $JanoUnif->prepare($sentencia);
		$paramsCentros = [":codigo" => $Uf["uf"],
			":descrip" => $Uf["descripcion"],
			":enuso" => $Uf["enuso"],
			":f_creacion" => $Uf["fechaCreacion"],
			":oficial" => $Uf["oficial"],
			":da" => $Uf["da"],
			":gerencia" => $Uf["gerencia"]];
		$insert = $query->execute($paramsCentros);
		if ($insert == 0) {
			echo "**ERROR EN UPDATE CENTROS BD. UNIFICADA " . $Uf["uf"] . "\n";
			$gblError = 1;
			return null;
		}
		echo " MODIFICADO CENTRO =" .
			$Uf["uf"] . " " .
			$Uf["descripcion"] .
			" EN LA BASE DE DATOS UNIFICADA " . "\n";

		return $Uf["uf"];
	} catch (PDOException $ex) {
		echo " *** PDOERROR UPDATE CENTROS BD UNIFICADA CODIGO= " . $Uf["uf"] . "  \n" . $ex->getMessage() . "\n";
		$gblError = 1;
		return null;
	}
}

/**
 * @param $Uf
 * @return bool|null
 */
function updateCentroArea($Uf)
{
	global $tipobd, $gblError;
	/*
	 * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente
	 */
	$codigoLoc = selectEqCentro($Uf["uf"], $Uf["edificio"], 'U');
	$conexion = conexionEdificio($Uf["edificio"], $tipobd);

	if ($conexion == null) {
		echo "***ERROR EN LA CONEXIÓN EDIFICIO= " . $Uf["edificio"] . " TIPO BD=" . $tipobd . "\n";
		$gblError = 1;
		return null;
	}

	try {
		$query = " update centros set "
			. " descrip = :descrip,"
			. " enuso = :enuso,"
			. " f_creacion = :f_creacion"
			. " where codigo = :codigo ";
		$query = $conexion->prepare($query);
		$paramsCentros = [":codigo" => $codigoLoc,
			":descrip" => $Uf["descripcion"],
			":enuso" => $Uf["enuso"],
			":f_creacion" => $Uf["fechaCreacion"]
		];
		$insert = $query->execute($paramsCentros);
		if ($insert == 0) {
			echo "***ERROR  UPDATE CENTROS BD. AREA " . $Uf["edificio"] . " " . $codigoLoc . "\n";
			$gblError = 1;
			return null;
		}
		echo " MODIFICADO CENTRO =" .
			$codigoLoc . " " .
			$Uf["descripcion"] .
			" EN LA BASE DE DATOS AREA " . $Uf["edificio"] . "\n";

		return true;
	} catch (PDOException $ex) {
		echo "PDOERROR CENTROS BD AREA " . $Uf["edificio"] . " " . $codigoLoc . $ex->getMessage() . "\n";
		$gblError = 1;
		return false;
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
$uf_id = $argv[2];
$actuacion = $argv[3];
$equf_id = $argv[4];
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

$Uf = selectUfById($uf_id);
if ($Uf == null) {
	echo "****ERROR NO EXISTE UF ID=" . $uf_id . "\n";
	echo "** TERMINA EN ERROR n";
	exit(1);
}

$Uf["codigoLoc"] = substr($Uf["oficial"], 4, 6);

echo "==> UNIDAD FUNCIONAL ID=" . $Uf["id"]
	. " UF= " . $Uf["uf"]
	. " OFICIAL= " . $Uf["oficial"]
	. " CODIGO LOCAL= " . $Uf["codigoLoc"]
	. " DESCRIPCION= " . $Uf["descripcion"]
	. " EDIFICIO= " . $Uf["edificio"]
	. " DA= " . $Uf["da"]
	. " USO= " . $Uf["enuso"]
	. " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ($actuacion == 'INSERT') {
	if (crearCentroUnif($Uf)) {
		if (crearCentroArea($Uf)) {
			crearEqCentroInte($Uf);
		}
	}
}

if ($actuacion == 'UPDATE') {
	updateCentroUnif($Uf);
	updateCentroArea($Uf);
}

if ($actuacion == 'ACTIVAR') {
	updateEqCentrosUf($Uf, 'S');
	updateEqUf($Uf, $equf_id,'S');
}

if ($actuacion == 'DESACTIVAR') {
	updateEqCentrosUf($Uf, 'N');
	updateEqUf($Uf,$equf_id, 'N');
}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN UNIDAD FUNCIONAL +++++++++++++ \n";
exit($gblError);
