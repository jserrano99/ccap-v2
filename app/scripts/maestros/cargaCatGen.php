<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqCatGen($EqCatGen) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into ccap_eq_catgen (edificio_id, catgen_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :catgen_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqCatGen["edificio_id"],
            ":catgen_id" => $EqCatGen["catgen_id"],
            ":codigo_loc" => $EqCatGen["codigo_loc"],
            ":enuso" => $EqCatGen["enUso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT CCAP_EQ_CATGEN EDIFICIO: " . $EqCatGen["edificio"]
            . " CATGEN=" . $EqCatGen["codigo_uni"]
            . " CODIGO_LOC= " . $EqCatGen["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT CCAP_EQ_CATGEN EDIFICIO: " . $EqCatGen["edificio"]
        . " CATGEN=" . $EqCatGen["codigo_uni"]
        . " CODIGO_LOC= " . $EqCatGen["codigo_loc"]
        . " EN USO = " . $EqCatGen["enUso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CCAP_EQ_CATGEN EDIFICIO: " . $row["EDIFICIO"]
        . " CATGEN=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectCatGenEnUso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from catgen as t1 "
                . " where t1.codigo = :codigo";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["ENUSO"];
        } else {
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR NO EN SELECT CATGEN=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertCatGen($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into ccap_catgen "
                . " (codigo, descripcion, btc_tbol_codigo, enuso,plan_org, cod_insalud, des_insalud, especialidad, codigo_sms)"
                . " values "
                . " (:codigo, :descripcion, :btc_tbol_codigo, :enuso, :plan_org, :cod_insalud, :des_insalud, :especialidad, :codigo_sms)";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $row["CODIGO"],
            ":descripcion" => $row["DESCRIP"],
            ":btc_tbol_codigo" => $row["BTC_TBOL_CODIGO"],
            ":enuso" => $row["ENUSO"],
            ":plan_org" => $row["PLAN_ORG"],
            ":cod_insalud" => $row["COD_INSALUD"],
            ":des_insalud" => $row["DES_INSALUD"],
            ":especialidad" => $row["ESPECIALIDAD"],
            ":codigo_sms" => $row["CODIGO_SMS"]);
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT CATEGORIA CATGEN CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA CATEGORIA (CATGEN) ID= " . $row["ID"] . " CATGEN:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CATGEN CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage()."\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL CATEGORIAS (CATGEN) +++++++++++ \n";
/*
 * Conexión a la base de datos de Control en Mysql 
 */
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}
/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];
$gblError = 0;
if ($tipo == 'REAL') {
    echo "==> ENTORNO: PRODUCCIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo "==> ENTORNO: VALIDACIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}
/*
 * INICIALIZAMOS LA TABLA Y LA CORRESPONDIENTE TABLA DE EQUIVALENCIAS
 */
$sentencia = " delete from ccap_eq_catgen";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (CCAP_EQ_CATGEN) REGISTROS: " . $rows . "\n";

$sentencia = " delete from ccap_catgen";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA CATEGORIA CATGEN (CCAP_CATGEN) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA CATGEN
 */
$sentencia = " select * from catgen";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertCatGen($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_catgen where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqCatGenAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqCatGenAll) == 0) {
                $EqCatGen["edificio_id"] = $Edificio["id"];
                $EqCatGen["edificio"] = $Edificio["codigo"];
                $EqCatGen["codigo_loc"] = "XXXX";
                $EqCatGen["codigo_uni"] = $row["CODIGO"];
                $EqCatGen["catgen_id"] = $row["ID"];
                $EqCatGen["enUso"] = "X";
                insertEqCatGen($EqCatGen);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqCatGenAll as $rowEq) {
                        $EqCatGen["edificio_id"] = $Edificio["id"];
                        $EqCatGen["edificio"] = $Edificio["codigo"];
                        $EqCatGen["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqCatGen["codigo_uni"] = $row["CODIGO"];
                        $EqCatGen["catgen_id"] = $row["ID"];
                        $EqCatGen["enUso"] = selectCatGenEnUso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqCatGen($EqCatGen);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL CATGEN  +++++++++++++ \n";
exit($gblError);
