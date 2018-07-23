<?php

include_once __DIR__ . '/funcionesDAO.php';

function crearCentroUnif($PA) {
    global $JanoUnif;
    /*
     * Se genera el registro en la tabla centros de la base de datos unificada 
     */
    try {
        $query = " insert into centros (codigo, descrip, vista, enuso, f_creacion, oficial, da, gerencia ) "
                . " values (:codigo, :descrip, :vista, :enuso, :f_creacion, :oficial, :da, :gerencia )";
        $query = $JanoUnif->prepare($query);
        $paramsCentros = array(":codigo" => $PA["pa"],
            ":descrip" => $PA["descripcion"],
            ":vista" => 'P',
            ":enuso" => 'S',
            ":f_creacion" => $PA["fecha_creacion"],
            ":oficial" => $PA["oficial"],
            ":da" => $PA["da"],
            ":gerencia" => $PA["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo " ERROR EN INSERT CENTROS BD. UNIFICADA " . $PA["pa"] . "\n";
        } else {
            echo " GENERADO CENTRO =" .
            $PA["pa"] . " " .
            $PA["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return $codigoUnif;
    } catch (PDOException $ex) {
        echo " *** PDOERROR CENTROS BD UNIFICADA CODIGO= " . $PA["pa"] . "  \n" . $ex->getMessage() . "\n";
        return null;
    }
}

function crearEqCentroInte($PA) {
    global $JanoInte;
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
            ":vista" => 'P');
        $insert = $query->execute($params);
        if (!$insert) {
            echo " ERROR EN INSERT EQ_CENTROS BD. INTERMEDIA " . $PA["pa"] . "\n";
        } else {
            echo " GENERADO EQ_CENTRO EDIF=" . $PA["edificio"]
            . " CODIGO_LOC= " . $PA["codigoLoc"]
            . " CODIGO_UNI= " . $PA["pa"]
            . " OFICIAL= " . $PA["oficial"]
            . " DA= " . $PA["da"] . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR EN EQ_CENTROS BD INTERMEDIA " . $PA["pa"] . $ex->getMessage() . "\n";
        return false;
    }
}

function crearCentroArea($PA) {
    global $tipoBd;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */

    $baseDatos = SelectBaseDatosEdificio($tipoBd, $PA["edificio"]);
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
        $paramsCentros = array(":codigo" => $PA["codigoLoc"],
            ":descrip" => $PA["descripcion"],
            ":vista" => 'P',
            ":enuso" => 'S',
            ":f_creacion" => $PA["fechaCreacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo " error en insert CENTROS BD. AREA " . $PA["edificio"] . " " . $PA["codigoLoc"] . "\n";
        } else {
            echo " GENERADO CENTRO =" .
            $PA["codigoLoc"] . " " .
            $PA["descripcion"] .
            " EN LA BASE DE DATOS AREA " . $PA["edificio"] . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $PA["edificio"] . " " . $PA["pa"] . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoInsert($PA) {

    
    if (!$crearCentroUnif($PA)) {
        exit(1);
    }
    
    if (!crearEqCentroInte($PA)) {
        exit(1);
    }

    if (!crearCentroArea($PA)) {
        exit(1);
    }
}

function updateCentroUnif($PA) {
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
        $paramsCentros = array(":codigo" => $PA["pa"],
            ":descrip" => $PA["descripcion"],
            ":enuso" => $PA["en_uso"],
            ":f_creacion" => $PA["fechaCreacion"],
            ":oficial" => $PA["oficial"],
            ":da" => $PA["da"],
            ":gerencia" => $PA["edificio"]);
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo " ERROR EN UPDATE CENTROS BD. UNIFICADA " . $PA["pa"] . "\n";
        } else {
            echo " MODIFICADO CENTRO =" .
            $PA["pa"] . " " .
            $PA["descripcion"] .
            " EN LA BASE DE DATOS UNIFICADA " . "\n";
        }
        return $PA["pa"];
    } catch (PDOException $ex) {
        echo " *** PDOERROR UPDATE CENTROS BD UNIFICADA CODIGO= " . $PA["pa"] . "  \n" . $ex->getMessage() . "\n";
        return null;
    }
}

function updateCentroArea($PA) {
    global $tipoBd;
    /*
     * Se genera el registro en la tabla centros de la base de datos del edificio correspondiente  
     */
    $codigoLoc = selectEqCentro($PA["pa"], $PA["edificio"], 'P');
    $baseDatos = SelectBaseDatosEdificio($tipoBd, $PA["edificio"]);

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
        $paramsCentros = array(":codigo" => $PA["codigoLoc"],
            ":descrip" => $PA["descripcion"],
            ":enuso" => $PA["en_uso"],
            ":f_creacion" => $PA["fecha_creacion"]
        );
        $insert = $query->execute($paramsCentros);
        if ($insert == 0) {
            echo " error en UPDATE CENTROS BD. AREA " . $PA["edificio"] . " " . $PA["codigoLoc"] . "\n";
        } else {
            echo " MODIFICADO CENTRO =" .
            $PA["codigoLoc"]  . " " .
            $PA["descripcion"] .
            " EN LA BASE DE DATOS AREA " . $PA["edificio"] . "\n";
        }
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR CENTROS BD AREA " . $PA["edificio"] . " " . $PA["codigoLoc"]. " "  . $ex->getMessage() . "\n";
        return false;
    }
}

function procesoUpdate($PA) {
    if (!updateCentroUnif($PA)) {
        return null;
    }

    if (!updateCentroArea($PA)) {
        return null;
    }

    return true;
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO REPLICA DE PUNTO ASISTENCIAL (PA) +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$pa = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    echo " ENTORNO : PRODUCCIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos( 2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 2, 'U'));
    $tipoBd=2;
    
} else {
    echo "  ENTORNO : VALIDACIÓN  \n";
    $JanoInte = conexionPDO(SelectBaseDatos( 1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 1, 'U'));
    $tipoBd=1;
    
}

$PA = SelectPa($pa);
$PA["codigoLoc"]= substr($PA["oficial"],4,4);

echo "==> PUNTO ASISTENCIAL ID=" . $PA["id"]
 . " PA= " . $PA["pa"]
 . " CODIGO LOCAL = " .$PA["codigoLoc"]       
 . " OFICIAL= " . $PA["oficial"]
 . " DESCRIPCION= " . $PA["descripcion"]
 . " EDIFICIO= " . $PA["edificio"]
 . " DA= " . $PA["da"]
 . " USO= " . $PA["en_uso"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ($actuacion == 'INSERT') {
    if (!procesoInsert($PA)) {
        exit(1);
    }
}

if ($actuacion == 'UPDATE') {
    if (!procesoUpdate($PA)) {
        exit(1);
    }
}

echo "  +++++++++++ TERMINA PROCESO REPLICA PUNTO ASISTENCIAL +++++++++++++ \n";
exit(0);
