<?php

include_once __DIR__ . '/../funcionesDAO.php';

function crearCentroUnif($PA) {
    global $JanoUnif, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos unificada 
     */
    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion, oficial, da, gerencia ) "
                . " values (:codigo, :descrip, :vista, :enuso, :f_creacion, :oficial, :da, :gerencia )";
        $query = $JanoUnif->prepare($query);
        $paramsCentros = array(":codigo" => $PA["pa"],
            ":descrip" => $PA["descripcion"],
            ":vista" => 'U',
            ":enuso" => 'S',
            ":f_creacion" => $PA["fecha_creacion"],
            ":oficial" => $PA["oficial"],
            ":da" => $PA["da"],
            ":gerencia" => $PA["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "**ERROR EN INSERT CENTROS BD. UNIFICADA " . $PA["pa"] . "\n";
            $gblError = 1;
        } else {
            echo "==>GENERADO CENTRO =" .
            $PA["pa"] . " " .
            $PA["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo " *** PDOERROR CENTROS BD UNIFICADA CODIGO= " . $PA["pa"] . "  \n" . $ex->getMessage() . "\n";
        $gblError01;
        return null;
    }
}

function crearEqCentroInte($PA) {
    global $JanoInte, $gblError;
    /*
     * Se genera el registro en la tabla de equivalencias de centros de la base de datos intermedia 
     */
    try {
        $query = " insert into eq_centros (edificio, codigo_loc, codigo_uni, oficial, da, vista) "
                . " values (:edificio, :codigo_loc, :codigo_uni, :oficial, :da, :vista)";
        $query = $JanoInte->prepare($query);
        $params = array(":edificio" => $PA["edificio"],
            ":codigo_loc" => $PA["codigoLoc"],
            ":codigo_uni" => $PA["pa"],
            ":oficial" => $PA["oficial"],
            ":da" => $PA["da"],
            ":vista" => 'U');
        $insert = $query->execute($params);
        if (!$insert) {
            echo "***ERROR EN INSERT EQ_CENTROS BD. INTERMEDIA " . $PA["codigoUnif"] . "\n";
            $gblError = 1;
        }
        echo " GENERADO EQ_CENTRO EDIF=" . $PA["edificio"]
        . " CODIGO_LOC= " . $PA["codigoLoc"]
        . " CODIGO_UNI= " . $PA["pa"]
        . " OFICIAL= " . $PA["oficial"]
        . " DA= " . $PA["da"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR EN EQ_CENTROS BD INTERMEDIA " . $PA["codigoUnif"] . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

function crearCentroArea($PA) {
    global $tipobd, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $conexion = conexionEdificio($PA["edificio"], $tipobd);
    if ($conexion == null) {
        echo "*** ERROR NO EXISTE CONEXION PARA EDIFICIO: " . $PA["edificio"] . " TIPO BD=" . $tipobd;
        $gblError = 1;
        return null;
    }

    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion ) "
                . " values (:codigo,:descrip, :vista, :enuso, :f_creacion )";
        $query = $conexion->prepare($query);
        $paramsCentros = array(":codigo" => $PA["codigoLoc"],
            ":descrip" => $PA["descripcion"],
            ":vista" => 'P',
            ":enuso" => 'S',
            ":f_creacion" => $PA["fecha_creacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "***ERROR INSERT CENTROS BD. AREA " . $PA["edificio"] . " " . $PA["pa"] . "\n";
            $gblError = 1;
        }
        echo "==>GENERADO CENTRO =" .
        $PA["codigoLoc"] . " " .
        $PA["descripcion"] .
        " EN LA BASE DE DATOS AREA " . $PA["edificio"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR CENTROS BD AREA " . $PA["edificio"] . " " . $PA["pa"] . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

function updateCentroUnif($PA) {
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
        $paramsCentros = array(":codigo" => $PA["pa"],
            ":descrip" => $PA["descripcion"],
            ":enuso" => $PA["enuso"],
            ":f_creacion" => $PA["fechaCreacion"],
            ":oficial" => $PA["oficial"],
            ":da" => $PA["da"],
            ":gerencia" => $PA["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "**ERROR EN UPDATE CENTROS BD. UNIFICADA " . $PA["pa"] . "\n";
            $gblError = 1;
            return null;
        }
        echo " MODIFICADO CENTRO =" .
        $PA["pa"] . " " .
        $PA["descripcion"] .
        " EN LA BASE DE DATOS UNIFICADA " . "\n";

        return $PA["pa"];
    } catch (PDOException $ex) {
        echo " *** PDOERROR UPDATE CENTROS BD UNIFICADA CODIGO= " . $PA["pa"] . "  \n" . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function updateCentroArea($PA) {
    global $tipobd, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $codigoLoc = selectEqCentro($PA["pa"], $PA["edificio"], 'P');
    $conexion = conexionEdificio($PA["edificio"], $tipobd);

    if ($conexion == null) {
        echo "***ERROR EN LA CONEXIÓN EDIFICIO= " . $PA["edificio"] . " TIPO BD=" . $tipobd . "\n";
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
            ":descrip" => $PA["descripcion"],
            ":enuso" => $PA["enuso"],
            ":f_creacion" => $PA["fechaCreacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "***ERROR  UPDATE CENTROS BD. AREA " . $PA["edificio"] . " " . $codigoLoc . "\n";
            $gblError = 1;
            return null;
        }
        echo " MODIFICADO CENTRO =" .
        $codigoLoc . " " .
        $PA["descripcion"] .
        " EN LA BASE DE DATOS AREA " . $PA["edificio"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $PA["edificio"] . " " . $codigoLoc . $ex->getMessage() . "\n";
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
$actuacion = $argv[3];
$gblError = 0;

if ($tipo == 'REAL') {
    echo "==> ENTORNO : PRODUCCIÓN  \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo "==> ENTORNO : VALIDACIÓN  \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

$PA = selectPaById($pa_id);
if ($PA== null ) {
    echo "****ERROR NO EXISTE PA ID=". $pa_id. "\n";
    echo "** TERMINA EN ERROR n";
    exit(1);
}

$PA["codigoLoc"] = substr($PA["oficial"], 4, 4);

echo "==> PUNTO ASISTENCIAL ID=" . $PA["id"]
 . " PA= " . $PA["pa"]
 . " OFICIAL= " . $PA["oficial"]
 . " CODIGO LOCAL= " . $PA["codigoLoc"]
 . " DESCRIPCION= " . $PA["descripcion"]
 . " EDIFICIO= " . $PA["edificio"]
 . " DA= " . $PA["da"]
 . " USO= " . $PA["enuso"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ($actuacion == 'INSERT') {
    if (crearCentroUnif($PA)) {
        if (crearCentroArea($PA)) {
            crearEqCentroInte($PA);
        }
    }
}

if ($actuacion == 'UPDATE') {
    updateCentroUnif($PA);
    updateCentroArea($PA);
}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN PUNTO ASISTENCIAL +++++++++++++ \n";
exit($gblError);
