<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqCateg($EqCateg) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_categ (edificio_id, categ_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :categ_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqCateg["edificio_id"],
            ":categ_id" => $EqCateg["categ_id"],
            ":codigo_loc" => $EqCateg["codigo_loc"],
            ":enuso" => $EqCateg["enUso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_categ EDIFICIO: " . $EqCateg["edificio"]
            . " CATEG=" . $EqCateg["codigo_uni"]
            . " CODIGO_LOC= " . $EqCateg["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_categ EDIFICIO: " . $EqCateg["edificio"]
        . " CATEG=" . $EqCateg["codigo_uni"]
        . " CODIGO_LOC= " . $EqCateg["codigo_loc"]
        . " EN USO = " . $EqCateg["enUso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_categ EDIFICIO: " . $row["EDIFICIO"]
        . " CATEG=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectCategEnUso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from categ as t1 "
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
        echo "**PDOERROR NO EN SELECT CATEG=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertCateg($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_categ ("
                . "  catgen_id "
                . " ,codigo "
                . " ,descripcion"
                . " ,fsn"
                . " ,catAnexo_id"
                . " ,grupocot_id"
                . " ,grupoprof_id"
                . " ,grupocobro_id"
                . " ,ocupacion_id"
                . " ,epiacc_id"
                . " ,enuso"
                . " ,categ_orden"
                . " ,categoriarptid"
                . " ,catrpt_codigo"
                . " ,catrpt_descripcion"
                . " ,mir"
                . " ,categ_sms"
                . " ,grupo_tit"
                . " ,prof_san"
                . " ,directivo"
                . " ,cno2011"
                . " ,ceco_personal"
                . " ,ceco_categoria"
                . " ,tipocat"
                . " ,condicionado"
                . " ,id_grupocat "
                . " ) values ( "
                . "  :catgen_id "
                . " ,:codigo"
                . " ,:descripcion"
                . " ,:fsn"
                . " ,:catanexo_id"
                . " ,:grupocot_id"
                . " ,:grupoprof_id"
                . " ,:grupocobro_id"
                . " ,:ocupacion_id"
                . " ,:epiacc_id"
                . " ,:enuso"
                . " ,:categ_orden"
                . " ,:categoriarptid"
                . " ,:catrpt_codigo"
                . " ,:catrpt_descripcion"
                . " ,:mir"
                . " ,:categ_sms"
                . " ,:grupo_tit"
                . " ,:prof_san"
                . " ,:directivo"
                . " ,:cno2011"
                . " ,:ceco_personal"
                . " ,:ceco_categoria"
                . " ,:tipocat"
                . " ,:condicionado"
                . " ,:id_grupocat )";

        $query = $JanoControl->prepare($sentencia);
        
        $catgen = selectCatGen($row['CATGEN']);
        $catanexo_id = selectCatAnexo($row['CATANEXO']);
        $grupoprof_id = selectGrupoProf($row['GRUPOPROF']);
        $grupocot_id = selectGrupoCot($row['GRUPCOT']);
        $ocupacion_id = selectOcupacion($row['OCUPACION']);
        $epiacc_id = selectEpiAcc($row['EPIACC']);
        $grupocobro_id = selectGrupoCobro($row['GRUPOCOBRO']);

        $params = array(
                     ":catgen_id" => $catgen["id"],
                     ":codigo" => $row['CODIGO'] ,
                     ":descripcion"=> $row['DESCRIP'],
                     ":fsn"=> $row['FSN'],
                     ":catanexo_id"=> $catanexo_id,
                     ":grupocot_id"=> $grupocot_id,
                     ":grupoprof_id"=> $grupoprof_id,
                     ":grupocobro_id"=> $grupocobro_id,
                     ":ocupacion_id"=> $ocupacion_id,
                     ":epiacc_id"=> $epiacc_id,
                     ":enuso"=> $row['ENUSO'],
                     ":categ_orden"=> $row['CATEG_ORDEN'],
                     ":categoriarptid"=> $row['CATEGORIARPTID'],
                     ":catrpt_codigo"=> $row['CATRPT_CODIGO'],
                     ":catrpt_descripcion"=> $row['CATRPT_DESCRIPCION'],
                     ":mir"=> $row['MIR'],
                     ":categ_sms"=> $row['CATEG_SMS'],
                     ":grupo_tit"=> $row['GRUPO_TIT'],
                     ":prof_san"=> $row['PROF_SAN'],
                     ":directivo"=> $row['DIRECTIVO'],
                     ":cno2011"=> $row['CNO2011'],
                     ":ceco_personal"=> $row['CECO_PERSONAL'],
                     ":ceco_categoria"=> $row['CECO_CATEGORIA'],
                     ":tipocat"=> $row['TIPOCAT'],
                     ":condicionado"=> $row['CONDICIONADO'],
                     ":id_grupocat" => $row['ID_GRUPOCAT']);
          
        
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT CATEGORIA CATEG CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA CATEGORIA PROFESIONAL (CATEG) ID= " . $row["ID"] . " CATEG:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CATEG CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL CATEGORIAS (CATEG) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_categ";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (gums_eq_categ) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_categ";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA CATEGORIA CATEG (gums_categ) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA CATEG
 */
$sentencia = " select * from categ";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertCateg($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_categ where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqCategAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqCategAll) == 0) {
                $EqCateg["edificio_id"] = $Edificio["id"];
                $EqCateg["edificio"] = $Edificio["codigo"];
                $EqCateg["codigo_loc"] = "XXXX";
                $EqCateg["codigo_uni"] = $row["CODIGO"];
                $EqCateg["categ_id"] = $row["ID"];
                $EqCateg["enUso"] = "X";
                insertEqCateg($EqCateg);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqCategAll as $rowEq) {
                        $EqCateg["edificio_id"] = $Edificio["id"];
                        $EqCateg["edificio"] = $Edificio["codigo"];
                        $EqCateg["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqCateg["codigo_uni"] = $row["CODIGO"];
                        $EqCateg["categ_id"] = $row["ID"];
                        $EqCateg["enUso"] = selectCategEnUso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqCateg($EqCateg);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL CATEG  +++++++++++++ \n";
exit($gblError);
