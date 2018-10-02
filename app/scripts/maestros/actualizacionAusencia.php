<?php

include_once __DIR__ . '../../funcionesDAO.php';

/**
 * 
 * @param type $Ausencia
 * @param type $edificio_id
 * @return type
 */
function verParams($Ausencia, $edificio_id) {
    $Equi = equivalenciasAusencia($Ausencia, $edificio_id);

    $params = array(":a22" => $Ausencia["a22"],
        ":absentismo" => $Ausencia["absentismo"],
        ":afecta_revision" => $Ausencia["afecta_revision"],
        ":ausenciasrptid" => $Ausencia["ausenciasrptid"],
        ":ausrpt_codigo" => $Ausencia["ausrpt_codigo"],
        ":ausrpt_descripcion" => $Ausencia["ausrpt_descripcion"],
        ":autog" => $Ausencia["autog"],
        ":autog_desde" => $Ausencia["autog_desde"],
        ":autog_hasta" => $Ausencia["autog_hasta"],
        ":btc_tipocon" => $Ausencia["btc_tipocon"],
        ":calculo_ffin" => $Ausencia["calculo_ffin"],
        ":cambiogrc" => $Ausencia["cambiogrc"],
        ":cambiopuesto" => $Ausencia["cambiopuesto"],
        ":cambiosgrc" => $Ausencia["cambiosgrc"],
        ":codigo" => $Ausencia["codigo"],
        ":codigonom" => $Ausencia["codigonom"],
        ":contador" => $Ausencia["contador"],
        ":cotizass" => $Ausencia["cotizass"],
        ":csituadm" => $Ausencia["csituadm"],
        ":ctact" => $Ausencia["ctact"],
        ":ctrl_horario" => $Ausencia["ctrl_horario"],
        ":cuenta_pago" => $Ausencia["cuenta_pago"],
        ":cuenta_turnic" => $Ausencia["cuenta_turnic"],
        ":descrip" => $Ausencia["descrip"],
        ":descu_trienios" => $Ausencia["descu_trienios"],
        ":destino" => $Ausencia["destino"],
        ":didesde1" => $Ausencia["didesde1"] == null ? 0 : $Ausencia["didesde1"],
        ":didesde2" => $Ausencia["didesde2"] == null ? 0 : $Ausencia["didesde2"],
        ":didesde3" => $Ausencia["didesde3"] == null ? 0 : $Ausencia["didesde3"],
        ":dihasta1" => $Ausencia["dihasta1"] == null ? 0 : $Ausencia["dihasta1"],
        ":dihasta2" => $Ausencia["dihasta2"] == null ? 0 : $Ausencia["dihasta2"],
        ":dihasta3" => $Ausencia["dihasta3"] == null ? 0 : $Ausencia["dihasta3"],
        ":dtrab" => $Ausencia["dtrab"],
        ":dtrabperm" => $Ausencia["dtrabperm"],
        ":dur_reserva" => $Ausencia["dur_reserva"],
        ":enuso" => $Ausencia["enuso"],
        ":excluir_plpage" => ($Ausencia["excluir_plpage"] == null) ? 'N' : $Ausencia["excluir_plpage"],
        ":fin_red" => $Ausencia["fin_red"],
        ":guarda" => $Ausencia["guarda"],
        ":huelga" => $Ausencia["huelga"],
        ":idbasescon" => $Ausencia["idbasescon"],
        ":itinerancia" => $Ausencia["itinerancia"],
        ":justificante_dias" => (int) $Ausencia["justificante_dias"],
        ":justificar" => $Ausencia["justificar"],
        ":mapturnos" => $Ausencia["mapturnos"],
        ":max_anual" => (int) $Ausencia["max_anual"],
        ":max_anual_h" => $Ausencia["max_anual_h"],
        ":max_total" => (int) $Ausencia["max_total"],
        ":max_total_h" => $Ausencia["max_total_h"],
        ":mejora_it" => ($Ausencia["mejora_it"] == null) ? 'N' : $Ausencia["mejora_it"],
        ":naturales" => $Ausencia["naturales"],
        ":naturales_ev" => $Ausencia["naturales_ev"],
        ":otrosperm" => $Ausencia["otrosperm"],
        ":pagotit" => $Ausencia["pagotit"],
        ":persinsu" => $Ausencia["persinsu"],
        ":porcen1" => $Ausencia["porcen1"] == null ? 0 : $Ausencia["porcen1"],
        ":porcen2" => $Ausencia["porcen2"] == null ? 0 : $Ausencia["porcen2"],
        ":porcen3" => $Ausencia["porcen3"] == null ? 0 : $Ausencia["porcen3"],
        ":porcen_it" => ($Ausencia["porcen_it"] == null) ? 0 : $Ausencia["porcen_it"],
        ":predecible" => $Ausencia["predecible"],
        ":proporcional" => $Ausencia["proporcional"],
        ":red" => $Ausencia["red"],
        ":redondeo" => $Ausencia["redondeo"] == null ? 0 :$Ausencia["redondeo"] ,
        ":reduccion" => $Ausencia["reduccion"],
        ":reserva" => ($Ausencia["reserva"] == null) ? 'N' : $Ausencia["reserva"],
        ":sindicato" => $Ausencia["sindicato"],
        ":tipo_inactividad" => $Ausencia["tipo_inactividad"],
        ":turnos" => $Ausencia["turnos"],
        ":txtab" => $Ausencia["txtab"],
        ":it_contador_jano" => $Ausencia["it_contador_jano"],
        ":epiacc" => $Equi["epiacc"],
        ":modocupa" => $Equi["modocupa"],
        ":fco" => $Equi["fco"],
        ":ocupacion" => $Equi["ocupacion"],
        ":ocupacion_new" => $Equi["ocupacion_new"],
        ":tipo_ilt" => $Equi["tipo_ilt"],
        ":patronal" => $Equi["patronal"]
    );

    return $params;
}

/**
 * 
 * @global type $JanoInte
 * @param type $Ausencia
 * @param type $area
 * @return boolean
 */
function insertEqAusencia($Ausencia, $area) {
    global $JanoInte;
    try {
        $sentencia = "insert into eq_ausencias "
                . " (edificio, codigo_loc, codigo_uni) "
                . " values "
                . " (:edificio, :codigo_loc, :codigo_uni) ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $area,
            ":codigo_loc" => $Ausencia["codigo"],
            ":codigo_uni" => $Ausencia["codigo"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT EQ_AUSENCIA EDIFICIO= " . $area . " CODIGO_LOC= " . $Ausencia["codigo"] . " CODIGO_UNI=" . $Ausencia["codigo"] . "\n";
            return null;
        }
        echo "INSERT EQ_AUSENCIA EDIFICIO= " . $area . " CODIGO_LOC= " . $Ausencia["codigo"] . " CODIGO_UNI=" . $Ausencia["codigo"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT EQ_AUSENCIA EDIFICIO= " . $area
        . " CODIGO_LOC= " . $Ausencia["codigo"]
        . " CODIGO_UNI=" . $Ausencia["codigo"]
        . " \t  " . $ex->getMessage()
        . " \n";
        return null;
    }
}

/**
 * 
 * @global type $JanoControl
 * @param type $id
 * @return type
 */
function selectAusenciaById($id) {
    global $JanoControl;
    try {
        $sentencia = "select t1.*, t2.codigo as ocupacion, t3.codigo as epiacc, t4.codigo as tipo_ilt "
                . " , t5.codigo as fco , t6.codigo as movipat, t7.codigo as modocupa, t8.codigo as ocupacion_new  "
                . " from gums_ausencias as t1 "
                . " left join gums_ocupacion as t2 on t1.ocupacion_id = t2.id "
                . " left join gums_epiacc as t3 on t1.epiacc_id = t3.id "
                . " left join gums_tipo_ilt as t4 on t1.tipo_ilt_id = t4.id "
                . " left join gums_fco as t5 on t1.fco_id = t5.id "
                . " left join gums_movipat as t6 on t1.movipat_id = t6.id "
                . " left join gums_modocupa as t7 on t1.modocupa_id = t7.id "
                . " left join gums_ocupacion as t8 on t1.ocupacion_new_id = t8.id "
                . " where t1.id = :id";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":id" => $id);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        return $res;
    } catch (PDOException $ex) {
        echo "*** PDOERROR EN SELECT GUMS_AUSENCIA ID= (" . $id . ") ERROR= " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $BasesDatos
 * @global type $JanoUnif
 * @param type $Ausencia
 */
function procesoInsert($Ausencia) {
    global $tipobd;

    if (!insertAusenciaUnif($Ausencia))
        exit(1);

    if ($Ausencia["jano_codigo"] != null) {
        if (!insertJanoMaePer($Ausencia))
            exit(1);
        if (!insertJanoEquPer($Ausencia))
            exit(1);
    }

    for ($i = 0; $i < 12; $i++) {
        $conexion = conexionEdificio($i, $tipobd);
        if ($conexion) {
            $edificio_id = selectEdificio($i);
            if (insertAusenciaAreas($conexion, $Ausencia, $edificio_id)) {
                insertEqAusencia($Ausencia, $i);
            }
        }
    }
}

/**
 * 
 * @global type $JanoUnif
 * @param type $Ausencia
 */
function procesoUpdate($Ausencia) {
    global $JanoUnif, $tipobd;

    if (!updateAusenciaUnif($Ausencia)) {
        exit(1);
    }

    if ($Ausencia["jano_codigo"] != null) {
        if (!updateJanoMaePer($Ausencia))
            exit(1);
    }

    for ($i = 0; $i < 12; $i++) {
        echo "--> Tratamiento Edificio : (" . $i . ") \n";
        echo " Equivalencia Código " . $Ausencia["codigo"] . " \n";
        $edificio_id = selectEdificio($i);
        $codigo = selectEqAusencia($Ausencia["id"], $edificio_id);
        if ($codigo) {
            echo "-->Codigo = (" . $Ausencia["codigo"] . ") / (" . $codigo . ")\n";
            $conexion = conexionEdificio($i, $tipobd);
            if ($conexion) {
                updateAusenciaAreas($Ausencia, $conexion, $codigo, $edificio_id);
            }
        }
    }
}

/**
 * 
 * @global string $SqlInsertAusencia
 * @param type $Ausencia
 * @return boolean
 */
function insertAusenciaUnif($Ausencia) {
    global $SqlInsertAusencia, $JanoUnif;
    try {
        $query = $JanoUnif->prepare($SqlInsertAusencia);

        $params = array(":a22" => $Ausencia["a22"],
            ":absentismo" => $Ausencia["absentismo"],
            ":afecta_revision" => $Ausencia["afecta_revision"],
            ":ausenciasrptid" => $Ausencia["ausenciasrptid"],
            ":ausrpt_codigo" => $Ausencia["ausrpt_codigo"],
            ":ausrpt_descripcion" => $Ausencia["ausrpt_descripcion"],
            ":autog" => $Ausencia["autog"],
            ":autog_desde" => $Ausencia["autog_desde"],
            ":autog_hasta" => $Ausencia["autog_hasta"],
            ":btc_tipocon" => $Ausencia["btc_tipocon"],
            ":calculo_ffin" => $Ausencia["calculo_ffin"],
            ":cambiogrc" => $Ausencia["cambiogrc"],
            ":cambiopuesto" => $Ausencia["cambiopuesto"],
            ":cambiosgrc" => $Ausencia["cambiosgrc"],
            ":codigo" => $Ausencia["codigo"],
            ":codigonom" => $Ausencia["codigonom"],
            ":contador" => $Ausencia["contador"],
            ":cotizass" => $Ausencia["cotizass"],
            ":csituadm" => $Ausencia["csituadm"],
            ":ctact" => $Ausencia["ctact"],
            ":ctrl_horario" => $Ausencia["ctrl_horario"],
            ":cuenta_pago" => $Ausencia["cuenta_pago"],
            ":cuenta_turnic" => $Ausencia["cuenta_turnic"],
            ":descrip" => $Ausencia["descrip"],
            ":descu_trienios" => $Ausencia["descu_trienios"],
            ":destino" => $Ausencia["destino"],
            ":didesde1" => $Ausencia["didesde1"] == null ? 0 : $Ausencia["didesde1"],
            ":didesde2" => $Ausencia["didesde2"] == null ? 0 : $Ausencia["didesde2"],
            ":didesde3" => $Ausencia["didesde3"] == null ? 0 : $Ausencia["didesde3"],
            ":dihasta1" => $Ausencia["dihasta1"] == null ? 0 : $Ausencia["dihasta1"],
            ":dihasta2" => $Ausencia["dihasta2"] == null ? 0 : $Ausencia["dihasta2"],
            ":dihasta3" => $Ausencia["dihasta3"] == null ? 0 : $Ausencia["dihasta3"],
            ":dtrab" => $Ausencia["dtrab"],
            ":dtrabperm" => $Ausencia["dtrabperm"],
            ":dur_reserva" => $Ausencia["dur_reserva"],
            ":enuso" => $Ausencia["enuso"],
            ":excluir_plpage" => ($Ausencia["excluir_plpage"] == null) ? 'N' : $Ausencia["excluir_plpage"],
            ":fin_red" => $Ausencia["fin_red"],
            ":guarda" => $Ausencia["guarda"],
            ":huelga" => $Ausencia["huelga"],
            ":idbasescon" => $Ausencia["idbasescon"],
            ":itinerancia" => $Ausencia["itinerancia"],
            ":justificante_dias" => (int) $Ausencia["justificante_dias"],
            ":justificar" => $Ausencia["justificar"],
            ":mapturnos" => $Ausencia["mapturnos"],
            ":max_anual" => (int) $Ausencia["max_anual"],
            ":max_anual_h" => $Ausencia["max_anual_h"],
            ":max_total" => (int) $Ausencia["max_total"],
            ":max_total_h" => $Ausencia["max_total_h"],
            ":mejora_it" => ($Ausencia["mejora_it"] == null) ? 'N' : $Ausencia["mejora_it"],
            ":naturales" => $Ausencia["naturales"],
            ":naturales_ev" => $Ausencia["naturales_ev"],
            ":otrosperm" => $Ausencia["otrosperm"],
            ":pagotit" => $Ausencia["pagotit"],
            ":persinsu" => $Ausencia["persinsu"],
            ":porcen1" => $Ausencia["porcen1"] == null ? 0 : $Ausencia["porcen1"],
            ":porcen2" => $Ausencia["porcen2"] == null ? 0 : $Ausencia["porcen2"],
            ":porcen3" => $Ausencia["porcen3"] == null ? 0 : $Ausencia["porcen3"],
            ":porcen_it" => ($Ausencia["porcen_it"] == null) ? 0 : $Ausencia["porcen_it"],
            ":predecible" => $Ausencia["predecible"],
            ":proporcional" => $Ausencia["proporcional"],
            ":red" => $Ausencia["red"],
            ":redondeo" => $Ausencia["redondeo"] == null ? 0 :$Ausencia["redondeo"] ,
            ":reduccion" => $Ausencia["reduccion"] ,
            ":reserva" => ($Ausencia["reserva"] == null) ? 'N' : $Ausencia["reserva"],
            ":sindicato" => $Ausencia["sindicato"],
            ":tipo_inactividad" => $Ausencia["tipo_inactividad"],
            ":turnos" => $Ausencia["turnos"],
            ":txtab" => $Ausencia["txtab"],
            ":it_contador_jano" => $Ausencia["it_contador_jano"],
            ":epiacc" => $Ausencia["epiacc"],
            ":modocupa" => $Ausencia["modocupa"],
            ":fco" => $Ausencia["fco"],
            ":ocupacion" => $Ausencia["ocupacion"],
            ":ocupacion_new" => $Ausencia["ocupacion_new"],
            ":tipo_ilt" => $Ausencia["tipo_ilt"],
            ":patronal" => $Ausencia["patronal"]
        );

        echo " Parametros = " . count($params) . "\n";

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
            return false;
        }
        echo "=> INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . " BASE DE DATOS UNIFICADA \n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"]
        . " DESCRIPCION= " . $Ausencia["descrip"] . "  " . $ex->getMessage() . "\n";
        return false;
    }
}

/**
 * 
 * @param type $Ausencia
 * @param type $edificio_id
 * @return type
 */
function equivalenciasAusencia($Ausencia, $edificio_id) {

    $Equi["epiacc"] = selectEqEpiAcc($Ausencia["epiacc_id"], $edificio_id);
    $Equi["modocupa"] = selectEqModOcupa($Ausencia["modocupa_id"], $edificio_id);
    $Equi["fco"] = selectEqFco($Ausencia["fco_id"], $edificio_id);
    $Equi["ocupacion"] = selectEqOcupacion($Ausencia["ocupacion_id"], $edificio_id);
    $Equi["ocupacion_new"] = selectEqOcupacion($Ausencia["ocupacion_new_id"], $edificio_id);
    $Equi["tipo_ilt"] = selectEqTipoIlt($Ausencia["tipo_ilt_id"], $edificio_id);
    $Equi["patronal"] = selectEqMoviPat($Ausencia["movipat_id"], $edificio_id);

    echo " EQUIVALENCIAS (LOCAL/UNIFICADA) \n";
    echo " =============================== \n";
    echo " EPIACC = (" . $Equi["epiacc"] . ")/(" . $Ausencia["epiacc_id"] . ") \n";
    echo " MODOCUPA= (" . $Equi["modocupa"] . ")/(" . $Ausencia["modocupa_id"] . ") \n";
    echo " FCO= (" . $Equi["fco"] . ")/(" . $Ausencia["fco_id"] . ") \n";
    echo " OCUPACION= (" . $Equi["ocupacion"] . ")/(" . $Ausencia["ocupacion_id"] . ") \n";
    echo " OCUPACION_NEW= (" . $Equi["ocupacion_new"] . ")/(" . $Ausencia["ocupacion_new_id"] . ") \n";
    echo " TIPO_ILT= (" . $Equi["tipo_ilt"] . ")/(" . $Ausencia["tipo_ilt_id"] . ") \n";
    echo " PATRONAL= (" . $Equi["patronal"] . ")/(" . $Ausencia["movipat_id"] . ") \n";

    return $Equi;
}

/**
 * 
 * @global string $SqlInsertAusencia
 * @param type $conexion
 * @param type $Ausencia
 * @param type $edificio_id
 * @return boolean
 */
function insertAusenciaAreas($conexion, $Ausencia, $edificio_id) {
    global $SqlInsertAusencia;
    try {
        $query = $conexion->prepare($SqlInsertAusencia);

        $params = verParams($Ausencia, $edificio_id);

        //var_dump($params);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
            return null;
        }
        echo "=> INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . " EDIFICIO_ID= (" . $edificio_id . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"]
        . " DESCRIPCION= " . $Ausencia["descrip"] . "  " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoUnif
 * @param type $Ausencia
 * @return boolean
 */
function insertJanoEquPer($Ausencia) {
    global $JanoUnif;
    try {
        $sentencia = " insert into jano_equper "
                . "( cod_saint, cod_maeper, sar, principal ) "
                . " values "
                . "( :cod_saint, :cod_maeper, :sar, :principal ) ";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":cod_saint" => $Ausencia["codigo"],
            ":cod_maeper" => $Ausencia["jano_codigo"],
            ":sar" => $Ausencia["jano_sar"],
            ":principal" => "S");

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT JANO_EQUPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
            return null;
        }
        echo "=>INSERT JANO_EQUPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT JANO_EQUPER CODIGO= " . $Ausencia["jano_codigo"]
        . " DESCRIPCION= " . $Ausencia["jano_descripcion"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoUnif
 * @param type $Ausencia
 * @return boolean
 */
function insertJanoMaePer($Ausencia) {
    global $JanoUnif;
    try {
        $sentencia = " insert into jano_maeper "
                . " (apartado, apd, codigo, con2annos, contador, descripcion"
                . " , descripseg, dldold, documento, en_horas, enuso, es_it"
                . ", fecfin_abierta, feclimant, grado, grautoriza, id_it"
                . ", justificante, localidad, maxadelanto, maxlab, maxnat, mir"
                . ", nombrelargo, reduccion, referencia, responsable, resto, sar"
                . ", sitadm, suma_dias_cont, suma_dias_disc, usuario, varold )"
                . " values "
                . " (:apartado, :apd, :codigo, :con2annos, :contador, :descripcion"
                . ", :descripseg, :dldold, :documento, :en_horas, :enuso, :es_it"
                . ", :fecfin_abierta, :feclimant, :grado, :grautoriza, :id_it"
                . ", :justificante, :localidad, :maxadelanto, :maxlab, :maxnat, :mir"
                . ", :nombrelargo, :reduccion, :referencia, :responsable, :resto, :sar"
                . ", :sitadm, :suma_dias_cont, :suma_dias_disc, :usuario, :varold)";

        $query = $JanoUnif->prepare($sentencia);
        $params = array(":apartado" => $Ausencia["jano_apartado"],
            ":apd" => $Ausencia["jano_apd"],
            ":codigo" => $Ausencia["jano_codigo"],
            ":con2annos" => $Ausencia["jano_con2annos"],
            ":contador" => $Ausencia["contador"],
            ":descripcion" => $Ausencia["jano_descripcion"],
            ":descripseg" => $Ausencia["jano_descripseg"],
            ":dldold" => $Ausencia["jano_dldold"],
            ":documento" => $Ausencia["jano_documento"],
            ":en_horas" => $Ausencia["jano_en_horas"],
            ":enuso" => $Ausencia["enuso"],
            ":es_it" => $Ausencia["es_it"],
            ":fecfin_abierta" => $Ausencia["jano_fecfin_abierta"],
            ":feclimant" => $Ausencia["jano_feclimant"],
            ":grado" => $Ausencia["jano_grado"],
            ":grautoriza" => $Ausencia["jano_grautoriza"],
            ":id_it" => $Ausencia["tipo_ilt"] == null ? ' ' : $Ausencia["tipo_ilt"],
            ":justificante" => $Ausencia["justificar"],
            ":localidad" => $Ausencia["jano_localidad"],
            ":maxadelanto" => $Ausencia["jano_maxadelanto"],
            ":maxlab" => $Ausencia["jano_maxlab"] == null ? 0 : $Ausencia["jano_maxlab"],
            ":maxnat" => $Ausencia["jano_maxnat"] == null ? 0 : $Ausencia["jano_maxnat"],
            ":mir" => $Ausencia["jano_mir"],
            ":nombrelargo" => $Ausencia["jano_nombrelargo"],
            ":reduccion" => $Ausencia["reduccion"],
            ":referencia" => "",
            ":responsable" => $Ausencia["jano_responsable"],
            ":resto" => $Ausencia["jano_resto"],
            ":sar" => $Ausencia["jano_sar"],
            ":sitadm" => $Ausencia["csituadm"],
            ":suma_dias_cont" => $Ausencia["jano_suma_dias_cont"],
            ":suma_dias_disc" => $Ausencia["jano_suma_dias_disc"],
            ":usuario" => $Ausencia["jano_usuario"],
            ":varold" => $Ausencia["jano_varold"]);

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT JANO_MAEPER CODIGO= " . $Ausencia["janoCodigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
            return null;
        }
        echo "=> INSERT JANO_MAEPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT JANO_MAEPER CODIGO= " . $Ausencia["jano_codigo"]
        . " DESCRIPCION= " . $Ausencia["jano_descripcion"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global string $SqlUpdateAusencia
 * @param type $Ausencia
 * @return boolean
 */
function updateAusenciaUnif($Ausencia) {
    global $SqlUpdateAusencia, $JanoUnif;
    try {
        $query = $JanoUnif->prepare($SqlUpdateAusencia);

        $params = array(":a22" => $Ausencia["a22"],
            ":absentismo" => $Ausencia["absentismo"],
            ":afecta_revision" => $Ausencia["afecta_revision"],
            ":ausenciasrptid" => $Ausencia["ausenciasrptid"],
            ":ausrpt_codigo" => $Ausencia["ausrpt_codigo"],
            ":ausrpt_descripcion" => $Ausencia["ausrpt_descripcion"],
            ":autog" => $Ausencia["autog"],
            ":autog_desde" => $Ausencia["autog_desde"],
            ":autog_hasta" => $Ausencia["autog_hasta"],
            ":btc_tipocon" => $Ausencia["btc_tipocon"],
            ":calculo_ffin" => $Ausencia["calculo_ffin"],
            ":cambiogrc" => $Ausencia["cambiogrc"],
            ":cambiopuesto" => $Ausencia["cambiopuesto"],
            ":cambiosgrc" => $Ausencia["cambiosgrc"],
            ":codigo" => $Ausencia["codigo"],
            ":codigonom" => $Ausencia["codigonom"],
            ":contador" => $Ausencia["contador"],
            ":cotizass" => $Ausencia["cotizass"],
            ":csituadm" => $Ausencia["csituadm"],
            ":ctact" => $Ausencia["ctact"],
            ":ctrl_horario" => $Ausencia["ctrl_horario"],
            ":cuenta_pago" => $Ausencia["cuenta_pago"],
            ":cuenta_turnic" => $Ausencia["cuenta_turnic"],
            ":descrip" => $Ausencia["descrip"],
            ":descu_trienios" => $Ausencia["descu_trienios"],
            ":destino" => $Ausencia["destino"],
            ":didesde1" => $Ausencia["didesde1"] == null ? 0 : $Ausencia["didesde1"],
            ":didesde2" => $Ausencia["didesde2"] == null ? 0 : $Ausencia["didesde2"],
            ":didesde3" => $Ausencia["didesde3"] == null ? 0 : $Ausencia["didesde3"],
            ":dihasta1" => $Ausencia["dihasta1"] == null ? 0 : $Ausencia["dihasta1"],
            ":dihasta2" => $Ausencia["dihasta2"] == null ? 0 : $Ausencia["dihasta2"],
            ":dihasta3" => $Ausencia["dihasta3"] == null ? 0 : $Ausencia["dihasta3"],
            ":dtrab" => $Ausencia["dtrab"],
            ":dtrabperm" => $Ausencia["dtrabperm"],
            ":dur_reserva" => $Ausencia["dur_reserva"],
            ":enuso" => $Ausencia["enuso"],
            ":epiacc" => $Ausencia["epiacc"],
            ":excluir_plpage" => ($Ausencia["excluir_plpage"] == null) ? 'N' : $Ausencia["excluir_plpage"],
            ":fco" => $Ausencia["fco"],
            ":fin_red" => $Ausencia["fin_red"],
            ":guarda" => $Ausencia["guarda"],
            ":huelga" => $Ausencia["huelga"],
            ":idbasescon" => $Ausencia["idbasescon"],
            ":itinerancia" => $Ausencia["itinerancia"],
            ":justificante_dias" => (int) $Ausencia["justificante_dias"],
            ":justificar" => $Ausencia["justificar"],
            ":mapturnos" => $Ausencia["mapturnos"],
            ":max_anual" => (int) $Ausencia["max_anual"],
            ":max_anual_h" => $Ausencia["max_anual_h"],
            ":max_total" => (int) $Ausencia["max_total"],
            ":max_total_h" => $Ausencia["max_total_h"],
            ":mejora_it" => ($Ausencia["mejora_it"] == null) ? 'N' : $Ausencia["mejora_it"],
            ":modocupa" => $Ausencia["modocupa"],
            ":naturales" => $Ausencia["naturales"],
            ":naturales_ev" => $Ausencia["naturales_ev"],
            ":ocupacion" => $Ausencia["ocupacion"],
            ":ocupacion_new" => $Ausencia["ocupacion_new"],
            ":otrosperm" => $Ausencia["otrosperm"],
            ":pagotit" => $Ausencia["pagotit"],
            ":patronal" => $Ausencia["patronal"],
            ":persinsu" => $Ausencia["persinsu"],
            ":porcen1" => $Ausencia["porcen1"] == null ? 0 : $Ausencia["porcen1"],
            ":porcen2" => $Ausencia["porcen2"] == null ? 0 : $Ausencia["porcen2"],
            ":porcen3" => $Ausencia["porcen3"] == null ? 0 : $Ausencia["porcen3"],
            ":porcen_it" => ($Ausencia["porcen_it"] == null) ? 0 : $Ausencia["porcen_it"],
            ":predecible" => $Ausencia["predecible"],
            ":proporcional" => $Ausencia["proporcional"],
            ":red" => $Ausencia["red"],
            ":redondeo" => $Ausencia["redondeo"] == null ? 0 :$Ausencia["redondeo"] ,
             ":reduccion" => $Ausencia["reduccion"],
            ":reserva" => ($Ausencia["reserva"] == null) ? 'N' : $Ausencia["reserva"],
            ":sindicato" => $Ausencia["sindicato"],
            ":tipo_ilt" => $Ausencia["tipo_ilt"],
            ":tipo_inactividad" => $Ausencia["tipo_inactividad"],
            ":turnos" => $Ausencia["turnos"],
            ":txtab" => $Ausencia["txtab"],
            ":it_contador_jano" => $Ausencia["it_contador_jano"]);

        echo " PARAMETROS = " . count($params) . "\n";
        $res = $query->execute($params);

        if ($res == 0) {
            echo "**ERROR EN UPDATE AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
            return null;
        }
        echo "UPDATE AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . " BASE DATOS UNIFICADA \n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE AUSENCIAS CODIGO= (" . $Ausencia["codigo"]
        . ") DESCRIPCION= (" . $Ausencia["descrip"]
        . ") ERROR=  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global string $SqlUpdateAusencia
 * @param type $Ausencia
 * @param type $conexion
 * @param type $codigo
 * @param type $edificio_id
 * @return boolean
 */
function updateAusenciaAreas($Ausencia, $conexion, $codigo, $edificio_id) {
    global $SqlUpdateAusencia;
    try {

        $query = $conexion->prepare($SqlUpdateAusencia);

        $params = verParams($Ausencia, $edificio_id);

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE AUSENCIAS CODIGO= " . $codigo . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
            return null;
        }
        echo "UPDATE AUSENCIAS CODIGO= " . $codigo . " DESCRIPCION= " . $Ausencia["descrip"] . " EDIFICIO_ID= (" . $edificio_id . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE AUSENCIAS CODIGO= " . $codigo
        . " DESCRIPCION= " . $Ausencia["descrip"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoUnif
 * @param type $Ausencia
 * @return boolean
 */
function updateJanoMaePer($Ausencia) {
    global$JanoUnif;
    try {
        $sentencia = " update jano_maeper set "
                . "  apartado = :apartado, "
                . "  apd = :apd, "
                . "  con2annos = :con2annos, "
                . "  contador = :contador, "
                . "  descripcion = :descripcion, "
                . "  descripseg = :descripseg, "
                . "  dldold = :dldold, "
                . "  documento = :documento, "
                . "  en_horas = :en_horas, "
                . "  enuso = :enuso, "
                . "  es_it = :es_it, "
                . "  fecfin_abierta = :fecfin_abierta, "
                . "  feclimant = :feclimant, "
                . "  grado = :grado, "
                . "  grautoriza = :grautoriza, "
                . "  id_it = :id_it, "
                . "  justificante = :justificante, "
                . "  localidad = :localidad, "
                . "  maxadelanto = :maxadelanto, "
                . "  maxlab = :maxlab, "
                . "  maxnat = :maxnat, "
                . "  mir = :mir, "
                . "  nombrelargo = :nombrelargo, "
                . "  reduccion = :reduccion, "
                . "  referencia = :referencia, "
                . "  responsable = :responsable, "
                . "  resto = :resto, "
                . "  sar = :sar, "
                . "  sitadm = :sitadm, "
                . "  suma_dias_cont = :suma_dias_cont, "
                . "  suma_dias_disc = :suma_dias_disc, "
                . "  usuario = :usuario, "
                . "  varold = :varold "
                . " where codigo = :codigo ";


        $query = $JanoUnif->prepare($sentencia);
        $params = array(":apartado" => $Ausencia["jano_apartado"],
            ":apd" => $Ausencia["jano_apd"],
            ":codigo" => $Ausencia["jano_codigo"],
            ":con2annos" => $Ausencia["jano_con2annos"],
            ":contador" => $Ausencia["contador"],
            ":descripcion" => $Ausencia["jano_descripcion"],
            ":descripseg" => $Ausencia["jano_descripseg"],
            ":dldold" => $Ausencia["jano_dldold"],
            ":documento" => $Ausencia["jano_documento"],
            ":en_horas" => $Ausencia["jano_en_horas"],
            ":enuso" => $Ausencia["enuso"],
            ":es_it" => $Ausencia["es_it"],
            ":fecfin_abierta" => $Ausencia["jano_fecfin_abierta"],
            ":feclimant" => $Ausencia["jano_feclimant"],
            ":grado" => $Ausencia["jano_grado"],
            ":grautoriza" => $Ausencia["jano_grautoriza"],
            ":id_it" => $Ausencia["tipo_ilt"] == null ? ' ' : $Ausencia["tipo_ilt"],
            ":justificante" => $Ausencia["justificar"],
            ":localidad" => $Ausencia["jano_localidad"],
            ":maxadelanto" => $Ausencia["jano_maxadelanto"],
            ":maxlab" => $Ausencia["jano_maxlab"] == null ? 0 : $Ausencia["jano_maxlab"],
            ":maxnat" => $Ausencia["jano_maxnat"] == null ? 0 : $Ausencia["jano_maxnat"],
            ":mir" => $Ausencia["jano_mir"],
            ":nombrelargo" => $Ausencia["jano_nombrelargo"],
            ":reduccion" => $Ausencia["reduccion"],
            ":referencia" => "",
            ":responsable" => $Ausencia["jano_responsable"],
            ":resto" => $Ausencia["jano_resto"],
            ":sar" => $Ausencia["jano_sar"],
            ":sitadm" => $Ausencia["csituadm"],
            ":suma_dias_cont" => $Ausencia["jano_suma_dias_cont"],
            ":suma_dias_disc" => $Ausencia["jano_suma_dias_disc"],
            ":usuario" => $Ausencia["jano_usuario"],
            ":varold" => $Ausencia["jano_varold"]);

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE JANO_MAEPER CODIGO= " . $Ausencia["janoCodigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
            return null;
        }
        echo "UPDATE JANO_MAEPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE JANO_MAEPER CODIGO= " . $Ausencia["jano_codigo"]
        . " DESCRIPCION= " . $Ausencia["jano_descripcion"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * COMIEZO PROCESO
 */
echo " ++++++ COMIENZA PROCESO SINCRONIZACIÓN AUSENCIAS +++++++++++ \n";

/**
 * VARIABLES GLOBALES 
 */
$SqlInsertAusencia = " insert into ausencias "
        . "(a22, absentismo, afecta_revision, ausenciasrptid, ausrpt_codigo "
        . ",ausrpt_descripcion, autog, autog_desde, autog_hasta, btc_tipocon "
        . ",calculo_ffin, cambiogrc, cambiopuesto, cambiosgrc, codigo, codigonom "
        . ",contador, cotizass, csituadm, ctact, ctrl_horario, cuenta_pago, cuenta_turnic"
        . ",descrip, descu_trienios, destino, didesde1, didesde2, didesde3, dihasta1, dihasta2"
        . ",dihasta3, dtrab, dtrabperm, dur_reserva, enuso, epiacc, excluir_plpage, fco"
        . ",fin_red, guarda, huelga, idbasescon, itinerancia, justificante_dias, justificar"
        . ",mapturnos, max_anual, max_anual_h, max_total, max_total_h, mejora_it, modocupa"
        . ",patronal, naturales, naturales_ev, ocupacion, ocupacion_new, otrosperm, pagotit"
        . ",persinsu, porcen1, porcen2, porcen3, porcen_it, predecible, proporcional, red"
        . ",redondeo, reduccion, reserva, sindicato, tipo_ilt, tipo_inactividad, turnos, txtab, it_contador_jano)"
        . " values "
        . "(:a22, :absentismo, :afecta_revision, :ausenciasrptid, :ausrpt_codigo"
        . ",:ausrpt_descripcion, :autog, :autog_desde, :autog_hasta, :btc_tipocon"
        . ",:calculo_ffin, :cambiogrc, :cambiopuesto, :cambiosgrc, :codigo, :codigonom"
        . ",:contador, :cotizass, :csituadm,:ctact, :ctrl_horario, :cuenta_pago, :cuenta_turnic"
        . ",:descrip, :descu_trienios, :destino, :didesde1, :didesde2, :didesde3, :dihasta1, :dihasta2"
        . ",:dihasta3, :dtrab, :dtrabperm, :dur_reserva, :enuso, :epiacc, :excluir_plpage, :fco"
        . ",:fin_red, :guarda, :huelga, :idbasescon, :itinerancia, :justificante_dias, :justificar"
        . ",:mapturnos, :max_anual, :max_anual_h, :max_total, :max_total_h, :mejora_it, :modocupa"
        . ",:patronal, :naturales, :naturales_ev, :ocupacion, :ocupacion_new, :otrosperm, :pagotit"
        . ",:persinsu, :porcen1, :porcen2, :porcen3, :porcen_it, :predecible, :proporcional, :red"
        . ",:redondeo, :reduccion, :reserva, :sindicato, :tipo_ilt, :tipo_inactividad, :turnos, :txtab, :it_contador_jano)";


$SqlUpdateAusencia = "update ausencias set "
        . " a22 = :a22, "
        . " absentismo = :absentismo, "
        . " afecta_revision = :afecta_revision, "
        . " ausenciasrptid = :ausenciasrptid, "
        . " ausrpt_codigo  = :ausrpt_codigo , "
        . " ausrpt_descripcion = :ausrpt_descripcion, "
        . " autog = :autog, "
        . " autog_desde = :autog_desde, "
        . " autog_hasta = :autog_hasta, "
        . " btc_tipocon  = :btc_tipocon , "
        . " calculo_ffin = :calculo_ffin, "
        . " cambiogrc = :cambiogrc, "
        . " cambiopuesto = :cambiopuesto, "
        . " cambiosgrc = :cambiosgrc, "
        . " codigonom  = :codigonom , "
        . " contador = :contador, "
        . " cotizass = :cotizass, "
        . " csituadm = :csituadm, "
        . " ctact = :ctact, "
        . " ctrl_horario = :ctrl_horario, "
        . " cuenta_pago = :cuenta_pago, "
        . " cuenta_turnic = :cuenta_turnic, "
        . " descrip = :descrip, "
        . " descu_trienios = :descu_trienios, "
        . " destino = :destino, "
        . " didesde1 = :didesde1, "
        . " didesde2 = :didesde2, "
        . " didesde3 = :didesde3, "
        . " dihasta1 = :dihasta1, "
        . " dihasta2 = :dihasta2, "
        . " dihasta3 = :dihasta3, "
        . " dtrab = :dtrab, "
        . " dtrabperm = :dtrabperm, "
        . " dur_reserva = :dur_reserva, "
        . " enuso = :enuso, "
        . " epiacc = :epiacc, "
        . " excluir_plpage = :excluir_plpage, "
        . " fco = :fco, "
        . " fin_red = :fin_red, "
        . " guarda = :guarda, "
        . " huelga = :huelga, "
        . " idbasescon = :idbasescon, "
        . " itinerancia = :itinerancia, "
        . " justificante_dias = :justificante_dias, "
        . " justificar = :justificar, "
        . " mapturnos = :mapturnos, "
        . " max_anual = :max_anual, "
        . " max_anual_h = :max_anual_h, "
        . " max_total = :max_total, "
        . " max_total_h = :max_total_h, "
        . " mejora_it = :mejora_it, "
        . " modocupa = :modocupa, "
        . " patronal = :patronal, "
        . " naturales = :naturales, "
        . " naturales_ev = :naturales_ev, "
        . " ocupacion = :ocupacion, "
        . " ocupacion_new = :ocupacion_new, "
        . " otrosperm = :otrosperm, "
        . " pagotit = :pagotit, "
        . " persinsu = :persinsu, "
        . " porcen1 = :porcen1, "
        . " porcen2 = :porcen2, "
        . " porcen3 = :porcen3, "
        . " porcen_it = :porcen_it, "
        . " predecible = :predecible, "
        . " proporcional = :proporcional, "
        . " red = :red, "
        . " redondeo = :redondeo, "
        . " reduccion = :reduccion, "
        . " reserva = :reserva, "
        . " sindicato = :sindicato, "
        . " tipo_ilt = :tipo_ilt, "
        . " tipo_inactividad = :tipo_inactividad, "
        . " turnos = :turnos, "
        . " txtab = :txtab, "
        . " it_contador_jano = :it_contador_jano "
        . " where codigo = :codigo";

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
$ausencia_id = $argv[2];
$actuacion = $argv[3];
$eqausencia_id = $argv[4];

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

$Ausencia = selectAusenciaById($ausencia_id);

if (!$Ausencia) {
    echo "*** ERROR EN AUSENCIA NO EXISTE ID = " . $ausencia_id . "\n";
    exit(1);
}

echo " ==> SINCRONIZACIÓN AUSENCIA : ID= (" . $Ausencia["id"] . ")"
 . " CÓDIGO= (" . $Ausencia["codigo"] . ")"
 . " DESCRIPCIÓN= (" . $Ausencia["descrip"] . ")"
 . " ACTUACION= (" . $actuacion . ")"
 . " EQAUSENCIA_ID= (" . $eqausencia_id . ")"
 . "\n";

if ($actuacion == 'INSERT') {
    if (!procesoInsert($Ausencia)) {
        exit(1);
    }
}

if ($actuacion == 'UPDATE') {
    if (!procesoUpdate($Ausencia)) {
        exit(1);
    }
}

echo " FIN SINCRONIZACIÓN AUSENCIA" . "\n";
exit(0);