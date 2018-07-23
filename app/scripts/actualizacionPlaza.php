<?php

include_once __DIR__ . '/funcionesDAO.php';

function selectCecoCias($conexion, $cias) {
    try {
        $sentencia = "select * from cecocias  "
                . "  where cias =  :cias";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return true;
        } else {
            echo " NO EXISTE RELACIÓN CECOCIAS PARA CIAS= " . $cias . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo " ERROR PDO EN BORRADO " . $ex->getMessage() . " \n";
        return false;
    }
}

function procesoCecoCias($conexion, $cias, $ceco) {
    if (selectCecoCias($conexion, $cias)) {
        updateCecoCias($conexion, $cias, $ceco);
    } else {
        insertCecoCias($conexion, $cias, $ceco);
    }
}

function insertCecoCias($conexion, $cias, $ceco) {
    $sentencia = "insert into cecocias ( "
            . "  cias "
            . " ,ceco "
            . " ) values ("
            . "  :cias "
            . " ,:ceco "
            . " )";
    try {
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $res = $query->execute($params);
        if ($res == 0) {
            echo " ERROR INSERCIÓN CECOCIAS cias = " . $cias . " ceco= " . $ceco . "\n";
            return false;
        } else {
            echo " INSERCIÓN CECOCIAS cias = " . $cias . " ceco= " . $ceco . "\n";
            return true;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR EN INSERCIÓN CECOCIAS CIAS=" . $cias . " CECO=" . $ceco . " " . $ex->getMessage() . " \n";
        return false;
    }

    return true;
}

function updateCecoCias($conexion, $cias, $ceco) {
    $sentencia = "update cecocias set "
            . " ceco  = :ceco "
            . " where cias =  :cias ";
    try {
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $res = $query->execute($params);
        if ($res == 0) {
            echo " ERROR UPDATE CECOCIAS cias = " . $cias . " ceco= " . $ceco . "\n";
            return false;
        } else {
            echo " UPDATE CECOCIAS cias = " . $cias . " ceco= " . $ceco . "\n";
            return true;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR EN UPDATE CECOCIAS CIAS=" . $cias . " CECO=" . $ceco . " " . $ex->getMessage() . " \n";
        return false;
    }
}

function procesoInsert($Plaza) {
    global $JanoUnif, $JanoControl, $tipo;
    if (!insertPlazaUnif($Plaza)) {
        return false;
    }

    if (!insertPlazaArea($Plaza)) {
        return false;
    }
}

function procesoUpdate($Plaza) {
    global $tipo;

    if (!updatePlazaUnif($Plaza)) {
        return false;
    }

    if (!updatePlazaArea($Plaza)) {
        return false;
    }

    return true;
}

function equivalenciasPlaza($Plaza) {
    $Equi["uf"] = selectEqCentro($Plaza["uf"], $Plaza["edificio"], "U");
    $Equi["p_asist"] = selectEqCentro($Plaza["pa"], $Plaza["edificio"], "P");
    $Equi["catgen"] = selectEqCatGen($Plaza["catgen"], $Plaza["edificio"]);
    $Equi["catfp"] = selectEqCatFp($Plaza["catfp"], $Plaza["edificio"]);
    $Equi["turno"] = selectEqTurno($Plaza["turno"], $Plaza["edificio"]);
    /*
      echo "**EQUIVALENCIAS**\n";
      echo "**-------------**\n";
      echo " uf = " . $Plaza["uf"] . "/" . $Equi["uf"] . "\n";
      echo " p_asist = " . $Plaza["pa"] . "/" . $Equi["p_asist"] . "\n";
      echo " catgen = " . $Plaza["catgen"] . "/" . $Equi["catgen"] . "\n";
      echo " catfp = " . $Plaza["catfp"] . "/" . $Equi["catfp"] . "\n";
     */
    return $Equi;
}

function insertPlazaUnif($Plaza) {
    global $JanoUnif;
    try {
        $sentencia = "insert into plazas ( cias, uf, modalidad, p_asist, catgen"
                . "  ,ficticia, refuerzo, catfp, cupequi, plantilla, f_amortiza, colaboradora, observaciones,turno"
                . "  ,fcreacion, hor_normal ) values ( "
                . "  :cias, :uf, :modalidad, :p_asist, :catgen"
                . "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno "
                . "  ,:f_creacion, :hor_normal )";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":cias" => $Plaza["cias"],
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
            ":hor_normal" => $Plaza["horNormal"]);

        $ins = $query->execute($params);
        if ($ins == 0) {
            echo " ERROR EN INSERCIÓN EN LA BASE DE DATOS UNIFICADA CIAS=" . $Plaza["cias"] . "\n";
            return false;
        } else {
            echo " PLAZA " . $Plaza["cias"] . " CREADA  EN LA BASE DE DATOS UNIFICADA \n";
        }
        if ($Plaza["ceco"] != null) {
            procesoCecoCias($JanoUnif, $Plaza["cias"], $Plaza["ceco"]);
        }
    } catch (PDOException $ex) {
        echo " PDOERROR EN INSERT BASE DE DATOS UNIFICADA " . $ex->getMessage() . " \n";
        return false;
    }

    return true;
}

function insertPlazaArea($Plaza) {
    global $tipo;
    $baseDatos = SelectBaseDatosEdificio($tipo, $Plaza["edificio"]);
    if ($baseDatos == null) {
        echo " ERROR EN NO EXISTE DEFINICIÓN PARA LA BASE DE DATOS EDIFICIO=" . $Plaza["edificio"] . " ENTORNO = " . $tipo . " \n";
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
        $sentencia = "insert into plazas ( cias, uf, modalidad, p_asist, catgen"
                . "  ,ficticia, refuerzo, catfp, cupequi, plantilla, f_amortiza, colaboradora, observaciones, turno"
                . "  ,fcreacion, hor_normal ) values ( "
                . "  :cias, :uf, :modalidad, :p_asist, :catgen"
                . "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno "
                . "  ,:f_creacion, :hor_normal )";

        $query = $conexionArea->prepare($sentencia);
        $Equi = equivalenciasPlaza($Plaza);
        $params = array(":cias" => $Plaza["cias"],
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
            ":hor_normal" => $Plaza["horNormal"]);
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo " ERROR EN LA Inserción CIAS=" . $Plaza["cias"] . "\n";
            return false;
        }
        if ($Plaza["ceco"] != null) {
            procesoCecoCias($conexionArea, $Plaza["cias"], $Plaza["ceco"]);
        }
        return true;
    } catch (PDOException $ex) {
        echo " PDOERROR EN INSERCION EN BASE DE DATOS AREA  " . $ex->getMessage() . " \n";
        return false;
    }
}

function updatePlazaUnif($Plaza) {
    global $JanoUnif;

    try {
        $sentencia = "update plazas set  "
                . "  uf = :uf"
                . " ,modalidad= :modalidad"
                . ", p_asist= :p_asist"
                . ", catgen= :catgen"
                . "  ,ficticia= :ficticia"
                . ", refuerzo= :refuerzo"
                . ", catfp= :catfp"
                . ", cupequi= :cupequi"
                . ", plantilla= :plantilla"
                . ", f_amortiza= :f_amortiza"
                . ", colaboradora= :colaboradora"
                . ", fcreacion= :f_creacion"
                . ", observaciones = :observaciones"
                . ", turno = :turno"
                . ", hor_normal = :hor_normal"
                . " where cias = :cias ";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":cias" => $Plaza["cias"],
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
            ":hor_normal" => $Plaza["horNormal"]);
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo " Error en actualización base de datos unificada cias= " . $Plaza["cias"] . "\n";
            return false;
        }
        echo " PLAZA " . $Plaza["cias"] . " MODIFICADA EN LA BASE DE DATOS UNIFICADA \n";
        if ($Plaza["ceco"] != null) {
            procesoCecoCias($JanoUnif, $Plaza["cias"], $Plaza["ceco"]);
        }
        return true;
    } catch (PDOException $ex) {
        echo " ERRORPDO EN  Actualicación " . $ex->getMessage() . " \n";
        return false;
    }
}

function updatePlazaArea($Plaza) {
    global $tipo;
    $baseDatos = SelectBaseDatosEdificio($tipo, $Plaza["edificio"]);
    if ($baseDatos == null) {
        echo " ERROR EN NO EXISTE DEFINICIÓN PARA LA BASE DE DATOS EDIFICIO=" . $Plaza["edificio"] . " ENTORNO =" . $tipo . " \n";
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
        $sentencia = "update plazas set  "
                . "  uf = :uf"
                . " ,modalidad= :modalidad"
                . ", p_asist= :p_asist"
                . ", catgen= :catgen"
                . "  ,ficticia= :ficticia"
                . ", refuerzo= :refuerzo"
                . ", catfp= :catfp"
                . ", cupequi= :cupequi"
                . ", plantilla= :plantilla"
                . ", f_amortiza= :f_amortiza"
                . ", colaboradora= :colaboradora"
                . ", fcreacion= :f_creacion"
                . ", observaciones = :observaciones"
                . ", turno = :turno"
                . ", hor_normal = :hor_normal"
                . " where cias = :cias ";
        $query = $conexionArea->prepare($sentencia);
        $Equi = equivalenciasPlaza($Plaza);

        $params = array(":cias" => $Plaza["cias"],
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
            ":hor_normal" => $Plaza["horNormal"]);

        $ins = $query->execute($params);
        if ($ins == 0) {
            echo " Error en actualización base de datos edificio= " . $Plaza["edificio"] . " cias= " . $Plaza["cias"] . "\n";
            return false;
        } else {
            echo " PLAZA " . $Plaza["cias"] . " MODIFICADA EN LA BASE DE DATOS AREA \n";
        }
        if ($Plaza["ceco"] != null) {
            procesoCecoCias($conexionArea, $Plaza["cias"], $Plaza["ceco"]);
        }

        return true;
    } catch (PDOException $ex) {
        echo " ERRORPDO EN  Actualicación " . $ex->getMessage() . " \n";
        return false;
    }
}

/*
 * definimos las conexiones a las bases de datos intermedia jano_inte, unificada unif_01 Y CONTROL DE LOG JANO_CTRL 
 * a nivel global para poder usarlas en todo el proceso
 */

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO ACTUALIZACIÓN PLAZA +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$id = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    echo " **** PRODUCCIÓN **** \n";
    $JanoInte = conexionPDO(selectBaseDatos( 2, 'I'));
    $JanoUnif = conexionPDO(selectBaseDatos( 2, 'U'));
} else {
    echo " ++++ VALIDACIÓN ++++ \n";
    $JanoInte = conexionPDO(selectBaseDatos( 1, 'I'));
    $JanoUnif = conexionPDO(selectBaseDatos( 1, 'U'));
}


echo " ** ACTUACIÓN = " . $actuacion . " **\n";
$Plaza = selectPlaza($id);

if ($Plaza == null) {
    echo " No existe plaza id=" . $id . "\n";
    echo " +++ TERMINA EN ERROR(1) +++ \n";
    exit(1);
}

echo " ==> PLAZA: ID = " . $Plaza["id"]
 . " CIAS = " . $Plaza["cias"]
 . " UF =" . $Plaza["uf"]
 . " modalidad =" . $Plaza["modalidad"]
 . " p_asist=" . $Plaza["pa"]
 . " catgen=" . $Plaza["catgen"]
 . " ficticia=" . $Plaza["ficticia"] . "\n"
 . " refuerzo=" . $Plaza["refuerzo"]
 . " catfp=" . $Plaza["catfp"]
 . " cupequi=" . $Plaza["cupequi"]
 . " plantilla=" . $Plaza["plantilla"]
 . " f_amortiza=" . $Plaza["f_amortiza"]
 . " colaboradora=" . $Plaza["colaboradora"]
 . " fcreacion=" . $Plaza["f_creacion"]
 . " edificion=" . $Plaza["edificio"]
 . " turno= " . $Plaza["turno"]
 . " ceco= " . $Plaza["ceco"] . "\n\n";

if ($actuacion == 'INSERT') {
    if (!procesoInsert($Plaza)) {
        echo "  +++++++++++ TERMINA PROCESO INSERT PLAZA EN ERROR +++++++++++++ \n";
        exit(1);
    }
}

if ($actuacion == 'UPDATE') {
    if (!procesoUpdate($Plaza)) {
        echo "  +++++++++++ TERMINA PROCESO UPDATE PLAZA EN ERROR +++++++++++++ \n";
        exit(1);
    }
}
echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN PLAZA +++++++++++++ \n";
exit(0);
