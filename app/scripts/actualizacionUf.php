<?php

include_once __DIR__ . '/funcionesDAO.php';

function crearCentroUnif($UF) {
    global $JanoUnif;
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
            echo " ERROR EN INSERT CENTROS BD. UNIFICADA " . $UF["uf"] . "\n";
        } else {
            echo " GENERADO CENTRO =" .
            $UF["uf"] . " " .
            $UF["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo " *** PDOERROR CENTROS BD UNIFICADA CODIGO= " . $UF["uf"] . "  \n" . $ex->getMessage() . "\n";
        return null;
    }
}

function crearEqCentroInte($UF) {
    global $JanoInte;
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
            echo " ERROR EN INSERT EQ_CENTROS BD. INTERMEDIA " . $UF["codigoUnif"] . "\n";
        } else {
            echo " GENERADO EQ_CENTRO EDIF=" . $UF["edificio"]
            . " CODIGO_LOC= " . $UF["codigoLoc"]
            . " CODIGO_UNI= " . $UF["uf"]
            . " OFICIAL= " . $UF["oficial"]
            . " DA= " . $UF["da"] . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR EN EQ_CENTROS BD INTERMEDIA " . $UF["codigoUnif"] . $ex->getMessage() . "\n";
        return false;
    }
}

function crearCentroArea($UF) {
    global $tipoBd;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */

    $baseDatos = SelectBaseDatosEdificio($tipoBd, $UF["edificio"]);
    $alias = $baseDatos["alias"];
    $datosConexion["maquina"] = $baseDatos["maquina"];
    $datosConexion["puerto"] = $baseDatos["puerto"];
    $datosConexion["servidor"] = $baseDatos["servidor"];
    $datosConexion["esquema"] = $baseDatos["esquema"];
    $datosConexion["usuario"] = $baseDatos["usuario"];
    $datosConexion["password"] = $baseDatos["password"];
    $conexion = conexionPDO($datosConexion);

    echo " ==>Proceso para : " . $alias . "\n";
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
            echo " error en insert CENTROS BD. AREA " . $UF["edificio"] . " " . $UF["uf"] . "\n";
        } else {
            echo " GENERADO CENTRO =" .
            $UF["codigoLoc"] . " " .
            $UF["descripcion"] .
            " EN LA BASE DE DATOS AREA " . $UF["edificio"] . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $UF["edificio"] . " " . $UF["uf"] . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoInsert($UF) {

    if (!crearCentroUnif($UF)) {
        exit(1);
    }

    if (!crearEqCentroInte($UF)) {
        exit(1);
    }

    if (!crearCentroArea($UF)) {
        exit(1);
    }
}

function updateCentroUnif($UF) {
    global $JanoUnif;
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
            ":enuso" => $UF["en_uso"],
            ":f_creacion" => $UF["fechaCreacion"],
            ":oficial" => $UF["oficial"],
            ":da" => $UF["da"],
            ":gerencia" => $UF["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo " ERROR EN UPDATE CENTROS BD. UNIFICADA " . $UF["uf"] . "\n";
        } else {
            echo " MODIFICADO CENTRO =" .
            $UF["uf"] . " " .
            $UF["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return $UF["uf"];
    } catch (PDOException $ex) {
        echo " *** PDOERROR UPDATE CENTROS BD UNIFICADA CODIGO= " . $UF["uf"] . "  \n" . $ex->getMessage() . "\n";
        return null;
    }
}

function updateCentroArea($UF) {
    global $tipoBd;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $codigoLoc = selectEqCentro($UF["uf"], $UF["edificio"], 'U');
    $baseDatos = SelectBaseDatosEdificio($tipoBd, $UF["edificio"]);

    $alias = $baseDatos["alias"];
    $datosConexion["maquina"] = $baseDatos["maquina"];
    $datosConexion["puerto"] = $baseDatos["puerto"];
    $datosConexion["servidor"] = $baseDatos["servidor"];
    $datosConexion["esquema"] = $baseDatos["esquema"];
    $datosConexion["usuario"] = $baseDatos["usuario"];
    $datosConexion["password"] = $baseDatos["password"];
    $conexion = conexionPDO($datosConexion);
    if ($conexion == null) {
        echo " ERROR EN LA CONEXIÓN \n";
        return null;
    }

    echo " ==>Proceso para : " . $alias . "\n";
    try {
        $query = " update centros set "
                . " descrip = :descrip,"
                . " enuso = :enuso,"
                . " f_creacion = :f_creacion"
                . " where codigo = :codigo ";
        $query = $conexion->prepare($query);
        $paramsCentros = array(":codigo" => $codigoLoc,
            ":descrip" => $UF["descripcion"],
            ":enuso" => $UF["en_uso"],
            ":f_creacion" => $UF["fechaCreacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo " error en UPDATE CENTROS BD. AREA " . $UF["edificio"] . " " . $codigoLoc . "\n";
        } else {
            echo " MODIFICADO CENTRO =" .
            $codigoLoc . " " .
            $UF["descripcion"] .
            " EN LA BASE DE DATOS AREA " . $UF["edificio"] . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $UF["edificio"] . " " . $codigoLoc . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoUpdate($UF) {
    if (!updateCentroUnif($UF)) {
        return null;
    }

    if (!updateCentroArea($UF)) {
        return null;
    }

    return true;
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO REPLICA DE UNIDAD FUNCIONAL (UF) +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$uf = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipoBd= 2;
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipoBd= 1;
    echo " ++++ VALIDACIÓN ++++ \n";
}

$UF = SelectUf($uf);
$UF["codigoLoc"] = substr($UF["oficial"], 4, 6);

echo "==> UNIDAD FUNCIONAL ID=" . $UF["id"]
 . " UF= " . $UF["uf"]
 . " OFICIAL= " . $UF["oficial"]
 . " CODIGO LOCAL= " . $UF["codigoLoc"]
 . " DESCRIPCION= " . $UF["descripcion"]
 . " EDIFICIO= " . $UF["edificio"]
 . " DA= " . $UF["da"]
 . " USO= " . $UF["en_uso"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ($actuacion == 'INSERT') {
    if (!procesoInsert($UF)) {
        exit(1);
    }
}

if ($actuacion == 'UPDATE') {
    if (!procesoUpdate($UF)) {
        exit(1);
    }
}

echo "  +++++++++++ TERMINA PROCESO REPLICA UNIDAD FUNCIONAL +++++++++++++ \n";
exit(0);
