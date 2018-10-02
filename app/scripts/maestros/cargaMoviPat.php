<?php

include_once __DIR__ . '../../funcionesDAO.php';

function selectMoviPatEnuso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from movipat as t1 "
                . " where t1.codigo = :codigo";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["ENUSO"];
        } else {
            echo "**NO EXISTE  MOVIPAT=" . $codigo . " " . $ex->getMessage() . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR NO EN SELECT MOVIPAT=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertEqMoviPat($EqMoviPat) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_eq_movipat "
                . " (edificio_id, codigo_loc, movipat_id, enuso) values  ( :edificio_id, :codigo_loc, :movipat_id,:enuso )";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqMoviPat["edificio_id"],
            ":codigo_loc" => $EqMoviPat["codigo_loc"],
            ":movipat_id" => $EqMoviPat["movipat_id"],
            ":enuso" => $EqMoviPat["enuso"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT GUMS_EQ_MOVIPAT EDIFICIO= " . $EqMoviPat["edificio"] . " CODIGO_LOC = " . $EqMoviPat["codigo_loc"] . "\n";
            return null;
        }
        echo "GENERADA EQUIVALENCIA GUMS_EQ_MOVIPAT EDIFICIO = " . $EqMoviPat["edificio"] . " CODIGO_LOC = " . $EqMoviPat["codigo_loc"] . " USO= (" . $EqMoviPat["enuso"] . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_EQ_MOVIPAT " . $ex->getMessage() . "\n";
        return null;
    }
}

function insertMoviPat($MoviPat) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_movipat "
                . " (codigo, descrip, cif, pat_contin, obr_contin, pat_he, obr_he, pat_acc, obr_acc  "
                . " ,pat_fp ,obr_fp ,fogasa ,numeroseg, empresa, pat_munpal, obr_munpal, pat_integra  "
                . " ,enuso ,clave ,eventual, porcent, pat_acc_ant, forzar_l00 ) values "
                . " (:codigo, :descrip, :cif, :pat_contin, :obr_contin, :pat_he, :obr_he, :pat_acc, :obr_acc  "
                . " , :pat_fp , :obr_fp , :fogasa , :numeroseg, :empresa, :pat_munpal, :obr_munpal, :pat_integra  "
                . " , :enuso , :clave , :eventual, :porcent, :pat_acc_ant, :forzar_l00 )";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $MoviPat["CODIGO"],
            ":descrip" => $MoviPat["DESCRIP"],
            ":cif" => $MoviPat["CIF"],
            ":pat_contin" => $MoviPat["PAT_CONTIN"],
            ":obr_contin" => $MoviPat["OBR_CONTIN"],
            ":pat_he" => $MoviPat["PAT_HE"],
            ":obr_he" => $MoviPat["OBR_HE"],
            ":pat_acc" => $MoviPat["PAT_ACC"],
            ":obr_acc" => $MoviPat["OBR_ACC"],
            ":pat_fp" => $MoviPat["PAT_FP"],
            ":obr_fp" => $MoviPat["OBR_FP"],
            ":fogasa" => $MoviPat["FOGASA"],
            ":numeroseg" => $MoviPat["NUMEROSEG"],
            ":empresa" => $MoviPat["EMPRESA"],
            ":pat_munpal" => $MoviPat["PAT_MUNPAL"],
            ":obr_munpal" => $MoviPat["OBR_MUNPAL"],
            ":pat_integra" => $MoviPat["PAT_INTEGRA"],
            ":enuso" => $MoviPat["ENUSO"],
            ":clave" => $MoviPat["CLAVE"],
            ":eventual" => $MoviPat["EVENTUAL"],
            ":porcent" => $MoviPat["PORCENT"],
            ":pat_acc_ant" => $MoviPat["PAT_ACC_ANT"],
            ":forzar_l00" => $MoviPat["FORZAR_L00"]);

        $res = $insert->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_MOVIPAT CODIGO= " . $MoviPat["CODIGO"] . " \n";
            return null;
        }
        $MoviPat["ID"] = $JanoControl->lastInsertId();
        echo " CREADO GUMS_MOVIPAT ID= " . $MoviPat["ID"] . " CODIGO= " . $MoviPat["CODIGO"] . " " . $MoviPat["DESCRIP"] . "\n";
        return $MoviPat["ID"];
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_MOVIPAT CODIGO= " . $registro["CODIGO"] . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/*
 * Carga Inicial de la Tabla MoviPat y la correspondiente Tabla de Equivalencias
 */


/*
 * Cuerpo Principal 
 */

echo " -- CARGA INICIAL TABLA: gums_movipat " . "\n";
$JanoControl = jano_ctrl();
if (!$JanoControl) {
    exit(111);
}

/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];

if ($tipo == 'REAL') {
    echo " ENTORNO = PRODUCCIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo " ENTORNO = VALIDACIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

try {
    $sentencia = " delete from gums_eq_movipat";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_EQ_MOVIPAT " . $ex->getMessage() . "\n";
    exit(1);
}

try {
    $sentencia = " delete from gums_movipat";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_MOVIPAT " . $ex->getMessage() . "\n";
    exit(1);
}

try {
    $sentencia = " select * from movipat";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $MoviPatAll = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN LA SELECT DE MOVIPAT BASE DE DATOS UNIFICADA " . $ex->getMessage() . "\n";
    exit(1);
}

/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);


echo " Registros a Cargar = " . count($MoviPatAll) . "\n";

foreach ($MoviPatAll as $MoviPat) {
    $id = insertMoviPat($MoviPat);
    if ($id == null) {
        continue;
    }
    $MoviPat["ID"] = $id;
    foreach ($EdificioAll as $Edificio) {
        $sql = " select * from eq_movipat where codigo_uni = :codigo_uni and edificio = :edificio";
        $query = $JanoInte->prepare($sql);
        $params = array(":codigo_uni" => $MoviPat["CODIGO"],
            ":edificio" => $Edificio["codigo"]);
        $query->execute($params);
        $EqMoviPatAll = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($EqMoviPatAll) == 0) {
            $EqMoviPat["edificio_id"] = $Edificio["id"];
            $EqMoviPat["edificio"] = $Edificio["codigo"];
            $EqMoviPat["codigo_loc"] = "XXX";
            $EqMoviPat["codigo_uni"] = $MoviPat["CODIGO"];
            $EqMoviPat["movipat_id"] = $MoviPat["ID"];
            $EqMoviPat["enuso"] = "X";
            insertEqMoviPat($EqMoviPat);
        } else {
            $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
            if ($conexion) {
                foreach ($EqMoviPatAll as $rowEq) {
                    $EqMoviPat["edificio_id"] = $Edificio["id"];
                    $EqMoviPat["edificio"] = $Edificio["codigo"];
                    $EqMoviPat["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqMoviPat["codigo_uni"] = $MoviPat["CODIGO"];
                    $EqMoviPat["movipat_id"] = $MoviPat["ID"];
                    $EqMoviPat["enuso"] = selectMoviPatEnuso($conexion, $rowEq["CODIGO_LOC"]);
                    insertEqMoviPat($EqMoviPat);
                }
            }
        }
    }
}

echo " TERMINADA LA CARGA DE MOVIPAT " . "\n";
exit(0);

