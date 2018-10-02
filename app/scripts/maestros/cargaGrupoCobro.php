<?php

include_once __DIR__ . '/../funcionesDAO.php';

function insertEqGrc($EqGrc) {
    global $JanoControl, $gblError;
    try {
        $sentencia = "insert into gums_eq_grc (edificio_id, grupocobro_id, codigo_loc, enuso)  "
                . " values (:edificio_id, :grupocobro_id, :codigo_loc, :enuso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqGrc["edificio_id"],
            ":grupocobro_id" => $EqGrc["grupocobro_id"],
            ":codigo_loc" => $EqGrc["codigo_loc"],
            ":enuso" => $EqGrc["enuso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT gums_eq_grc EDIFICIO: " . $EqGrc["edificio"]
            . " GRC=" . $EqGrc["codigo_uni"]
            . " CODIGO_LOC= " . $EqGrc["codigo_loc"] . "\n";
            $gblError = 1;
        }
        echo "==>INSERT gums_eq_grc EDIFICIO: " . $EqGrc["edificio"]
        . " GRC=" . $EqGrc["codigo_uni"]
        . " CODIGO_LOC= " . $EqGrc["codigo_loc"]
        . " EN USO = " . $EqGrc["enuso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT gums_eq_grc EDIFICIO: " . $row["EDIFICIO"]
        . " GRC=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
        $gblError = 1;
    }
}

function selectGrcEnuso($conexion, $codigo) {
    global $gblError;
    try {
        $sentencia = " select enuso from grc as t1 "
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
        echo "**PDOERROR NO EN SELECT GRC=" . $codigo . " " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

function insertGrc($row) {
    global $JanoControl, $gblError;
    try {
        $sentencia = " insert into gums_grc "
                . " (codigo, enuso, epiacc_id, grupocot_id, ocupacion_id"
                . ", nivel, horas, grupob, apd, refuerzo, persinsueldo, cobra_nomina"
                . ", cotiza_ss, prodtsi, liq_extra, liq_vacaciones, retribucion"
                . ", tipo, minimo_fijo, minimo_interino, minimo_eventual, minimo_ev"
                . ", horas_anuales, horas_sabados, media_vacaciones, excluir_plpage"
                . ", grcrpt_codigo, grcrpt_descripcion, grcrptid, personal, peac"
                . ", excluir_extra, asumedia, extra_por_horas, asumedia_periodo"
                . " ) values ( "
                . "  :codigo, :enuso, :epiacc_id, :grupocot_id, :ocupacion_id"
                . ", :nivel, :horas, :grupob, :apd, :refuerzo, :persinsueldo, :cobra_nomina"
                . ", :cotiza_ss, :prodtsi, :liq_extra, :liq_vacaciones, :retribucion"
                . ", :tipo, :minimo_fijo, :minimo_interino, :minimo_eventual, :minimo_ev"
                . ", :horas_anuales, :horas_sabados, :media_vacaciones, :excluir_plpage"
                . ", :grcrpt_codigo, :grcrpt_descripcion, :grcrptid, :personal, :peac"
                . ", :excluir_extra, :asumedia, :extra_por_horas, :asumedia_periodo)";

        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $row["CODIGO"],
            ":enuso" => $row["ENUSO"],
            ":epiacc_id" => selectEpiAcc($row["EPIACC"]),
            ":grupocot_id" => selectGrupoCot($row["GRUPCOT"]),
            ":ocupacion_id" => selectOcupacion($row["OCUPACION"]),
            ":nivel" => $row["NIVEL"],
            ":horas" => $row["HORAS"],
            ":grupob" => $row["GRUPOB"],
            ":apd" => $row["APD"],
            ":refuerzo" => $row["REFUERZO"],
            ":persinsueldo" => $row["PERSINSUELDO"],
            ":cobra_nomina" => $row["COBRA_NOMINA"],
            ":cotiza_ss" => $row["COTIZA_SS"],
            ":prodtsi" => $row["PRODTSI"],
            ":liq_extra" => $row["LIQ_EXTRA"],
            ":liq_vacaciones" => $row["LIQ_VACACIONES"],
            ":retribucion" => $row["LIQ_VACACIONES"],
            ":tipo" => $row["TIPO"],
            ":minimo_fijo" => $row["MINIMO_FIJO"],
            ":minimo_interino" => $row["MINIMO_INTERINO"],
            ":minimo_eventual" => $row["MINIMO_EVENTUAL"],
            ":minimo_ev" => $row["MINIMO_EV"],
            ":horas_anuales" => $row["HORAS_ANUALES"],
            ":horas_sabados" => $row["HORAS_SABADOS"],
            ":media_vacaciones" => $row["MEDIA_VACACIONES"],
            ":excluir_plpage" => $row["EXCLUIR_PLPAGE"],
            ":grcrpt_codigo" => $row["GRCRPT_CODIGO"],
            ":grcrpt_descripcion" => $row["GRCRPT_DESCRIPCION"],
            ":grcrptid" => $row["GRCRPTID"],
            ":personal" => $row["PERSONAL"],
            ":peac" => $row["PEAC"],
            ":excluir_extra" => $row["EXCLUIR_EXTRA"],
            ":asumedia" => $row["ASUMEDIA"],
            ":extra_por_horas" => $row["EXTRA_POR_HORAS"],
            ":asumedia_periodo" => $row["ASUMEDIA_PERIODO"]);
        $res = $query->execute($params);
        $row["ID"] = $JanoControl->lastInsertId();
        if ($res == 0) {
            echo "**ERROR EN INSERT GRUPO COBRO (GRC) CODIGO= " . $row["CODIGO"] . "\n";
            $gblError = 1;
            return null;
        }
        echo "==>CREADA GRUPO COBRO (GRC) ID= " . $row["ID"] . " GRC:" . $row["CODIGO"] . " " . $row["DESCRIPCION"] . "\n";
        return $row["ID"];
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT GRUPO COBRO (GRC) CODIGO= " . $row["CODIGO"] . "\n ERROR = " . $ex->getMessage() . "\n";
        $gblError = 1;
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * 
 */

echo " +++++++++++ COMIENZA PROCESO CARGA INICIAL GRUPO COBROS (GRC) +++++++++++ \n";
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
$sentencia = " delete from gums_eq_grc";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA EQUIVALENCIAS (gums_eq_grc) REGISTROS: " . $rows . "\n";

$sentencia = " delete from gums_grc";
$query = $JanoControl->prepare($sentencia);
$rows = $query->execute();
echo " ELIMINADA TABLA GRUPO COBRO GRC (gums_grc) REGISTROS: " . $rows . "\n";
/*
 * SELECCIONAMOS TODOS LOS EDIFICIOS PARA ESTABLECER LAS EQUIVALENCIAS 
 */
$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

/*
 * SELECCIONAMOS TODOS LOS REGISTROS DE LA TABLA GRC
 */
$sentencia = " select * from grc";
$query = $JanoUnif->prepare($sentencia);
$query->execute();
$resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultSet as $row) {
    $id = insertGrc($row);
    $row["ID"] = $id;
    if ($id) {
        foreach ($EdificioAll as $Edificio) {
            $sql = " select * from eq_grc where codigo_uni = :codigo_uni and edificio = :edificio";
            $query = $JanoInte->prepare($sql);
            $params = array(":codigo_uni" => $row["CODIGO"],
                ":edificio" => $Edificio["codigo"]);
            $query->execute($params);
            $EqGrcAll = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($EqGrcAll) == 0) {
                $EqGrc["edificio_id"] = $Edificio["id"];
                $EqGrc["edificio"] = $Edificio["codigo"];
                $EqGrc["codigo_loc"] = "XXXX";
                $EqGrc["codigo_uni"] = $row["CODIGO"];
                $EqGrc["grupocobro_id"] = $row["ID"];
                $EqGrc["enuso"] = "X";
                insertEqGrc($EqGrc);
            } else {
                $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
                if ($conexion) {
                    foreach ($EqGrcAll as $rowEq) {
                        $EqGrc["edificio_id"] = $Edificio["id"];
                        $EqGrc["edificio"] = $Edificio["codigo"];
                        $EqGrc["codigo_loc"] = $rowEq["CODIGO_LOC"];
                        $EqGrc["codigo_uni"] = $row["CODIGO"];
                        $EqGrc["grupocobro_id"] = $row["ID"];
                        $EqGrc["enuso"] = selectGrcEnuso($conexion, $rowEq["CODIGO_LOC"]);
                        insertEqGrc($EqGrc);
                    }
                }
            }
        }
    }
}
echo "  +++++++++++ TERMINA PROCESO CARGA INICIAL GRC  +++++++++++++ \n";
exit($gblError);
