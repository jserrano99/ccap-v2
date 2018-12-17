<?php

include_once __DIR__ . '/../funcionesDAO.php';

/**
 * @param $Pa
 * @param $enuso
 * @return null
 */
function updateEqCentrosPa($Pa, $enuso)
{
	global $JanoInte;
	try {
		$sentencia = " update eq_centros set enuso = :enuso  "
			. "where codigo_uni = :codigo_uni"
			. " and edificio = :edificio "
			. " and codigo_loc = :codigo_loc "
			. " and vista = 'P'";
		$update = $JanoInte->prepare($sentencia);
		$params = [":enuso" => $enuso,
			":codigo_uni" => $Pa["pa"],
			":codigo_loc" => $Pa["codigoLoc"],
			":edificio" => $Pa["edificio"]];
		$res = $update->execute($params);
		if ($res == 0) {
			echo "**ERROR EN UPDATE EQ_CENTROS(U) CODIGO_UNI =(" . $Pa["pa"] . ") "
				. " CODIGO_LOC =(" . $Pa["codigoLoc"] . ") "
				. " EDIFICIO =(" . $Pa["edificio"] . ") "
				. "\n";
			return null;
		}
		echo "**MODIFICADO EQ_CENTROS(U) ENUSO =(" . $enuso . ") "
			. " CODIGO_UNI =(" . $Pa["pa"] . ") "
			. " CODIGO_LOC =(" . $Pa["codigoLoc"] . ") "
			. " EDIFICIO =(" . $Pa["edificio"] . ") "
			. "\n";

	} catch (PDOException $exception) {
		echo "**ERROR EN UPDATE EQ_CENTROS(U) CODIGO_UNI =(" . $Pa["pa"] . ") "
			. " CODIGO_LOC =(" . $Pa["codigoLoc"] . ") "
			. " EDIFICIO =(" . $Pa["edificio"] . ") "
			. $exception->getMessage()
			. "\n";
		return null;
	}
}

/**
 * @param $Pa
 * @param $eqpa_id
 * @param $enuso
 * @return null
 */
function updateEqPa($Pa,$eqpa_id, $enuso)
{
	global $JanoControl;

	try {
		$sentencia = " update ccap_eq_pa set enuso = :enuso  "
			. "where id = :id ";
		$update = $JanoControl->prepare($sentencia);
		$params = [":enuso" => $enuso,
			":id" => $eqpa_id];
		$res = $update->execute($params);

		if ($res == 0) {
			echo "**ERROR EN UPDATE CCAP_EQ_PA CODIGO_UNI =(" . $Pa["pa"] . ") "
				. " ID =(" . $eqpa_id. ") "
				. "\n";
			return null;
		}
		echo "**MODIFICADO CCAP_EQ_PA ENUSO =(" . $enuso . ") "
			. " CODIGO_UNI =(" . $Pa["pa"] . ") "
			. " ID =(" . $eqpa_id. ") "
			. "\n";

	} catch (PDOException $exception) {
		echo "**PDOERROR EN UPDATE CCAP_EQ_PA CODIGO_UNI =(" . $Pa["pa"] . ") "
			. " ID =(" . $eqpa_id. ") "
			. $exception->getMessage()
			. "\n";
	}
}

/**
 * @param $Pa
 * @return bool|null
 */

function crearCentroUnif($Pa) {
    global $JanoUnif, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos unificada 
     */
    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion, oficial, da, gerencia ) "
                . " values (:codigo, :descrip, :vista, :enuso, :f_creacion, :oficial, :da, :gerencia )";
        $query = $JanoUnif->prepare($query);
        $paramsCentros = [":codigo" => $Pa["pa"],
            ":descrip" => $Pa["descripcion"],
            ":vista" => 'P',
            ":enuso" => 'S',
            ":f_creacion" => $Pa["fecha_creacion"],
            ":oficial" => $Pa["oficial"],
            ":da" => $Pa["da"],
            ":gerencia" => $Pa["gerencia"]];
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "**ERROR EN INSERT CENTROS BD. UNIFICADA " . $Pa["pa"] . "\n";
            $gblError = 1;
        } else {
            echo "==>GENERADO CENTRO =" .
            $Pa["pa"] . " " .
            $Pa["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo " *** PDOERROR CENTROS BD UNIFICADA CODIGO= " . $Pa["pa"] . "  \n" . $ex->getMessage() . "\n";
        $gblError=1;
        return null;
    }
}

/**
 * @param $Pa
 * @return bool
 */
function crearEqCentroInte($Pa) {
    global $JanoInte, $gblError,$JanoControl;
    /*
     * Se genera el registro en la tabla de equivalencias de centros de la base de datos intermedia 
     */
    try {
        $query = " insert into eq_centros (edificio, codigo_loc, codigo_uni, oficial, da, vista,enuso) "
                . " values (:edificio, :codigo_loc, :codigo_uni, :oficial, :da, :vista, :enuso)";
        $query = $JanoInte->prepare($query);
        $params = [":edificio" => $Pa["edificio"],
            ":codigo_loc" => $Pa["codigoLoc"],
            ":codigo_uni" => $Pa["pa"],
            ":oficial" => $Pa["oficial"],
            ":da" => $Pa["da"],
            ":vista" => 'P',
	        ":enuso" => 'S'];
        $insert = $query->execute($params);
        if (!$insert) {
            echo "***ERROR EN INSERT EQ_CENTROS BD. INTERMEDIA " . $Pa["codigoUnif"] . "\n";
            $gblError = 1;
        }
        echo " GENERADO EQ_CENTRO EDIF=" . $Pa["edificio"]
        . " CODIGO_LOC= " . $Pa["codigoLoc"]
        . " CODIGO_UNI= " . $Pa["pa"]
        . " OFICIAL= " . $Pa["oficial"]
        . " DA= " . $Pa["da"] . "\n";

        $edificio_id = selectEdificio($Pa["edificio"]);

	    $query = " insert into ccap_eq_pa (edificio_id, codigo_loc, pa_id,enuso) "
		    . " values (:edificio_id, :codigo_loc, :pa_id, :enuso)";
	    $query = $JanoInte->prepare($query);
	    $params = [":edificio_id" => $edificio_id,
		    ":codigo_loc" => $Pa["codigoLoc"],
		    ":pa_id" => $Pa["id"],
		    ":enuso" => 'S'];
	    $query->execute($params);

	    return true;
    } catch (PDOException $ex) {
        echo "PDOERROR EN EQ_CENTROS BD INTERMEDIA " . $Pa["codigoUnif"] . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

/**
 * @param $Pa
 * @return bool|null
 */
function crearCentroArea($Pa) {
    global $tipobd, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $conexion = conexionEdificio($Pa["edificio"], $tipobd);
    if ($conexion == null) {
        echo "*** ERROR NO EXISTE CONEXION PARA EDIFICIO: " . $Pa["edificio"] . " TIPO BD=" . $tipobd;
        $gblError = 1;
        return null;
    }

    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion ) "
                . " values (:codigo,:descrip, :vista, :enuso, :f_creacion )";
        $query = $conexion->prepare($query);
        $paramsCentros = array(":codigo" => $Pa["codigoLoc"],
            ":descrip" => $Pa["descripcion"],
            ":vista" => 'P',
            ":enuso" => 'S',
            ":f_creacion" => $Pa["fecha_creacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "***ERROR INSERT CENTROS BD. AREA " . $Pa["edificio"] . " " . $Pa["pa"] . "\n";
            $gblError = 1;
        }
        echo "==>GENERADO CENTRO =" .
        $Pa["codigoLoc"] . " " .
        $Pa["descripcion"] .
        " EN LA BASE DE DATOS AREA " . $Pa["edificio"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR CENTROS BD AREA " . $Pa["edificio"] . " " . $Pa["pa"] . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

/**
 * @param $Pa
 * @return null
 */
function updateCentroUnif($Pa) {
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
        $paramsCentros = array(":codigo" => $Pa["pa"],
            ":descrip" => $Pa["descripcion"],
            ":enuso" => $Pa["enuso"],
            ":f_creacion" => $Pa["fechaCreacion"],
            ":oficial" => $Pa["oficial"],
            ":da" => $Pa["da"],
            ":gerencia" => $Pa["gerencia"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "**ERROR EN UPDATE CENTROS BD. UNIFICADA " . $Pa["pa"] . "\n";
            $gblError = 1;
            return null;
        }
        echo " MODIFICADO CENTRO =" .
        $Pa["pa"] . " " .
        $Pa["descripcion"] .
        " EN LA BASE DE DATOS UNIFICADA " . "\n";

        return $Pa["pa"];
    } catch (PDOException $ex) {
        echo " *** PDOERROR UPDATE CENTROS BD UNIFICADA CODIGO= " . $Pa["pa"] . "  \n" . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/**
 * @param $Pa
 * @return bool|null
 */
function updateCentroArea($Pa) {
    global $tipobd, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $codigoLoc = selectEqCentro($Pa["pa"], $Pa["edificio"], 'P');
    $conexion = conexionEdificio($Pa["edificio"], $tipobd);

    if ($conexion == null) {
        echo "***ERROR EN LA CONEXIÓN EDIFICIO= " . $Pa["edificio"] . " TIPO BD=" . $tipobd . "\n";
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
        $paramsCentros = array(":codigo" => $codigoLoc,
            ":descrip" => $Pa["descripcion"],
            ":enuso" => $Pa["enuso"],
            ":f_creacion" => $Pa["fechaCreacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "***ERROR  UPDATE CENTROS BD. AREA " . $Pa["edificio"] . " " . $codigoLoc . "\n";
            $gblError = 1;
            return null;
        }
        echo " MODIFICADO CENTRO =" .
        $codigoLoc . " " .
        $Pa["descripcion"] .
        " EN LA BASE DE DATOS AREA " . $Pa["edificio"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $Pa["edificio"] . " " . $codigoLoc . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO ACTUALIZACIÓN DE PUNTO ASISTENCIAL (PA) +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$pa_id = $argv[2];
$eqpa_id = $argv[3];
$actuacion = $argv[4];

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

$Pa = selectPaById($pa_id);
if ($Pa== null ) {
    echo "****ERROR NO EXISTE PA ID=". $pa_id. "\n";
    echo "** TERMINA EN ERROR n";
    exit(1);
}

$Pa["codigoLoc"] = substr($Pa["oficial"], 4, 4);

echo "==> PUNTO ASISTENCIAL ID=" . $Pa["id"]
 . " PA= " . $Pa["pa"]
 . " OFICIAL= " . $Pa["oficial"]
 . " CODIGO LOCAL= " . $Pa["codigoLoc"]
 . " DESCRIPCION= " . $Pa["descripcion"]
 . " EDIFICIO= " . $Pa["edificio"]
 . " DA= " . $Pa["da"]
 . " USO= " . $Pa["enuso"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ($actuacion == 'INSERT') {
    if (crearCentroUnif($Pa)) {
        if (crearCentroArea($Pa)) {
            crearEqCentroInte($Pa);
        }
    }
}

if ($actuacion == 'UPDATE') {
    updateCentroUnif($Pa);
    updateCentroArea($Pa);
}

if ($actuacion == 'ACTIVAR') {
	updateEqCentrosPa($Pa, 'S');
	updateEqPa($Pa, $eqpa_id,'S');
}

if ($actuacion == 'DESACTIVAR') {
	updateEqCentrosPa($Pa, 'N');
	updateEqPa($Pa,$eqpa_id, 'N');
}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN PUNTO ASISTENCIAL +++++++++++++ \n";
exit($gblError);
