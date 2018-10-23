<?php

include_once __DIR__ . '/../funcionesDAO.php';

function selectCecoCias($conexion, $cias) {
    try {
        $sentencia = "select * from cecocias where cias =  :cias";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return true;
        } else {
//            echo " NO EXISTE RELACIÓN CECOCIAS PARA CIAS= " . $cias . "\n";
            return false;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR SELECT CECOCIAS CIAS= (" . $cias . ") ERROR= " . $ex->getMessage() . " \n";
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
    global $gblError;
    try {
        $sentencia = "insert into cecocias ( "
                . "  cias "
                . " ,ceco "
                . " ) values ("
                . "  :cias "
                . " ,:ceco "
                . " )";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR INSERCIÓN CECOCIAS CIAS= (" . $cias . ") CECO= (" . $ceco . ") \n";
            $gblError = 1;
            return false;
        } else {
            echo "==>INSERCIÓN CECOCIAS CIAS= (" . $cias . ") CECO= (" . $ceco . ") \n";
            return true;
        }
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERCIÓN CECOCIAS CIAS=" . $cias . " CECO=" . $ceco . " " . $ex->getMessage() . " \n";
        $gblError = 1;
        return false;
    }
}

function updateCecoCias($conexion, $cias, $ceco) {
    global $gblError;
    try {
        $sentencia = "update cecocias set "
                . " ceco  = :ceco "
                . " where cias =  :cias ";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR UPDATE CECOCIAS CIAS= " . $cias . " CECO= " . $ceco . "\n";
            $gblError = 1;
            return false;
        }
        echo "==>UPDATE CECOCIAS CIAS= (" . $cias . ") CECO= (" . $ceco . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE CECOCIAS CIAS=" . $cias . " CECO=" . $ceco . " " . $ex->getMessage() . " \n";
        $gblError = 1;
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
    $Equi["catgen"] = selectEqCatGen($Plaza["catgen_id"], $Plaza["edificio_id"]);
    $Equi["catfp"] = selectEqCatFp($Plaza["catfp_id"], $Plaza["edificio_id"]);
    //$Equi["turno"] = selectEqTurno($Plaza["turno"], $Plaza["edificio"]);
    $Equi["turno"] = $Plaza["turno"];

    echo "**EQUIVALENCIAS**\n";
    echo "**-------------**\n";
    echo " UNIDAD FUNCIONAL= (" . $Plaza["uf"] . ") / (" . $Equi["uf"] . ")\n";
    echo " PUNTO ASISTENCIAL= (" . $Plaza["pa"] . ") / (" . $Equi["p_asist"] . ")\n";
    echo " CATEGORIA GENERAL= (" . $Plaza["catgen"] . ") / (" . $Equi["catgen"] . ")\n";
    echo " CATEGORIA FP= (" . $Plaza["catfp"] . ") / (" . $Equi["catfp"] . ")\n";

    return $Equi;
}

function insertPlazaUnif($Plaza) {
    global $JanoUnif;
    try {
        $sentencia = " delete from plazas where cias = :cias";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":cias" => $Plaza["cias"]);
        $query->execute($params);

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
            echo "**ERROR EN INSERCIÓN EN LA BASE DE DATOS UNIFICADA CIAS=" . $Plaza["cias"] . "\n";
            $gblError = 1;
            return false;
        }

        echo "==> PLAZA " . $Plaza["cias"] . " CREADA EN LA BASE DE DATOS UNIFICADA \n";

        if ($Plaza["ceco"] != null) {
            procesoCecoCias($JanoUnif, $Plaza["cias"], $Plaza["ceco"]);
        }
    } catch (PDOException $ex) {
        echo "****PDOERROR EN INSERT BASE DE DATOS UNIFICADA " . $ex->getMessage() . " \n";
        $gblError = 1;
        return false;
    }

    return true;
}

function insertPlazaArea($Plaza) {
    global $tipobd, $gblError;
    $baseDatos = SelectBaseDatosEdificio($tipobd, $Plaza["edificio"]);
    if ($baseDatos == null) {
        echo "***ERROR EN NO EXISTE DEFINICIÓN PARA LA BASE DE DATOS EDIFICIO=" . $Plaza["edificio"] . " ENTORNO = " . $tipo . " \n";
        $gblError = 1;
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
        $sentencia = " delete from plazas where cias = :cias";
        $query = $conexionArea->prepare($sentencia);
        $params = array(":cias" => $Plaza["cias"]);
        $query->execute($params);

        $sentencia = "insert into plazas ( cias, uf, modalidad, p_asist, catgen"
                . "  ,ficticia, refuerzo, catfp, cupequi, plantilla, f_amortiza, colaboradora, observaciones, turno"
                . "  ,fcreacion, hor_normal, tarj1, tarj2, tarj3, tarj4, tarj5 ) values ( "
                . "  :cias, :uf, :modalidad, :p_asist, :catgen"
                . "  ,:ficticia, :refuerzo, :catfp, :cupequi, :plantilla, :f_amortiza, :colaboradora,:observaciones,:turno "
                . "  ,:f_creacion, :hor_normal, :tarj1, :tarj2, :tarj3, :tarj4, :tarj5 )";

        $query = $conexionArea->prepare($sentencia);
//        var_dump($query);
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
            ":hor_normal" => $Plaza["horNormal"],
            ":tarj1" => 0,
            ":tarj2" => 0,
            ":tarj3" => 0,
            ":tarj4" => 0,
            ":tarj5" => 0);
//        var_dump($params);

        $ins = $query->execute($params);

//        if ($ins == 0) {
//            echo "***ERROR EN INSERCION CIAS=" . $Plaza["cias"] . "\n";
//            return false;
//        }

        echo "==> PLAZA " . $Plaza["cias"] . " CREADA EN AREA \n";

        if ($Plaza["ceco"] != null) {
            procesoCecoCias($conexionArea, $Plaza["cias"], $Plaza["ceco"]);
        }
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERCION EN BASE DE DATOS AREA  " . $ex->getMessage() . " \n";
        $gblError = 1;
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
            echo "***Error en actualización base de datos unificada cias= " . $Plaza["cias"] . "\n";
            $gblError = 1;
            return false;
        }
        echo " PLAZA " . $Plaza["cias"] . " MODIFICADA EN LA BASE DE DATOS UNIFICADA \n";
        if ($Plaza["ceco"] != null) {
            procesoCecoCias($JanoUnif, $Plaza["cias"], $Plaza["ceco"]);
        }
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERRO EN  Actualicación " . $ex->getMessage() . " \n";
        $gblError = 1;
        return false;
    }
}

function updatePlazaArea($Plaza) {
    global $tipobd, $gblError;
    $conexionArea = conexionEdificio($Plaza["edificio"], $tipobd);
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
            echo "**ERROR UPDATE PLAZA EN EDIFICIO= " . $Plaza["edificio"] . " cias= " . $Plaza["cias"] . "\n";
            $gblError = 1;
            return false;
        }
        echo "==> PLAZA CIAS= (" . $Plaza["cias"] . ") MODIFICADA EN LA BASE DE DATOS AREA \n";

        if ($Plaza["ceco"] != null) {
            procesoCecoCias($conexionArea, $Plaza["cias"], $Plaza["ceco"]);
        }

        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE PLAZAS CIAS= (" . $Plaza["cias"] . ") \n" . $ex->getMessage() . "\n";
        $gblError = 1;
        return false;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO ACTUALIZACIÓN PLAZA +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$modo = $argv[1];
$id = $argv[2];
$actuacion = $argv[3];
$gblError = 0;

if ($modo == 'REAL') {
    echo " ENTORNO : PRODUCCIÓN \n";
    $tipobd = 2;
    $JanoInte = conexionPDO(selectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(selectBaseDatos(2, 'U'));
} else {
    echo " ENTORNO : VALIDACIÓN \n";
    $tipobd = 1;
    $JanoInte = conexionPDO(selectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(selectBaseDatos(1, 'U'));
}


$Plaza = selectPlazaById($id);

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

echo "==> ACTUACIÓN = " . $actuacion . " **\n";

if ($actuacion == 'INSERT') {
    procesoInsert($Plaza);
}

if ($actuacion == 'UPDATE') {
    procesoUpdate($Plaza);
}
echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN PLAZA +++++++++++++ \n";
exit($gblError);
