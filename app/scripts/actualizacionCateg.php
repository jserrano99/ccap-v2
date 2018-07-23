<?php

include_once __DIR__ . '/funcionesDAO.php';

function procesoUpdate($CATEG) {
    global $JanoUnif, $JanoControl, $tipo;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!updateCategUnif($CATEG)) {
        echo " ERROR EN LA ACTUALIZACIÓN EN LA BASE DE DATOS UNIFICADA \n";
        return false;
    }
    /*
     * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas 
     */
    $BasesDatos = SelectBaseDatosAreas($JanoControl, $tipo);
    //var_dump($BasesDatos);
    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        echo " ==> Proceso para : " . $alias . "\n";
        $codigo = selectEqCateg($CATEG["codigo"],$baseDatos["edificio"]);
        echo " Equivalencia Código \n";
        echo " codigo = ".$CATEG["codigo"]."/".$codigo."\n";
        if ($codigo ) {
            updateCategAreas($CATEG, $conexion, $codigo,$baseDatos["edificio"]);
        }
    }
    return true;
}

function updateCategUnif($CATEG) {
    global $JanoUnif;
    try {
        $sentencia = " update categ set"
                ." catgen = :catgen "
                ." ,descrip = :descrip"
                ." ,fsn = :fsn "
                ." ,catanexo = :catanexo"
                ." ,grupcot = :grupocot"
                ." ,epiacc = :epiacc" 
                ." ,grupoprof = :grupoprof"
                ." ,enuso = :enuso"
                ." ,grupocobro = :grupocobro "
                ." ,ocupacion = :ocupacion"
                ." ,mir = :mir "
                ." ,condicionado = :condicionado"
                ." ,directivo = :directivo"
                . " where codigo = :codigo ";
        $update = $JanoUnif->prepare($sentencia);
        
        $params = array(":codigo" => $CATEG["codigo"],
            ":catgen" => $CATEG["catgen"],
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $CATEG["catanexo"],
            ":grupocot" => $CATEG["grupocot"] ,
            ":epiacc" => $CATEG["epiacc"],
            ":grupoprof" => $CATEG["grupoprof"],
            ":enuso" => $CATEG["enuso"],
            ":grupocobro" => $CATEG["grupocobro"],
            ":ocupacion" => $CATEG["ocupacion"],
            ":mir" => $CATEG["mir"],
            ":condicionado" => $CATEG["condicionado"],
            ":directivo" => $CATEG["directivo"]);
        $res = $update->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " MODIFICADA \n";
            return true;
        } else {
            echo "  ERROR EN UPDATE CATEGORIA " . $CATEG["codigo"]. " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN UPDATE CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}

function updateCategAreas($CATEG, $conexion,$codigo, $edificio) {
    try {
        $sentencia = " update categ set "
                ."  catgen = :catgen "
                ." ,descrip = :descrip"
                ." ,fsn = :fsn "
                ." ,catanexo = :catanexo"
                ." ,grupcot = :grupocot"
                ." ,epiacc = :epiacc" 
                ." ,grupoprof = :grupoprof"
                ." ,enuso = :enuso"
                ." ,grupocobro = :grupocobro "
                ." ,ocupacion = :ocupacion "
                ." ,mir = :mir "
                ." ,condicionado = :condicionado"
                ." ,directivo = :directivo"
                . " where codigo = :codigo ";
        $update = $conexion->prepare($sentencia);
        $catgen = selectEqCatgen($CATEG["catgen"],$edificio);
        $catanexo = selectEqCatAnexo($CATEG["catanexo"],$edificio);
        $grupocot = selectEqGrupoCot($CATEG["grupocot"],$edificio);
        //$epiacc = selectEqEpiAcc($CATEG["epiacc"],$edificio);
        $grupoprof = selectEqGrupoProf($CATEG["grupoprof"],$edificio);
        $grupocobro = selectEqGrupoCobro($CATEG["grupocobro"],$edificio);
        $ocupacion = selectEqOcupacion($CATEG["ocupacion"],$edificio);
        echo "**EQUIVALENCIAS**\n";
        echo "**-------------**\n";
        echo " catgen = ".$CATEG["catgen"]."/".$catgen."\n";
        echo " catanexo = ".$CATEG["catanexo"]."/".$catanexo."\n";
        echo " grupocot = ".$CATEG["grupocot"]."/".$grupocot."\n";
        echo " grupoprof = ".$CATEG["grupoprof"]."/".$grupoprof."\n";
        echo " grupocobro = ".$CATEG["grupocobro"]."/".$grupocobro."\n";
        echo " ocupacion = ".$CATEG["ocupacion"]."/".$ocupacion."\n";
     
        $params = array(":codigo" => $codigo,
            ":catgen" => $catgen,
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $catanexo,
            ":grupocot" => $grupocot ,
            ":epiacc" => $CATEG["epiacc"],
            ":grupoprof" => $grupoprof,
            ":enuso" => $CATEG["enuso"],
            ":grupocobro" => $grupocobro,
            ":ocupacion" => $ocupacion,
            ":mir" => $CATEG["mir"],
            ":condicionado" => $CATEG["condicionado"],
            ":directivo" => $CATEG["directivo"]);
        
        $res = $update->execute($params);
        if ($res) {
            echo " CATEGORIA " . $codigo . " " . $CATEG["descripcion"] . " MODIFICADA EN LA B.D. \n";
            return true;
        } else {
            echo "  ERROR EN UPDATE CATEGORIA " . $codigo. " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN UPDATE CATEGORIA " . $codigo . " " . $CATEG["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}


function procesoInsert($CATEG) {
    global $JanoUnif, $JanoControl, $tipo;
    /*
     * Insert en la tabla categ de la base de datos unificada
     */
    if (!insertCategUnif($CATEG)) {
        echo " ERROR EN LA Inserción EN LA BASE DE DATOS UNIFICADA \n";
        return false;
    }
    /*
     * Insert en la tabla eq_categ de la base de datos intermedia para cada uno de las areas 
     */

    for ($i = 0; $i < 11; $i++) {
        if (!insertEqCateg($CATEG, $i)) {
            return false;
        }
    }

    /*
     * Insert en la tabla categ de en cada una de las areas 
     */
    $BasesDatos = SelectBaseDatosAreas($JanoControl, $tipo);
    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        echo " ==> Proceso para : " . $alias . "\n";
        insertCategAreas($CATEG, $conexion,$baseDatos["edificio"]);
    }
    
    return true;
}

function insertCategAreas($CATEG, $conexion,$edificio) {
    try {
        $sentencia = " insert into categ "
                . " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
                . " ,ocupacion, mir, condicionado, directivo ) values "
                . " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :grupcot, :epiacc, :grupoprof, :enuso, :grupocobro "
                . " ,:ocupacion, :mir, :condicionado, :directivo )";
        $insert = $conexion->prepare($sentencia);
        $catgen = selectEqCatGen($CATEG["catgen"],$edificio);
        $catanexo = selectEqCatAnexo($CATEG["catanexo"],$edificio);
        $grupocot = selectEqGrupoCot($CATEG["grupocot"],$edificio);
        //$epiacc = selectEqEpiAcc($CATEG["epiacc"],$edificio);
        $grupoprof = selectEqGrupoProf($CATEG["grupoprof"],$edificio);
        $grupocobro = selectEqGrupoCobro($CATEG["grupocobro"],$edificio);
        $ocupacion = selectEqOcupacion($CATEG["ocupacion"],$edificio);
        echo "**EQUIVALENCIAS**\n";
        echo "**-------------**\n";
        echo " catgen = ".$CATEG["catgen"]."/".$catgen."\n";
        echo " catanexo = ".$CATEG["catanexo"]."/".$catanexo."\n";
        echo " grupocot = ".$CATEG["grupocot"]."/".$grupocot."\n";
        echo " grupoprof = ".$CATEG["grupoprof"]."/".$grupoprof."\n";
        echo " grupocobro = ".$CATEG["grupocobro"]."/".$grupocobro."\n";
        echo " ocupacion = ".$CATEG["ocupacion"]."/".$ocupacion."\n";
                
        $params = array(":codigo" => $CATEG["codigo"],
            ":catgen" => $catgen,
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $catanexo,
            ":grupcot" => $grupocot ,
            ":epiacc" => $CATEG["epiacc"],
            ":grupoprof" => $grupoprof,
            ":enuso" => $CATEG["enuso"],
            ":grupocobro" => $grupocobro,
            ":ocupacion" => $ocupacion,
            ":mir" => $CATEG["mir"],
            ":condicionado" => $CATEG["condicionado"],
            ":directivo" => $CATEG["directivo"]);
        
        $res = $insert->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " CREADA EN LA B.D. \n";
            return true;
        } else {
            echo "  ERROR EN INSERT CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN INSERT CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}

function insertCategUnif($CATEG) {
    global $JanoUnif;
    try {
        $sentencia = " insert into categ "
                . " ( codigo, catgen, descrip, fsn, catanexo, grupcot, epiacc, grupoprof, enuso, grupocobro "
                . " ,ocupacion, mir, condicionado, directivo ) values "
                . " ( :codigo, :catgen, :descrip, :fsn, :catanexo, :grupcot, :epiacc, :grupoprof, :enuso, :grupocobro "
                . " ,:ocupacion, :mir, :condicionado, :directivo )";
        $insert = $JanoUnif->prepare($sentencia);
        
        $params = array(":codigo" => $CATEG["codigo"],
            ":catgen" => $CATEG["catgen"],
            ":descrip" => $CATEG["descripcion"],
            ":fsn" => $CATEG["fsn"],
            ":catanexo" => $CATEG["catanexo"],
            ":grupcot" => $CATEG["grupocot"] ,
            ":epiacc" => $CATEG["epiacc"],
            ":grupoprof" => $CATEG["grupoprof"],
            ":enuso" => $CATEG["enuso"],
            ":grupocobro" => $CATEG["grupocobro"],
            ":ocupacion" => $CATEG["ocupacion"],
            ":mir" => $CATEG["mir"],
            ":condicionado" => $CATEG["condicionado"],
            ":directivo" => $CATEG["directivo"]);
        
        $res = $insert->execute($params);
        if ($res) {
            echo " CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descripcion"] . " CREADA EN LA B.D. \n";
            return true;
        } else {
            echo "  ERROR EN INSERT CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN INSERT CATEGORIA " . $CATEG["codigo"] . " " . $CATEG["descrip"]
        . " B.D.  " . $ex->getMessage() . "\n";
        return false;
    }
}

function insertEqCateg($CATEG, $area) {
    global $JanoInte;
    try {
        $sentencia = " insert into eq_categ "
                . " ( edificio, codigo_loc, codigo_uni ) "
                . " values "
                . " ( :edificio, :codigo_loc, :codigo_uni ) "
        ;
        $insert = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $area,
            ":codigo_loc" => $CATEG["codigo"],
            ":codigo_uni" => $CATEG["codigo"]);
        $res = $insert->execute($params);
        if ($res) {
            echo " EQUIVALENCIA GENERADA EDIFICIO= " . $area
            . " CODIGO_LOC = " . $CATEG["codigo"]
            . " CODIGO_UNI = " . $CATEG["codigo"] . "\n";
            return true;
        } else {
            echo "  ERROR EN INSERT EQ_CATEG " . $CATEG["codigo"] . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "  PDOERROR EN INSERT EQ_CATEG " . $CATEG["codigo"] . $ex->getMessage() . "\n";
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

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos( 2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos( 1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos( 1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

$CATEG = selectCateg($categ_id);

echo "==> CATEGORIA PROFESIONAL : ID=" . $CATEG["id"]
 . " CODIGO= " . $CATEG["codigo"]
 . " DESCRIPCION= " . $CATEG["descripcion"]
 . " CATGEN= " . $CATEG["catgen"]
 . " CATANEXO= " . $CATEG["catanexo"]
 . " GRUPOCOT= " . $CATEG["grupocot"]
 . " GRUPOPROF= " . $CATEG["grupoprof"]
 . " GRUPOCOBRO= " . $CATEG["grupocobro"]
 . " OCUPACION= " . $CATEG["ocupacion"]
 . " EPIACC= " . $CATEG["epiacc"]
 . " \n";

echo "==> ACTUACION : " . $actuacion . "\n";

if ( $actuacion == 'INSERT') {
    if ( !procesoInsert($CATEG) ) {
       echo "  +++++++++++ TERMINA PROCESO INSERT EN ERROR +++++++++++++ \n";
       exit(1);
    }
}

if ( $actuacion == 'UPDATE') {
    if ( !procesoUpdate($CATEG) ) {
       echo "  +++++++++++ TERMINA PROCESO UPDATE EN ERROR +++++++++++++ \n";
       exit(1);
    }
}

echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit(0);
