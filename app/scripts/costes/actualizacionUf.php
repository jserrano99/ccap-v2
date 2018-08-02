<?php

include_once __DIR__ . '/../funcionesDAO.php';

function crearCentroUnif($UF) {
    global $JanoUnif, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos unificada 
     */
    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion, oficial, da, gerencia ) "
                . " values (:codigo, :descrip, :vista, :enuso, :f_creacion, :oficial, :da, :gerencia )";
        $query = $JanoUnif->prepare($query);
        $paramsCentros = array(":codigo" => $UF["uf"],
            ":descrip" => $UF["descripcion"],
            ":vista" => 'U',
            ":enuso" => 'S',
            ":f_creacion" => $UF["fecha_creacion"],
            ":oficial" => $UF["oficial"],
            ":da" => $UF["da"],
            ":gerencia" => $UF["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "**ERROR EN INSERT CENTROS BD. UNIFICADA " . $UF["uf"] . "\n";
            $gblError = 1;
        } else {
            echo "==>GENERADO CENTRO =" .
            $UF["uf"] . " " .
            $UF["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo " *** PDOERROR CENTROS BD UNIFICADA CODIGO= " . $UF["uf"] . "  \n" . $ex->getMessage() . "\n";
        $gblError01;
        return null;
    }
}

function crearEqCentroInte($UF) {
    global $JanoInte, $gblError;
    /*
     * Se genera el registro en la tabla de equivalencias de centros de la base de datos intermedia 
     */
    try {
        $query = " insert into eq_centros (edificio, codigo_loc, codigo_uni, oficial, da, vista) "
                . " values (:edificio, :codigo_loc, :codigo_uni, :oficial, :da, :vista)";
        $query = $JanoInte->prepare($query);
        $params = array(":edificio" => $UF["edificio"],
            ":codigo_loc" => $UF["codigoLoc"],
            ":codigo_uni" => $UF["uf"],
            ":oficial" => $UF["oficial"],
            ":da" => $UF["da"],
            ":vista" => 'U');
        $insert = $query->execute($params);
        if (!$insert) {
            echo "***ERROR EN INSERT EQ_CENTROS BD. INTERMEDIA " . $UF["codigoUnif"] . "\n";
            $gblError = 1;
        }
        echo " GENERADO EQ_CENTRO EDIF=" . $UF["edificio"]
        . " CODIGO_LOC= " . $UF["codigoLoc"]
        . " CODIGO_UNI= " . $UF["uf"]
        . " OFICIAL= " . $UF["oficial"]
        . " DA= " . $UF["da"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR EN EQ_CENTROS BD INTERMEDIA " . $UF["codigoUnif"] . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

function crearCentroArea($UF) {
    global $tipobd, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $conexion = conexionEdificio($UF["edificio"], $tipobd);
    if ($conexion == null) {
        echo "*** ERROR NO EXISTE CONEXION PARA EDIFICIO: " . $UF["edificio"] . " TIPO BD=" . $tipobd;
        $gblError = 1;
        return null;
    }

    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion ) "
                . " values (:codigo,:descrip, :vista, :enuso, :f_creacion )";
        $query = $conexion->prepare($query);
        $paramsCentros = array(":codigo" => $UF["codigoLoc"],
            ":descrip" => $UF["descripcion"],
            ":vista" => 'U',
            ":enuso" => 'S',
            ":f_creacion" => $UF["fecha_creacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "***ERROR INSERT CENTROS BD. AREA " . $UF["edificio"] . " " . $UF["uf"] . "\n";
            $gblError = 1;
        }
        echo "==>GENERADO CENTRO =" .
        $UF["codigoLoc"] . " " .
        $UF["descripcion"] .
        " EN LA BASE DE DATOS AREA " . $UF["edificio"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR CENTROS BD AREA " . $UF["edificio"] . " " . $UF["uf"] . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

function updateCentroUnif($UF) {
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
        $paramsCentros = array(":codigo" => $UF["uf"],
            ":descrip" => $UF["descripcion"],
            ":enuso" => $UF["enuso"],
            ":f_creacion" => $UF["fechaCreacion"],
            ":oficial" => $UF["oficial"],
            ":da" => $UF["da"],
            ":gerencia" => $UF["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "**ERROR EN UPDATE CENTROS BD. UNIFICADA " . $UF["uf"] . "\n";
            $gblError = 1;
            return null;
        }
        echo " MODIFICADO CENTRO =" .
        $UF["uf"] . " " .
        $UF["descripcion"] .
        " EN LA BASE DE DATOS UNIFICADA " . "\n";

        return $UF["uf"];
    } catch (PDOException $ex) {
        echo " *** PDOERROR UPDATE CENTROS BD UNIFICADA CODIGO= " . $UF["uf"] . "  \n" . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function updateCentroArea($UF) {
    global $tipobd, $gblError;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $codigoLoc = selectEqCentro($UF["uf"], $UF["edificio"], 'U');
    $conexion = conexionEdificio($UF["edificio"], $tipobd);

    if ($conexion == null) {
        echo "***ERROR EN LA CONEXIÓN EDIFICIO= " . $UF["edificio"] . " TIPO BD=" . $tipobd . "\n";
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
            ":descrip" => $UF["descripcion"],
            ":enuso" => $UF["enuso"],
            ":f_creacion" => $UF["fechaCreacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo "***ERROR  UPDATE CENTROS BD. AREA " . $UF["edificio"] . " " . $codigoLoc . "\n";
            $gblError = 1;
            return null;
        }
        echo " MODIFICADO CENTRO =" .
        $codigoLoc . " " .
        $UF["descripcion"] .
        " EN LA BASE DE DATOS AREA " . $UF["edificio"] . "\n";

        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $UF["edificio"] . " " . $codigoLoc . $ex->getMessage() . "\n";
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

$UF = selectUfById($uf_id);
if ($UF== null ) {
    echo "****ERROR NO EXISTE UF ID=". $uf_id. "\n";
    echo "** TERMINA EN ERROR n";
    exit(1);
}

$UF["codigoLoc"] = substr($UF["oficial"], 4, 6);

echo "==> UNIDAD FUNCIONAL ID=" . $UF["id"]
 . " UF= " . $UF["uf"]
 . " OFICIAL= " . $UF["oficial"]
 . " CODIGO LOCAL= " . $UF["codigoLoc"]
 . " DESCRIPCION= " . $UF["descripcion"]
 . " EDIFICIO= " . $UF["edificio"]
 . " DA= " . $UF["da"]
 . " USO= " . $UF["enuso"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ($actuacion == 'INSERT') {
    if (crearCentroUnif($UF)) {
        if (crearCentroArea($UF)) {
            crearEqCentroInte($UF);
        }
    }
}

if ($actuacion == 'UPDATE') {
    updateCentroUnif($UF);
    updateCentroArea($UF);
}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN UNIDAD FUNCIONAL +++++++++++++ \n";
exit($gblError);
