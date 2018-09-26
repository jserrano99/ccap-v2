<?php

include_once __DIR__ . '../../funcionesDAO.php';

function insertAusencia($Ausencia, $janoMaePer) {
    global $JanoControl;
    try {

        $sentencia = " insert into gums_ausencias "
                . "(a22, absentismo, afecta_revision, ausenciasrptid, ausrpt_codigo "
                . ",ausrpt_descripcion, autog, autog_desde, autog_hasta, btc_tipocon "
                . ",calculo_ffin, cambiogrc, cambiopuesto, cambiosgrc, codigo, codigonom "
                . ",contador, cotizass, csituadm, ctact, ctrl_horario, cuenta_pago, cuenta_turnic"
                . ",descrip, descu_trienios, destino, didesde1, didesde2, didesde3, dihasta1, dihasta2"
                . ",dihasta3, dtrab, dtrabperm, dur_reserva, enuso, epiacc_id, excluir_plpage, fco_id"
                . ",fin_red, guarda, huelga, idbasescon, itinerancia, justificante_dias, justificar"
                . ",mapturnos, max_anual, max_anual_h, max_total, max_total_h, mejora_it, modocupa_id"
                . ",movipat_id, naturales, naturales_ev, ocupacion_id, ocupacion_new_id, otrosperm, pagotit"
                . ",persinsu, porcen1, porcen2, porcen3, porcen_it, predecible, proporcional, red"
                . ",redondeo, reduccion, reserva, sindicato, tipo_ilt_id, tipo_inactividad, turnos, txtab, es_it"
                . ",jano_apartado,jano_apd,jano_codigo,jano_descripcion,jano_descripseg, jano_nombrelargo,jano_dldold"
                . ",jano_varold, jano_en_horas, jano_feclimant, jano_grado, jano_grautoriza, jano_responsable, jano_usuario"
                . ",jano_resto, jano_localidad, jano_maxadelanto, jano_maxlab, jano_maxnat, jano_mir, jano_sar, jano_suma_dias_cont"
                . ",jano_suma_dias_disc ) "
                . " values "
                . "(:a22,:absentismo,:afecta_revision,:ausenciasrptid,:ausrpt_codigo"
                . ",:ausrpt_descripcion,:autog,:autog_desde,:autog_hasta,:btc_tipocon"
                . ",:calculo_ffin,:cambiogrc,:cambiopuesto,:cambiosgrc,:codigo,:codigonom"
                . ",:contador,:cotizass,:csituadm,:ctact,:ctrl_horario,:cuenta_pago,:cuenta_turnic"
                . ",:descrip,:descu_trienios,:destino,:didesde1,:didesde2,:didesde3,:dihasta1,:dihasta2"
                . ",:dihasta3,:dtrab,:dtrabperm,:dur_reserva,:enuso,:epiacc_id,:excluir_plpage,:fco_id"
                . ",:fin_red,:guarda,:huelga,:idbasescon,:itinerancia,:justificante_dias,:justificar"
                . ",:mapturnos,:max_anual,:max_anual_h,:max_total,:max_total_h,:mejora_it,:modocupa_id"
                . ",:movipat_id,:naturales,:naturales_ev,:ocupacion_id,:ocupacion_new_id,:otrosperm,:pagotit"
                . ",:persinsu,:porcen1,:porcen2,:porcen3,:porcen_it,:predecible,:proporcional,:red"
                . ",:redondeo,:reduccion,:reserva,:sindicato,:tipo_ilt_id,:tipo_inactividad,:turnos,:txtab, :es_it"
                . ",:jano_apartado, :jano_apd, :jano_codigo, :jano_descripcion, :jano_descripseg, :jano_nombrelargo, :jano_dldold"
                . ",:jano_varold, :jano_en_horas, :jano_feclimant, :jano_grado, :jano_grautoriza, :jano_responsable, :jano_usuario"
                . ",:jano_resto, :jano_localidad, :jano_maxadelanto, :jano_maxlab, :jano_maxnat, :jano_mir, :jano_sar, :jano_suma_dias_cont"
                . ",:jano_suma_dias_disc ) ";

        $query = $JanoControl->prepare($sentencia);
        if ($Ausencia["TIPO_ILT"] == null) {
            $Ausencia["ES_IT"] = 'N';
        } else {
            $Ausencia["ES_IT"] = 'S';
        }
        $params = array(":a22" => $Ausencia["A22"],
            ":absentismo" => $Ausencia["ABSENTISMO"],
            ":afecta_revision" => $Ausencia["AFECTA_REVISION"],
            ":ausenciasrptid" => $Ausencia["AUSENCIASRPTID"],
            ":ausrpt_codigo" => $Ausencia["AUSRPT_CODIGO"],
            ":ausrpt_descripcion" => $Ausencia["AUSRPT_DESCRIPCION"],
            ":autog" => $Ausencia["AUTOG"],
            ":autog_desde" => $Ausencia["AUTOG_DESDE"],
            ":autog_hasta" => $Ausencia["AUTOG_HASTA"],
            ":btc_tipocon" => $Ausencia["BTC_TIPOCON"],
            ":calculo_ffin" => $Ausencia["CALCULO_FFIN"],
            ":cambiogrc" => $Ausencia["CAMBIOGRC"],
            ":cambiopuesto" => $Ausencia["CAMBIOPUESTO"],
            ":cambiosgrc" => $Ausencia["CAMBIOSGRC"],
            ":codigo" => $Ausencia["CODIGO"],
            ":codigonom" => $Ausencia["CODIGONOM"],
            ":contador" => $Ausencia["CONTADOR"],
            ":cotizass" => $Ausencia["COTIZASS"],
            ":csituadm" => $Ausencia["CSITUADM"],
            ":ctact" => $Ausencia["CTACT"],
            ":ctrl_horario" => $Ausencia["CTRL_HORARIO"],
            ":cuenta_pago" => $Ausencia["CUENTA_PAGO"],
            ":cuenta_turnic" => $Ausencia["CUENTA_TURNIC"],
            ":descrip" => $Ausencia["DESCRIP"],
            ":descu_trienios" => $Ausencia["DESCU_TRIENIOS"],
            ":destino" => $Ausencia["DESTINO"],
            ":didesde1" => $Ausencia["DIDESDE1"],
            ":didesde2" => $Ausencia["DIDESDE2"],
            ":didesde3" => $Ausencia["DIDESDE3"],
            ":dihasta1" => $Ausencia["DIHASTA1"],
            ":dihasta2" => $Ausencia["DIHASTA2"],
            ":dihasta3" => $Ausencia["DIHASTA3"],
            ":dtrab" => $Ausencia["DTRAB"],
            ":dtrabperm" => $Ausencia["DTRABPERM"],
            ":dur_reserva" => $Ausencia["DUR_RESERVA"],
            ":enuso" => $Ausencia["ENUSO"],
            ":excluir_plpage" => $Ausencia["EXCLUIR_PLPAGE"],
            ":fin_red" => $Ausencia["FIN_RED"],
            ":guarda" => $Ausencia["GUARDA"],
            ":huelga" => $Ausencia["HUELGA"],
            ":idbasescon" => $Ausencia["IDBASESCON"],
            ":itinerancia" => $Ausencia["ITINERANCIA"],
            ":justificante_dias" => $Ausencia["JUSTIFICANTE_DIAS"],
            ":justificar" => $Ausencia["JUSTIFICAR"],
            ":mapturnos" => $Ausencia["MAPTURNOS"],
            ":max_anual" => $Ausencia["MAX_ANUAL"],
            ":max_anual_h" => $Ausencia["MAX_ANUAL_H"],
            ":max_total" => $Ausencia["MAX_TOTAL"],
            ":max_total_h" => $Ausencia["MAX_TOTAL_H"],
            ":mejora_it" => $Ausencia["MEJORA_IT"],
            ":naturales" => $Ausencia["NATURALES"],
            ":naturales_ev" => $Ausencia["NATURALES_EV"],
            ":otrosperm" => $Ausencia["OTROSPERM"],
            ":pagotit" => $Ausencia["PAGOTIT"],
            ":persinsu" => $Ausencia["PERSINSU"],
            ":porcen1" => $Ausencia["PORCEN1"],
            ":porcen2" => $Ausencia["PORCEN2"],
            ":porcen3" => $Ausencia["PORCEN3"],
            ":porcen_it" => $Ausencia["PORCEN_IT"],
            ":predecible" => $Ausencia["PREDECIBLE"],
            ":proporcional" => $Ausencia["PROPORCIONAL"],
            ":red" => $Ausencia["RED"],
            ":redondeo" => $Ausencia["REDONDEO"],
            ":reduccion" => $Ausencia["REDUCCION"],
            ":reserva" => $Ausencia["RESERVA"],
            ":sindicato" => $Ausencia["SINDICATO"],
            ":tipo_inactividad" => $Ausencia["TIPO_INACTIVIDAD"],
            ":turnos" => $Ausencia["TURNOS"],
            ":txtab" => $Ausencia["TXTAB"],
            ":ocupacion_id" => selectOcupacion($Ausencia["OCUPACION"]),
            ":ocupacion_new_id" => selectOcupacion($Ausencia["OCUPACION_NEW"]),
            ":modocupa_id" => selectModOcupa($Ausencia["MODOCUPA"]),
            ":movipat_id" => selectMoviPat($Ausencia["PATRONAL"]),
            ":tipo_ilt_id" => selectTipoIlt($Ausencia["TIPO_ILT"]),
            ":epiacc_id" => selectEpiAcc($Ausencia["EPIACC"]),
            ":fco_id" => selectFco($Ausencia["FCO"]),
            ":es_it" => $Ausencia["ES_IT"],
            ":jano_apartado" => $janoMaePer["APARTADO"],
            ":jano_apd" => $janoMaePer["APD"],
            ":jano_codigo" => $janoMaePer["CODIGO"],
            ":jano_descripcion" => $janoMaePer["DESCRIPCION"],
            ":jano_descripseg" => $janoMaePer["DESCRIPSEG"],
            ":jano_nombrelargo" => $janoMaePer["NOMBRELARGO"],
            ":jano_dldold" => $janoMaePer["DLDOLD"],
            ":jano_varold" => $janoMaePer["VAROLD"],
            ":jano_en_horas" => $janoMaePer["EN_HORAS"],
            ":jano_feclimant" => $janoMaePer["FECLIMANT"],
            ":jano_grado" => $janoMaePer["GRADO"],
            ":jano_grautoriza" => $janoMaePer["GRAUTORIZA"],
            ":jano_responsable" => $janoMaePer["RESPONSABLE"],
            ":jano_usuario" => $janoMaePer["USUARIO"],
            ":jano_resto" => $janoMaePer["RESTO"],
            ":jano_localidad" => $janoMaePer["LOCALIDAD"],
            ":jano_maxadelanto" => $janoMaePer["MAXADELANTO"],
            ":jano_maxlab" => $janoMaePer["MAXLAB"],
            ":jano_maxnat" => $janoMaePer["MAXNAT"],
            ":jano_mir" => $janoMaePer["MIR"],
            ":jano_sar" => $janoMaePer["SAR"],
            ":jano_suma_dias_cont" => $janoMaePer["SUMA_DIAS_CONT"],
            ":jano_suma_dias_disc" => $janoMaePer["SUMA_DIAS_DISC"]);


        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_AUSENCIAS CODIGO= " . $Ausencia["CODIGO"] . " \n";
            return null;
        }
        $Ausencia["ID"] = $JanoControl->lastInsertId();
        echo " CREADA GUMS_AUSENCIA ID= " . $Ausencia["ID"] . " CODIGO= " . $Ausencia["CODIGO"] . " " . $Ausencia["DESCRIP"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT GUMS_AUSENCIAS CODIGO= " . $Ausencia["CODIGO"] . $ex->getMessage() . " \n";
        return null;
    }
}

function insertEqAusencia($EqAusencia) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_eq_ausencias "
                . " (edificio_id, codigo_loc, ausencia_id, enuso ) values  ( :edificio_id, :codigo_loc, :ausencia_id, :enuso)";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $edificio["id"],
            ":codigo_loc" => $EqAusencia["codigo_loc"],
            ":enuso" => $EqAusencia["enuso"],
            ":ausencia_id" => $Ausencia["ID"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT GUMS_EQ_AUSENCIAS EDIFICIO_ID= " . $edificio["id"] . " CODIGO_LOC = " . $EqAusencia["codigo_loc"] . "\n";
        }
        echo "GENERADA EQUIVALENCIA GUMS_EQ_AUSENCIAS EDIFICIO_ID= (" . $edificio["id"] . ") CODIGO_LOC= (" . $EqAusencia["codigo_loc"] . ") USO= (" . $EqAusencia["enuso"] . ") \n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_EQ_AUSENCIAS " . $ex->getMessage() . "\n";
    }
}

function selectEqAusencias($codigo) {
    global $JanoInte;
    try {
        $sentencia = " select * from eq_ausencias where codigo_uni = :codigo";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetchALL(PDO::FETCH_ASSOC);
        return $res;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT EQ_AUSENCIAS CODIGO= " . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectJanoMaePer($codigoSaint) {
    global $JanoUnif;
    try {
        $sentencia = " select t1.* from jano_maeper as t1 "
                . "inner join jano_equper as t2 on t1.codigo = t2.cod_maeper "
                . "where t2.cod_saint = :codigoSaint";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":codigoSaint" => $codigoSaint);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        return $res;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT JANO_MAEPER CODIGO= " . $codigoSaint . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/*
 * Cuerpo Principal 
 */
echo " -- CARGA INICIAL TABLA: GUMS_AUSENCIAS " . "\n";
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
    $sentencia = " delete from gums_eq_ausencias";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_EQ_AUSENCIAS " . $ex->getMessage() . "\n";
    exit(1);
}

try {
    $sentencia = " delete from gums_ausencias";
    $query = $JanoControl->prepare($sentencia);
    $query->execute();
} catch (PDOException $ex) {
    echo "***PDOERROR EN DELETE GUMS_AUSENCIAS " . $ex->getMessage() . "\n";
    exit(1);
}


try {
    $sentencia = " select * from ausencias";
    $query = $JanoUnif->prepare($sentencia);
    $query->execute();
    $AusenciasAll = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN LA SELECT DE AUSENCIAS BASE DE DATOS UNIFICADA " . $ex->getMessage() . "\n";
    exit(1);
}

$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);


echo " Registros a Cargar = " . count($AusenciasAll) . "\n";
foreach ($AusenciasAll as $Ausencia) {
    $janoMaePer = selectJanoMaePer($Ausencia["CODIGO"]);
    if (!insertAusencia($Ausencia, $janoMaePer)) {
        continue;
    }
    foreach ($EdificioAll as $Edificio) {
        $sql = " select * from eq_ausencias where codigo_uni = :codigo_uni and edificio = :edificio";
        $query = $JanoInte->prepare($sql);
        $params = array(":codigo_uni" => $Ausencia["CODIGO"],
            ":edificio" => $Edificio["codigo"]);
        $query->execute($params);
        $EqAusenciaAll = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($EqAusenciaAll) == 0) {
            $EqAusencia["edificio_id"] = $Edificio["id"];
            $EqAusencia["edificio"] = $Edificio["codigo"];
            $EqAusencia["codigo_loc"] = "XXX";
            $EqAusencia["codigo_uni"] = $Ausencia["CODIGO"];
            $EqAusencia["ausencia_id"] = $Ausencia["ID"];
            $EqAusencia["enuso"] = "X";
            insertEqAusencia($EqAusencia);
        } else {
            $conexion = conexionEdificio($Edificio["codigo"], $tipobd);
            if ($conexion) {
                foreach ($EqAusenciaAll as $rowEq) {
                    $EqAusencia["edificio_id"] = $Edificio["id"];
                    $EqAusencia["edificio"] = $Edificio["codigo"];
                    $EqAusencia["codigo_loc"] = $rowEq["CODIGO_LOC"];
                    $EqAusencia["codigo_uni"] = $Ausencia["CODIGO"];
                    $EqAusencia["ausencia_id"] = $Ausencia["ID"];
                    $EqAusencia["enuso"] = $rowEq["PRINCIPAL"];
                    insertEqAusencia($EqAusencia);
                }
            }
        }
    }
}

echo " TERMINADA LA CARGA DE GUMS_AUSENCIAS " . "\n";
exit(0);

