<?php

include_once __DIR__ . '/../funcionesDAO.php';

function selectCatGenById($id) {
    global $JanoControl;
    try {
        $sentencia = " select * from gums_catgen where "
                . " id = :id";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":id" => $id);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo "**ERROR NO EXISTE CATGEN ID=" . $id . "\n";
            $gblError = 1;
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN CATGEN  ID=" . $id . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function selectEqCatGenAll() {
    global $CatGen, $JanoControl, $gblError;
    try {
        $sentencia = " select t1.id as id, t1.codigo_loc, t2.codigo as edificio, t2.id as edificio_id,  t1.enuso as enuso "
                . " from gums_eq_catgen as t1 "
                . " inner join comun_edificio as t2 on t1.edificio_id = t2.id "
                . " where t1.catgen_id = :id and t1.enuso = 'S' ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":id" => $CatGen["id"]);
        $query->execute($params);
        $EqCatGenAll = $query->fetchAll(PDO::FETCH_ASSOC);
        return $EqCatGenAll;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN SELECT gums_eq_catgen ID = " . $CatGen["id"] . " CODIGO_UNI = " . $CatGen["codigo"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function selectEqCatGenById($eqcatgen_id) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " select t1.id as id, t1.codigo_loc, t2.codigo as edificio, t2.id as edificio_id,  t1.enuso as enuso "
                . " from gums_eq_catgen as t1 "
                . " inner join comun_edificio as t2 on t1.edificio_id = t2.id "
                . " where t1.id = :id";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":id" => $eqcatgen_id);
        $query->execute($params);
        $EqCatGenAll = $query->fetch(PDO::FETCH_ASSOC);
        return $EqCatGenAll;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN SELECT gums_eq_catgen ID = " . $CatGen["id"] . " CODIGO_UNI = " . $CatGen["codigo"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertEqCatGen() {
    global $JanoControl, $CatGen, $JanoInte, $EqCatGen;
    try {
        $sentencia = " insert into eq_catgen (edificio, codigo_loc, codigo_uni ) values (:edificio, :codigo_loc, :codigo_uni)";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $EqCatGen["edificio"],
            "codigo_loc" => $CatGen["codigo"],
            "codigo_uni" => $CatGen["codigo"]);
        $ins = $query->execute($params);
        if ($ins) {
            try {
                $setencia = " update gums_eq_catgen set codigo_loc = :codigo_loc "
                        . " enuso = 'S' "
                        . " where catgen_id = :catgen_id "
                        . " and edificio_id = :edificio_id ";
                $query = $JanoControl->prepare($sentencia);
                $params = array(":catgen_id" => $CatGen["id"],
                    ":edificio_id" => $EqCatGen["edificio_id"]);
                $res = $query->execute($params);
                if ($res == 0) {
                    echo "**ERROR EN UPDATE CCAP_EQ_CATGP CATGEN_ID=" . $CatGen["id"] . " EDIFICIO_ID= " . $EqCatGen["edificio_id"] . "\n";
                    $gblError = 1;
                }
            } catch (PDOException $ex) {
                echo "**PDOERROR EN UPDATE CCAP_EQ_CATGP CATGEN_ID=" . $CatGen["id"] . " EDIFICIO_ID= " . $EqCatGen["edificio_id"] . "\n"
                . $ex->getMessage() . "\n";
                $gblError = 1;
            }
        } else {
            echo "**ERROR EN INSERT EQ_CATGP CATGEN_ID=" . $CatGen["id"] . " EDIFICIO_ID= " . $EqCatGen["edificio_id"] . "\n";
            $gblError = 1;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT EQ_CATGP CATGEN_ID=" . $CatGen["id"] . " EDIFICIO_ID= " . $EqCatGen["edificio_id"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function insertCatGen($conexion) {
    global $CatGen;
    try {
        $sentencia = " insert into catgen "
                . " ( codigo, descrip, btc_tbol_codigo, enuso, plan_org, cod_insalud, des_insalud, especialidad, codigo_sms) values "
                . " ( :codigo, :descrip, :btc_tbol_codigo, :enuso, :plan_org, :cod_insalud, :des_insalud, :especialidad, :codigo_sms)";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $CatGen["codigo"],
            ":descrip" => $CatGen["descripcion"],
            ":btc_tbol_codigo" => $CatGen["btc_tbol_codigo"],
            ":enuso" => $CatGen["enuso"],
            ":plan_org" => $CatGen["plan_org"],
            ":cod_insalud" => $CatGen["cod_insalud"],
            ":des_insalud" => $CatGen["des_insalud"],
            ":especialidad" => $CatGen["especialidad"],
            ":codigo_sms" => $CatGen["codigo_sms"]);
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN INSERT CATGEN  : ID= " . $CatGen["id"] . " CODIGO= " . $CatGen["codigo"] . " DESCRIPCION= " . $CatGen["descripcion"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>INSERT EN CATGEN  : ID= " . $CatGen["id"] . " CODIGO= " . $CatGen["codigo"] . " DESCRIPCION= " . $CatGen["descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CATGEN : ID= " . $CatGen["id"] . " CODIGO= " . $CatGen["codigo"] . " DESCRIPCION= " . $CatGen["descripcion"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function updateCatGen($conexion, $codigo) {
    global $CatGen;
    try {
        $sentencia = " update catgen set "
                . "  descrip = :descrip"
                . ", btc_tbol_codigo = :btc_tbol_codigo"
                . ", enuso = :enuso"
                . ", plan_org = :plan_org"
                . ", cod_insalud = :cod_insalud"
                . ", des_insalud = :des_insalud"
                . ", especialidad = :especialidad"
                . ", codigo_sms = :codigo_sms"
                . " where codigo = :codigo";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $CatGen["codigo"],
            ":descrip" => $CatGen["descripcion"],
            ":btc_tbol_codigo" => $CatGen["btc_tbol_codigo"],
            ":enuso" => $CatGen["enuso"],
            ":plan_org" => $CatGen["plan_org"],
            ":cod_insalud" => $CatGen["cod_insalud"],
            ":des_insalud" => $CatGen["des_insalud"],
            ":especialidad" => $CatGen["especialidad"],
            ":codigo_sms" => $CatGen["codigo_sms"]);

        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN UPDATE CATGEN : ID= " . $CatGen["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatGen["descripcion"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>UPDATE CATGEN : ID= " . $CatGen["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatGen["descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE CATGEN : ID= " . $CatGen["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatGen["descripcion"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function updateEqCatGenControl() {
    global $CatGen, $EqCatGen, $JanoControl;
    try {
        $sentencia = " update gums_eq_catgen set enuso = :enuso where id = :id";
        $query = $JanoControl->prepare($sentencia);
        $params = array("id" => $EqCatGen["id"],
            ":enuso" => $EqCatGen["enuso"]);
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN UPDATE gums_eq_catgen : ID= " . $EqCatGen["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatGen["descripcion"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>UPDATE gums_eq_catgen : ID= " . $EqCatGen["id"]
        . " CODIGO_LOC= " . $EqCatGen["codigo_loc"]
        . " CODIGO_UNI = " . $CatGen["codigo"]
        . " EDIFICIO= " . $EqCatGen["edificio"]
        . " ENUSO= " . $EqCatGen["enuso"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE gums_catgen : ID= " . $CatGen["id"] . " CODIGO= " . $codigo . " DESCRIPCION= " . $CatGen["descripcion"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */

echo " +++++++++++ COMIENZA PROCESO REPLICA DE CATEGORIA CATGEN  ++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$catgen_id = $argv[2];
$actuacion = $argv[3];
$eqcatgen_id = $argv[4];
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

$CatGen = selectCatGenById($catgen_id);
if (!$CatGen) {
    exit(1);
}
echo "==> CATEGORIA (CATGEN) ID=" . $CatGen["id"]
 . " CODIGO= " . $CatGen["codigo"]
 . " DESCRIPCION= " . $CatGen["descripcion"]
 . " ACTUACION : " . $actuacion
 . " EQCATGEN_ID: " . $eqcatgen_id
 . "\n";

if ($actuacion == 'INSERT') {
    if (insertCatGen($JanoUnif)) {
        $EqCatGenAll = selectEqCatGenAll();
        foreach ($EqCatGenAll as $EqCatGen) {
            $conexion = conexionEdificio($EqCatGen["edificio"], $tipobd);
            if ($conexion) {
                if (insertCatGen($conexion)) {
                    insertEqCatGen();
                    $EqCatGen["enuso"] = 'S';
                    updateEqCatGenControl();
                }
            }
        }
    }
}

if ($actuacion == 'UPDATE') {
    if (updateCatGen($JanoUnif, $CatGen["codigo"])) {
        $EqCatGenAll = selectEqCatGenAll();
        foreach ($EqCatGenAll as $EqCatGen) {
            $conexion = conexionEdificio($EqCatGen["edificio"], $tipobd);
            if ($conexion) {
                updateCatGen($conexion, $EqCatGen["codigo_loc"]);
            }
        }
    }
}

if ($actuacion == 'ACTIVAR') {
    $EqCatGen = selectEqCatGenById($eqcatgen_id);
    $conexion = conexionEdificio($EqCatGen["edificio"], $tipobd);
    $CatGen["enuso"] = 'S';
    if ($conexion) {
        updateCatGen($conexion, $EqCatGen["codigo_loc"]);
        $EqCatGen["enuso"] = 'S';
        updateEqCatGenControl();
    }
}

if ($actuacion == 'DESACTIVAR') {
    $EqCatGen = selectEqCatGenById($eqcatgen_id);
    $conexion = conexionEdificio($EqCatGen["edificio"], $tipobd);
    $CatGen["enuso"] = 'N';
    if ($conexion) {
        updateCatGen($conexion, $EqCatGen["codigo_loc"]);
        $EqCatGen["enuso"] = 'N';
        updateEqCatGenControl();
    }
}

if ($actuacion == 'CREAR') {
    $EqCatGen = selectEqCatGenById($eqcatgen_id);
    $conexion = conexionEdificio($EqCatGen["edificio"], $tipobd);
    $CatGen["codigo"] = $EqCatGen["codigo_loc"];
    if ($conexion) {
        insertCatGen($conexion);
        $EqCatGen["enuso"] = 'S';
        updateEqCatGenControl();
    }
}

echo "  +++++++++++ TERMINA PROCESO REPLICA CATEGORIA PROFESIONAL +++++++++++++ \n";
exit($gblError);
