<?php

include_once __DIR__ . '/funcionesDAO.php';

function selectEqAusencias($codigo) {
    global $connInte;
    try {
        $sentencia = " select * from eq_ausencias where codigo_uni = :codigo";
        $query = $connInte->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetchALL(PDO::FETCH_ASSOC);
        return $res;
    } catch (PDOException $ex) {
        echo "** ERROR EN SELECT EQ_AUSENCIAS CODIGO= " . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function selectJanoMaePer($codigoSaint) {
    global $connUnif;
    try {
        $sentencia = " select t1.* from jano_maeper as t1 "
            ."inner join jano_equper as t2 on t1.codigo = t2.cod_maeper "
            ."where t2.cod_saint = :codigoSaint";
        $query = $connUnif->prepare($sentencia);
        $params = array(":codigoSaint" => $codigoSaint);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        return $res;
    } catch (PDOException $ex) {
        echo "** ERROR EN SELECT JANO_MAEPER CODIGO= " . $codigoSaint . " " . $ex->getMessage() . "\n";
        return null;
    }
    
}
/*
 * Cuerpo Principal 
 */
echo " -- CARGA INICIAL TABLA: GUMS_AUSENCIAS " . "\n";
$JanoControl = connGums();
if (!$JanoControl) {
    exit(111);
}

/*
 * recogemos el parametro para ver si estamos en pruebas en validación o en producción
 */
$tipo = $argv[1];

if ($tipo == 'REAL') {
    echo " ENTORNO = PRODUCCIÓN \n";
    $connInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $connUnif = conexionPDO(SelectBaseDatos(2, 'U'));
} else {
    echo " ENTORNO = VALIDACIÓN \n";
    $connInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $connUnif = conexionPDO(SelectBaseDatos(1, 'U'));
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
    $query = $connUnif->prepare($sentencia);
    $query->execute();
    $ausenciasAll = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo "***PDOERROR EN LA SELECT DE AUSENCIAS BASE DE DATOS UNIFICADA " . $ex->getMessage() . "\n";
    exit(1);
}

echo " Registros a Cargar = " . count($ausenciasAll) . "\n";
foreach ($ausenciasAll as $ausencia) {
    $yaExiste = selectAusenciaByCodigo($ausencia["CODIGO"]);
    if (!$yaExiste) {
        try {
            $janoMaePer = selectJanoMaePer($ausencia["CODIGO"]);
            $sentencia = " insert into gums_ausencias "
                    ."(a22, absentismo, afecta_revision, ausenciasrptid, ausrpt_codigo "
                    .",ausrpt_descripcion, autog, autog_desde, autog_hasta, btc_tipocon "
                    .",calculo_ffin, cambiogrc, cambiopuesto, cambiosgrc, codigo, codigonom "
                    .",contador, cotizass, csituadm, ctact, ctrl_horario, cuenta_pago, cuenta_turnic"
                    .",descrip, descu_trienios, destino, didesde1, didesde2, didesde3, dihasta1, dihasta2"
                    .",dihasta3, dtrab, dtrabperm, dur_reserva, enuso, epiacc_id, excluir_plpage, fco_id"
                    .",fin_red, guarda, huelga, idbasescon, itinerancia, justificante_dias, justificar"
                    .",mapturnos, max_anual, max_anual_h, max_total, max_total_h, mejora_it, modocupa_id"
                    .",movipat_id, naturales, naturales_ev, ocupacion_id, ocupacion_new_id, otrosperm, pagotit"
                    .",persinsu, porcen1, porcen2, porcen3, porcen_it, predecible, proporcional, red"
                    .",redondeo, reduccion, reserva, sindicato, tipo_ilt_id, tipo_inactividad, turnos, txtab, es_it"
                    .",jano_apartado,jano_apd,jano_codigo,jano_descripcion,jano_descripseg, jano_nombrelargo,jano_dldold"
                    .",jano_varold, jano_en_horas, jano_feclimant, jano_grado, jano_grautoriza, jano_responsable, jano_usuario"
                    .",jano_resto, jano_localidad, jano_maxadelanto, jano_maxlab, jano_maxnat, jano_mir, jano_sar, jano_suma_dias_cont" 
                    .",jano_suma_dias_disc ) "
                    ." values "
                    ."(:a22,:absentismo,:afecta_revision,:ausenciasrptid,:ausrpt_codigo"
                    .",:ausrpt_descripcion,:autog,:autog_desde,:autog_hasta,:btc_tipocon"
                    .",:calculo_ffin,:cambiogrc,:cambiopuesto,:cambiosgrc,:codigo,:codigonom"
                    .",:contador,:cotizass,:csituadm,:ctact,:ctrl_horario,:cuenta_pago,:cuenta_turnic"
                    .",:descrip,:descu_trienios,:destino,:didesde1,:didesde2,:didesde3,:dihasta1,:dihasta2"
                    .",:dihasta3,:dtrab,:dtrabperm,:dur_reserva,:enuso,:epiacc_id,:excluir_plpage,:fco_id"
                    .",:fin_red,:guarda,:huelga,:idbasescon,:itinerancia,:justificante_dias,:justificar"
                    .",:mapturnos,:max_anual,:max_anual_h,:max_total,:max_total_h,:mejora_it,:modocupa_id"
                    .",:movipat_id,:naturales,:naturales_ev,:ocupacion_id,:ocupacion_new_id,:otrosperm,:pagotit"
                    .",:persinsu,:porcen1,:porcen2,:porcen3,:porcen_it,:predecible,:proporcional,:red"
                    .",:redondeo,:reduccion,:reserva,:sindicato,:tipo_ilt_id,:tipo_inactividad,:turnos,:txtab, :es_it"
                    .",:jano_apartado, :jano_apd, :jano_codigo, :jano_descripcion, :jano_descripseg, :jano_nombrelargo, :jano_dldold"
                    .",:jano_varold, :jano_en_horas, :jano_feclimant, :jano_grado, :jano_grautoriza, :jano_responsable, :jano_usuario"
                    .",:jano_resto, :jano_localidad, :jano_maxadelanto, :jano_maxlab, :jano_maxnat, :jano_mir, :jano_sar, :jano_suma_dias_cont" 
                    .",:jano_suma_dias_disc ) ";
                    
            $query = $JanoControl->prepare($sentencia);
            if ($ausencia["TIPO_ILT"]==null ) {
                $ausencia["ES_IT"] = 'N';
            }else  {
                $ausencia["ES_IT"] = 'S';
            }
                
            $params = array(":a22" => $ausencia["A22"],
                            ":absentismo" => $ausencia["ABSENTISMO"],
                            ":afecta_revision" =>$ausencia["AFECTA_REVISION"],
                            ":ausenciasrptid" =>$ausencia["AUSENCIASRPTID"],
                            ":ausrpt_codigo" =>$ausencia["AUSRPT_CODIGO"],
                            ":ausrpt_descripcion" =>$ausencia["AUSRPT_DESCRIPCION"],
                            ":autog" =>$ausencia["AUTOG"],
                            ":autog_desde" =>$ausencia["AUTOG_DESDE"],
                            ":autog_hasta" =>$ausencia["AUTOG_HASTA"],
                            ":btc_tipocon" =>$ausencia["BTC_TIPOCON"],
                            ":calculo_ffin" =>$ausencia["CALCULO_FFIN"],
                            ":cambiogrc" =>$ausencia["CAMBIOGRC"],
                            ":cambiopuesto" =>$ausencia["CAMBIOPUESTO"],
                            ":cambiosgrc" =>$ausencia["CAMBIOSGRC"],
                            ":codigo" =>$ausencia["CODIGO"],
                            ":codigonom" =>$ausencia["CODIGONOM"],
                            ":contador" =>$ausencia["CONTADOR"],
                            ":cotizass" =>$ausencia["COTIZASS"],
                            ":csituadm" =>$ausencia["CSITUADM"],
                            ":ctact" =>$ausencia["CTACT"],
                            ":ctrl_horario" =>$ausencia["CTRL_HORARIO"],
                            ":cuenta_pago" =>$ausencia["CUENTA_PAGO"],
                            ":cuenta_turnic" =>$ausencia["CUENTA_TURNIC"],
                            ":descrip" =>$ausencia["DESCRIP"],
                            ":descu_trienios" =>$ausencia["DESCU_TRIENIOS"],
                            ":destino" =>$ausencia["DESTINO"],
                            ":didesde1" =>$ausencia["DIDESDE1"],
                            ":didesde2" =>$ausencia["DIDESDE2"],
                            ":didesde3" =>$ausencia["DIDESDE3"],
                            ":dihasta1" =>$ausencia["DIHASTA1"],
                            ":dihasta2" =>$ausencia["DIHASTA2"],
                            ":dihasta3" =>$ausencia["DIHASTA3"],
                            ":dtrab" =>$ausencia["DTRAB"],
                            ":dtrabperm" =>$ausencia["DTRABPERM"],
                            ":dur_reserva" =>$ausencia["DUR_RESERVA"],
                            ":enuso" =>$ausencia["ENUSO"],
                            ":excluir_plpage" =>$ausencia["EXCLUIR_PLPAGE"],
                            ":fin_red" =>$ausencia["FIN_RED"],
                            ":guarda" =>$ausencia["GUARDA"],
                            ":huelga" =>$ausencia["HUELGA"],
                            ":idbasescon" =>$ausencia["IDBASESCON"],
                            ":itinerancia" =>$ausencia["ITINERANCIA"],
                            ":justificante_dias" =>$ausencia["JUSTIFICANTE_DIAS"],
                            ":justificar" =>$ausencia["JUSTIFICAR"],
                            ":mapturnos" =>$ausencia["MAPTURNOS"],
                            ":max_anual" =>$ausencia["MAX_ANUAL"],
                            ":max_anual_h" =>$ausencia["MAX_ANUAL_H"],
                            ":max_total" =>$ausencia["MAX_TOTAL"],
                            ":max_total_h" =>$ausencia["MAX_TOTAL_H"],
                            ":mejora_it" =>$ausencia["MEJORA_IT"],
                            ":naturales" =>$ausencia["NATURALES"],
                            ":naturales_ev" =>$ausencia["NATURALES_EV"],
                            ":otrosperm" =>$ausencia["OTROSPERM"],
                            ":pagotit" =>$ausencia["PAGOTIT"],
                            ":persinsu" =>$ausencia["PERSINSU"],
                            ":porcen1" =>$ausencia["PORCEN1"],
                            ":porcen2" =>$ausencia["PORCEN2"],
                            ":porcen3" =>$ausencia["PORCEN3"],
                            ":porcen_it" =>$ausencia["PORCEN_IT"],
                            ":predecible" =>$ausencia["PREDECIBLE"],
                            ":proporcional" =>$ausencia["PROPORCIONAL"],
                            ":red" =>$ausencia["RED"],
                            ":redondeo" =>$ausencia["REDONDEO"],
                            ":reduccion" =>$ausencia["REDUCCION"],
                            ":reserva" =>$ausencia["RESERVA"],
                            ":sindicato" =>$ausencia["SINDICATO"],
                            ":tipo_inactividad" =>$ausencia["TIPO_INACTIVIDAD"],
                            ":turnos" =>$ausencia["TURNOS"],
                            ":txtab" =>$ausencia["TXTAB"],
                            ":ocupacion_id" =>  selectIdOcupacion($ausencia["OCUPACION"]),
                            ":ocupacion_new_id" =>  selectIdOcupacion($ausencia["OCUPACION_NEW"]),
                            ":modocupa_id" => selectIdModOcupa($ausencia["MODOCUPA"]),
                            ":movipat_id" => selectIdMoviPat($ausencia["PATRONAL"]),
                            ":tipo_ilt_id" => selectIdTipoIlt($ausencia["TIPO_ILT"]),
                            ":epiacc_id" => selectIdEpiAcc($ausencia["EPIACC"]),
                            ":fco_id" =>selectIdFco($ausencia["FCO"]),
                            ":es_it" =>$ausencia["ES_IT"],
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
                            ":jano_suma_dias_disc" => $janoMaePer["SUMA_DIAS_DISC"] );
                            
            
            $res = $query->execute($params);
            if ($res == 0) {
                echo "**ERROR EN INSERT GUMS_AUSENCIAS CODIGO= " . $ausencia["CODIGO"] . " \n";
                continue;
            }
            $ausencia["ID"] = $JanoControl->lastInsertId();
            echo " CREADA GUMS_AUSENCIA ID= " . $ausencia["ID"] . " CODIGO= " . $ausencia["CODIGO"] . " " . $ausencia["DESCRIP"] . "\n";

            $eqAusenciasAll = selectEqAusencias($ausencia["CODIGO"]);
            foreach ($eqAusenciasAll as $eqAusencias) {
                $edificio = selectEdificio($eqAusencias["EDIFICIO"]);
                try {
                    $sentencia = " insert into gums_eq_ausencias "
                            . " (edificio_id, codigo_loc, ausencia_id ) values  ( :edificio_id, :codigo_loc, :ausencia_id )";
                    $insert = $JanoControl->prepare($sentencia);
                    $params = array(":edificio_id" => $edificio["id"],
                        ":codigo_loc" => $eqAusencias["CODIGO_LOC"],
                        ":ausencia_id" => $ausencia["ID"]);
                    $res = $insert->execute($params);
                    if ($res == 0) {
                        echo "***ERROR EN INSERT GUMS_EQ_AUSENCIAS EDIFICIO= " . $edificio["id"] . " CODIGO_LOC = " . $eqAusenciasAll["CODIGO_LOC"] . "\n";
                    }
                    echo "GENERADA EQUIVALENCIA GUMS_EQ_AUSENCIAS EDIFICIO = " . $edificio["id"] . " CODIGO_LOC = " . $eqAusencias["CODIGO_LOC"] . "\n";
                } catch (PDOException $ex) {
                    echo "***PDOERROR EN INSERT GUMS_EQ_AUSENCIAS " . $ex->getMessage() . "\n";
                    continue;
                }
            }
        } catch (PDOException $ex) {
            echo "***PDOERROR EN INSERT GUMS_AUSENCIAS CODIGO= " . $ausencia["CODIGO"] . " " . $ex->getMessage() . "\n";
        }
    }
}

echo " TERMINADA LA CARGA DE GUMS_AUSENCIAS " . "\n";
exit(0);

