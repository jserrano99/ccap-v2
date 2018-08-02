<?php

include_once __DIR__ . '/funcionesDAO.php';

function selectEqAusencia($edificio, $codigo_uni) {
    global $connInte;
    try {
        $sentencia = " select * from eq_ausencias  "
                . "  where codigo_uni = :codigo_uni "
                . " and edificio = :edificio";
        $query = $connInte->prepare($sentencia);
        $params = array(":codigo_uni" => $codigo_uni,
            ":edificio" => $edificio);
        $query->execute($params);
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            //echo "** ERROR EN SELECT EQ_ALTAS CODIGO_UNI = " . $codigo_uni . " EDIFICIO= " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT EQ_AUSENCIAS CODIGO_UNI = " . $codigo_uni . " EDIFICIO= " . $edificio . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function deleteEqAusencia($Ausencia) {
    global $connInte;
    try {
        $sentencia = " delete from eq_ausencias where codigo_uni = :codigo_uni";
        $query = $connInte->prepare($sentencia);
        $params = array(":codigo_uni" => $Ausencia["codigo"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN DELETE EQ_AUSENCIAS CODIGO_UNI=" . $Ausencia["codigo"] . "\n";
        }
        echo "DELETE EQ_AUSENCIAS CODIGO_UNI=" . $Ausencia["codigo"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN DELETE EQ_AUSENCIAS EDIFICIO= " . $i
        . " CODIGO_LOC= " . $Ausencia["codigo"]
        . " CODIGO_UNI=" . $Ausencia["codigo"]
        . " \t " . $ex->getMessage()
        . " \n";
    }
}

function insertEqAusencia($Ausencia) {
    global $connInte;
    for ($i = 0; $i < 12; $i++) {
        try {
            $sentencia = "insert into eq_ausencias "
                    . " (edificio, codigo_loc, codigo_uni) "
                    . " values "
                    . " (:edificio, :codigo_loc, :codigo_uni) ";
            $query = $connInte->prepare($sentencia);
            $params = array(":edificio" => $i,
                ":codigo_loc" => $Ausencia["codigo"],
                ":codigo_uni" => $Ausencia["codigo"]);
            $res = $query->execute($params);
            if ($res == 0) {
                echo "**ERROR EN INSERT EQ_AUSENCIAS EDIFICIO= " . $i . " CODIGO_LOC= " . $Ausencia["codigo"] . " CODIGO_UNI=" . $Ausencia["codigo"] . "\n";
            }
            echo "INSERT EQ_AUSENCIAS EDIFICIO= " . $i . " CODIGO_LOC= " . $Ausencia["codigo"] . " CODIGO_UNI=" . $Ausencia["codigo"] . "\n";
        } catch (PDOException $ex) {
            echo "**PDOERROR EN INSERT EQ_AUSENCIAS EDIFICIO= " . $i
            . " CODIGO_LOC= " . $Ausencia["codigo"]
            . " CODIGO_UNI=" . $Ausencia["codigo"]
            . " \t  " . $ex->getMessage()
            . " \n";
        }
    }
}

function procesoInsert($Ausencia) {
    global $BasesDatos, $connUnif;
    if (!insertAusencia($connUnif, $Ausencia))
        exit(1);
    if ($Ausencia["jano_codigo"] != null) {
        if (!insertJanoMaePer($connUnif, $Ausencia))
            exit(1);
        if (!insertJanoEquPer($connUnif, $Ausencia))
            exit(1);
    }

    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        insertAusencia($conexion, $Ausencia);
    }
    insertEqAusencia($Ausencia);
}

function procesoDelete($Ausencia) {
    global $BasesDatos, $connUnif;
    deleteAusencia($connUnif, $Ausencia["codigo"]);
    deleteJanoMaePer($Ausencia["jano_codigo"]);
    deleteJanoEquPer($Ausencia["jano_codigo"]);
    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        $EqAusencia = selectEqAusencia($baseDatos["edificio"], $Ausencia["codigo"]);
        if ($EqAusencia) {
            foreach ($EqAusencia as $linea) {
                deleteAusencia($conexion, $linea["CODIGO_LOC"]);
            }
        }
    }
    deleteEqAusencia($Ausencia);
}

function procesoUpdate($Ausencia) {
    global $BasesDatos, $connUnif;

    if (!updateAusencia($connUnif, $Ausencia, $Ausencia["codigo"]))
        exit(1);

    if ($Ausencia["jano_codigo"] != null) {
        if (!updateJanoMaePer($connUnif, $Ausencia))
            exit(1);
    }

    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        $EqAusencia = selectEqAusencia($baseDatos["edificio"], $Ausencia["codigo"]);
        if ($EqAusencia) {
            foreach ($EqAusencia as $linea) {
                updateAusencia($conexion, $Ausencia, $linea["CODIGO_LOC"]);
            }
        }
    }
}

function insertAusencia($conexion, $Ausencia) {
    try {
        $sentencia = " insert into ausencias "
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
                . ",redondeo, reduccion, reserva, sindicato, tipo_ilt, tipo_inactividad, turnos, txtab)"
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
                . ",:redondeo, :reduccion, :reserva, :sindicato, :tipo_ilt, :tipo_inactividad, :turnos, :txtab)";

        $query = $conexion->prepare($sentencia);
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
            ":redondeo" => $Ausencia["redondeo"],
            ":reduccion" => $Ausencia["reduccion"],
            ":reserva" => ($Ausencia["reserva"] == null) ? 'N' : $Ausencia["reserva"],
            ":sindicato" => $Ausencia["sindicato"],
            ":tipo_ilt" => $Ausencia["tipo_ilt"],
            ":tipo_inactividad" => $Ausencia["tipo_inactividad"],
            ":turnos" => $Ausencia["turnos"],
            ":txtab" => $Ausencia["txtab"]);
        //var_dump($params);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
            return null;
        }
        echo "INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"] . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT AUSENCIAS CODIGO= " . $Ausencia["codigo"]
        . " DESCRIPCION= " . $Ausencia["descrip"] . "  " . $ex->getMessage() . "\n";
        return null;
    }
}

function insertJanoEquPer($conexion, $Ausencia) {
    try {
        $sentencia = " insert into jano_equper "
                . "( cod_saint, cod_maeper, sar, principal ) "
                . " values "
                . "( :cod_saint, :cod_maeper, :sar, :principal ) ";
        $query = $conexion->prepare($sentencia);
        $params = array(":cod_saint" => $Ausencia["codigo"],
            ":cod_maeper" => $Ausencia["jano_codigo"],
            ":sar" => $Ausencia["jano_sar"],
            ":principal" => "S");

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT JANO_EQUPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
            return null;
        }
        echo "INSERT JANO_EQUPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT JANO_EQUPER CODIGO= " . $Ausencia["jano_codigo"]
        . " DESCRIPCION= " . $Ausencia["jano_descripcion"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

function insertJanoMaePer($conexion, $Ausencia) {
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

        $query = $conexion->prepare($sentencia);
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
        echo "INSERT JANO_MAEPER CODIGO= " . $Ausencia["jano_codigo"] . " DESCRIPCION= " . $Ausencia["jano_descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT JANO_MAEPER CODIGO= " . $Ausencia["jano_codigo"]
        . " DESCRIPCION= " . $Ausencia["jano_descripcion"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

function updateAusencia($conexion, $Ausencia, $codigo) {
    try {
        $sentencia = "update ausencias set "
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
                . " txtab = :txtab "
                . " where codigo = :codigo";
        $query = $conexion->prepare($sentencia);

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
            ":codigo" => $codigo,
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
            ":redondeo" => $Ausencia["redondeo"],
            ":reduccion" => $Ausencia["reduccion"],
            ":reserva" => ($Ausencia["reserva"] == null) ? 'N' : $Ausencia["reserva"],
            ":sindicato" => $Ausencia["sindicato"],
            ":tipo_ilt" => $Ausencia["tipo_ilt"],
            ":tipo_inactividad" => $Ausencia["tipo_inactividad"],
            ":turnos" => $Ausencia["turnos"],
            ":txtab" => $Ausencia["txtab"]);

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE AUSENCIAS CODIGO= " . $codigo . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
            return null;
        }
        echo "UPDATE AUSENCIAS CODIGO= " . $codigo . " DESCRIPCION= " . $Ausencia["descrip"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE AUSENCIAS CODIGO= " . $codigo
        . " DESCRIPCION= " . $Ausencia["descrip"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

function updateJanoMaePer($conexion, $Ausencia) {
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


        $query = $conexion->prepare($sentencia);
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

function deleteAusencia($conexion, $codigo) {
    try {
        $sentencia = " delete from ausencias where codigo = :codigo";

        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN DELETE AUSENCIAS CODIGO= " . $codigo . "\n";
            return null;
        }
        echo "DELETE AUSENCIAS CODIGO= " . $codigo . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN DELETE AUSENCIAS CODIGO= " . $codigo . "    " . $ex->getMessage() . "\n";
        return null;
    }
}

function deleteJanoMaePer($codigo) {
    global $connUnif;
    try {
        $sentencia = " delete from jano_maeper where codigo = :codigo";

        $query = $connUnif->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN DELETE JANO_MAEPER CODIGO= " . $codigo . "\n";
            return null;
        }
        echo "DELETE JANO_MAEPER CODIGO= " . $codigo . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN DELETE JANO_MAEPER CODIGO= " . $codigo . "    " . $ex->getMessage() . "\n";
        return null;
    }
}

function deleteJanoEquPer($codigo) {
    global $connUnif;
    try {
        $sentencia = " delete from jano_equper where cod_maeper = :codigo";

        $query = $connUnif->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN DELETE JANO_EQUPER CODIGO= " . $codigo . "\n";
            return null;
        }
        echo "DELETE JANO_EQUPER CODIGO= " . $codigo . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN DELETE JANO_EQUPER CODIGO= " . $codigo . "    " . $ex->getMessage() . "\n";
        return null;
    }
}

echo " ++++++ COMIENZA PROCESO SINCRONIZACIÓN AUSENCIAS +++++++++++ \n";
/*
 * Conexión a la base de datos de Control en Mysql 
 */
$JanoControl = connGums();
if (!$JanoControl) {
    exit(1);
}

/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];
$ausencia_id = $argv[2];
$accion = $argv[3];

if ($tipo == 'REAL') {
    echo " ENTORNO = PRODUCCIÓN \n";
    $connInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $connUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $BasesDatos = selectBaseDatosAreas(2);
} else {
    echo " ENTORNO = VALIDACIÓN \n";
    $connInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $connUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $BasesDatos = selectBaseDatosAreas(1);
}

$Ausencia = selectAusenciaById($ausencia_id);

if (!$Ausencia) {
    echo "*** ERROR EN AUSENCIA NO EXISTE ID = " . $ausencia_id . "\n";
    exit(1);
}

echo " SINCRONIZACIÓN AUSENCIA : ID=" . $Ausencia["id"]
 . " CÓDIGO=" . $Ausencia["codigo"]
 . " DESCRIPCIÓN= " . $Ausencia["descrip"]
 . " ACCION =" . $accion
 . "\n";

if ($accion == 'DELETE') {
    if (!procesoDelete($Ausencia)) {
        exit(1);
    }
}

if ($accion == 'INSERT') {
    if (!procesoInsert($Ausencia)) {
        exit(1);
    }
}

if ($accion == 'UPDATE') {
    if (!procesoUpdate($Ausencia)) {
        exit(1);
    }
}

echo " FIN SINCRONIZACIÓN AUSENCIA" . "\n";
exit(0);
