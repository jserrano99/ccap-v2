<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqGrupoProf($EqGrupoProf) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_grupoprof (edificio_id, grupoprof_id, codigo_loc)  "
                . " values (:edificio_id, :grupoprof_id, :codigo_loc) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqGrupoProf["edificio_id"],
            ":grupoprof_id" => $EqGrupoProf["grupoprof_id"],
            ":codigo_loc" => $EqGrupoProf["codigo_loc"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_grupoprof EDIFICIO: " . $EqGrupoProf["edificio"]
            . " GRUPOPROF=" . $EqGrupoProf["codigo_uni"]
            . " CODIGO_LOC= " . $EqGrupoProf["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_grupoprof EDIFICIO: " . $EqGrupoProf["edificio"]
        . " GRUPOPROF=" . $EqGrupoProf["codigo_uni"]
        . " CODIGO_LOC= " . $EqGrupoProf["codigo_loc"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_grupoprof EDIFICIO: " . $row["EDIFICIO"]
        . " GRUPOPROF=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function insertGrupoProf($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_grupoprof ("
                . " codigo "
                . " ,descripcion"
                . " ,importe"
                . " ,exento_ss"
                . " ,muface_escala"
                . " ,sal_base"
                . " ,codigo2 "
                . " ) values ( "
                . " :codigo"
                . " ,:descripcion"
                . " ,:importe"
                . " ,:exento_ss"
                . " ,:muface_escala"
                . " ,:sal_base"
                . " ,:codigo2 )";

        $query = $JanoControl->prepare($sentencia);

        $params = array(
            ":codigo" => $row['CODIGO'],
            ":descripcion" => $row['DESCRIP'],
            ":importe" => $row['IMPORTE'],
            ":exento_ss" => $row['EXENTO_SS'],
            ":muface_escala" => $row['MUFACE_ESCALA'],
            ":sal_base" => $row['SAL_BASE'],
            ":codigo2" => $row['CODIGO_2']);

        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT GRUPOPROF () CODIGO= " . $row["codigo"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA GRUPOPROF () ID= " . $row["ID"] . " GRUPOPROF:" . $row["CODIGO"] . " " . $row["DESCRIP"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT GRUPOPROF () CODIGO= " . $row["codigo"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/**
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 */
echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL GRUPO PROFESIONAL (GRUPOPROF) +++++++++++ \n";
/*
 * Conexión a la base de datos de Control en Mysql 
 */
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}
/**
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
$sentencia = " delete from gums_eq_grupoprof";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA GRUPOPROF (gums_eq_grupoprof) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_grupoprof";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA GRUPOPROF (gums_grupoprof) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA GRUPOPROF
 */
$sentencia = " select * from grupoprof";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertGrupoProf($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_grupoprof where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqGrupoProfAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqGrupoProfAll) == 0) {
                $EqGrupoProf["edificio_id"] = $Edificio["id"];
                $EqGrupoProf["edificio"] = $Edificio["codigo"];
                $EqGrupoProf["codigo_loc"] = "XXXX";
                $EqGrupoProf["codigo_uni"] = $row["CODIGO"];
                $EqGrupoProf["grupoprof_id"] = $row["ID"];
                insertEqGrupoProf($EqGrupoProf);
            } else {
                foreach ($EqGrupoProfAll as $rowEq) {
                    $EqGrupoProf["edificio_id"] = $Edificio["id"];
                    $EqGrupoProf["edificio"] = $Edificio["codigo"];
                    $EqGrupoProf["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqGrupoProf["codigo_uni"] = $rowEq["CODIGO_UNI"];
                    $EqGrupoProf["grupoprof_id"] = $row["ID"];
                    insertEqGrupoProf($EqGrupoProf);
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL GRUPOPROF  +++++++++++++ \n";
exit($gblError);
