<?php

include_once __DIR__ . '../../funcionesDAO.php';

function insertEqConceptosInte($edificio, $codigo_uni, $codigo_loc) {
    global $JanoInte;
    try {
        $sentencia = " insert into eq_conceptos (edificio, codigo_uni, codigo_loc ) values (:edificio, :codigo_uni, :codigo_loc )";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $edificio,
            ":codigo_uni" => $codigo_uni,
            "codigo_loc" => $codigo_loc);
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "***ERROR EN INSERT EQ_CONCEPTOS EDIFICIO= (" . $edificio . ") CODIGO_UNI= (" . $codigo_uni . ") CODIGO_LOC= (" . $codigo_loc . ") \n";
            return null;
        }
        echo "INSERT EQ_CONCEPTOS EDIFICIO= (" . $edificio . ") CODIGO_UNI= (" . $codigo_uni . ") CODIGO_LOC= (" . $codigo_loc . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT EQ_CONCEPTOS EDIFICIO= (" . $edificio . ") CODIGO_UNI= (" . $codigo_uni . ") CODIGO_LOC= (" . $codigo_loc . ") ERROR = " .
        $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoControl
 * @return type
 */
function selectCargaConceptos() {
    global $JanoControl;

    try {
        $sentencia = " select * from gums_carga_conceptos";
        $query = $JanoControl->prepare($sentencia);
        $query->execute();
        $CargaConceptoAll = $query->fetchAll(PDO::FETCH_ASSOC);
        return $CargaConceptoAll;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN GUMS_CARGA_CONCEPTOS " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoUnif
 * @global type $JanoInte
 * @global type $JanoControl
 * @return boolean
 */
function inicializaTablas() {
    global $JanoUnif, $JanoInte, $JanoControl;
    /**
     * Borramos los conceptos de la base de datos unificada 
     */
    try {
        $sentencia = " delete from eq_conceptos";
        $query = $JanoInte->prepare($sentencia);
        $res = $query->execute();
        var_dump($res);
        var_dump($query);
    } catch (PDOException $ex) {
        echo "***PDOERROR EN DELETE EQ_CONCEPTOS " . $ex->getMessage() . " BASE DE DATOS INTERMEDIA \n";
        return null;
    }
    try {
        $sentencia = " delete from concepto";
        $query = $JanoUnif->prepare($sentencia);
        $query->execute();
        var_dump($query);
    } catch (PDOException $ex) {
        echo "***PDOERROR EN DELETE CONCEPTOS " . $ex->getMessage() . " BASE DE DATOS UNIFICADA \n";
        return null;
    }

    try {
        $sentencia = " delete from gums_eq_conceptos";
        $query = $JanoControl->prepare($sentencia);
        $query->execute();
        var_dump($query);
    } catch (PDOException $ex) {
        echo "***PDOERROR EN DELETE GUMS_EQ_CONCEPTOS " . $ex->getMessage() . "\n";
        return null;
    }

    try {
        $sentencia = " delete from gums_conceptos";
        $query = $JanoControl->prepare($sentencia);
        $query->execute();
        var_dump($query);
    } catch (PDOException $ex) {
        echo "***PDOERROR EN DELETE GUMS_CONCEPTOS " . $ex->getMessage() . "\n";
        return null;
    }

    return true;
}

function selectConceptoUnif($codigo) {
    global $JanoUnif;
    try {
        $sentencia = " select * from concepto where codigo = :codigo";
        $query = $JanoUnif->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        }
        return null;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT CONCEPTO BASE DE DATOS UNIFICADA CODIGO =(" . $codigo_loc . ") ERROR= " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoControl
 * @param type $codigo
 * @return type
 */
function selectConceptoByCodigo($codigo) {
    global $JanoControl;
    try {
        $sentencia = " select * from gums_conceptos where codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        }
        return null;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT GUMS_CONCEPTOS CODIGO =(" . $codigo_loc . ") ERROR= " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @param type $conexion
 * @param type $codigo_loc
 * @return type
 */
function selectConceptoOrigen($conexion, $codigo_loc) {
    try {
        $sentencia = " select * from concepto where codigo = :codigo";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $codigo_loc);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        }
        echo "**ERROR NO EXISTE CONCEPTO CODIGO =(" . $codigo_loc . ") EN LA BASE DE DATOS \n";
        return null;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT CONCEPTOS CODIGO =(" . $codigo_loc . ") ERROR= " . $ex->getMessage() . "\n";
        return null;
    }
}

function insertEqConceptoControl($EqConcepto) {
    global $JanoControl;
    try {
        $sentencia = " insert into gums_eq_conceptos "
                . " (edificio_id, codigo_loc, concepto_id, enuso ) values  ( :edificio_id, :codigo_loc, :concepto_id, :enuso )";
        $insert = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqConcepto["edificio_id"],
            ":codigo_loc" => $EqConcepto["codigo_loc"],
            ":concepto_id" => $EqConcepto["concepto_id"],
            ":enuso" => $EqConcepto["enuso"]);
        $res = $insert->execute($params);
        if ($res == 0) {
            echo "***ERROR EN INSERT GUMS_EQ_CONCEPTOS CONCEPTO_ID = (" . $EqConcepto["concepto_id"] . ")"
            . "EDIFICIO= (" . $EqConcepto["edificio"] . ") CODIGO_LOC= (" . $EqConcepto["codigo_loc"] . ") Uso= (" . $EqConcepto["enuso"] . ") \n";
        }
        echo "GENERADA EQUIVALENCIA GUMS_EQ_CONCEPTOS CODIGO_UNI = (" . $EqConcepto["codigo_uni"] . ")"
        . " EDIFICIO= (" . $EqConcepto["edificio"] . ") CODIGO_LOC= (" . $EqConcepto["codigo_loc"] . ") Uso= (" . $EqConcepto["enuso"] . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN INSERT GUMS_EQ_CONCEPTOS " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * 
 * @global type $JanoUnif
 * @global type $tipobd
 * @param type $CargaConcepto
 * @return type
 */
function insertConceptoUnif($CargaConcepto) {
    global $JanoUnif, $tipobd;

    $conexion = conexionEdificio($CargaConcepto["area_origen"], $tipobd);
    IF ($conexion == null) {
        return null;
    }
    $ConceptoOrigen = selectConceptoOrigen($conexion, $CargaConcepto["codigo_loc"]);

//    var_dump($ConceptoOrigen);
//    die();
    if ($ConceptoOrigen == "") {
        return null;
    }

    try {
        $sentencia = " insert into concepto "
                . " ( codigo,descrip, irpf, segsoc, extra, vacac, importe, tipo, acum, incre, huelga, devengo, clave190, tipo_concepto "
                . " , cupo_acu, cupo_cd, ret_judicial, trieniocupo, recupera_it, porcentaje_extra, gasto173, mayor_carga, mayor_carga_grc "
                . " , sabados, variable_irpf, mejora_it, cobraenextra, conceptorpt_codigo, conrpt_descripcion, conceptorptid, porcen_extra_ant "
                . " , exc_retencion, variable_decre, integro_mit, salario, complemento, at_continuada, turnicidad, descuenta_it, codigocre "
                . " , enespecie, reduccion, ceco_concepto, it190, rbmuface, rbmuface2, descanso ) values ( "
                . "  :codigo, :descrip, :irpf, :segsoc, :extra, :vacac, :importe, :tipo, :acum, :incre, :huelga, :devengo, :clave190, :tipo_concepto "
                . " , :cupo_acu, :cupo_cd, :ret_judicial, :trieniocupo, :recupera_it, :porcentaje_extra, :gasto173, :mayor_carga, :mayor_carga_grc "
                . " , :sabados, :variable_irpf, :mejora_it, :cobraenextra, :conceptorpt_codigo, :conrpt_descripcion, :conceptorptid, :porcen_extra_ant "
                . " , :exc_retencion, :variable_decre, :integro_mit, :salario, :complemento, :at_continuada, :turnicidad, :descuenta_it, :codigocre "
                . " , :enespecie, :reduccion, :ceco_concepto, :it190, :rbmuface, :rbmuface2, :descanso )";

        $query = $JanoUnif->prepare($sentencia);

        $params = array(":codigo" => $CargaConcepto["codigo_uni"],
            ":descrip" => $ConceptoOrigen["DESCRIP"],
            ":irpf" => $ConceptoOrigen["IRPF"],
            ":segsoc" => $ConceptoOrigen["SEGSOC"],
            ":extra" => $ConceptoOrigen["EXTRA"],
            ":vacac" => $ConceptoOrigen["VACAC"],
            ":importe" => $ConceptoOrigen["IMPORTE"],
            ":tipo" => $ConceptoOrigen["TIPO"],
            ":acum" => $ConceptoOrigen["ACUM"],
            ":incre" => $ConceptoOrigen["INCRE"],
            ":huelga" => $ConceptoOrigen["HUELGA"],
            ":devengo" => $ConceptoOrigen["DEVENGO"],
            ":clave190" => $ConceptoOrigen["CLAVE190"],
            ":tipo_concepto" => $ConceptoOrigen["TIPO_CONCEPTO"],
            ":cupo_acu" => $ConceptoOrigen["CUPO_ACU"],
            ":cupo_cd" => $ConceptoOrigen["CUPO_CD"],
            ":ret_judicial" => $ConceptoOrigen["RET_JUDICIAL"],
            ":trieniocupo" => $ConceptoOrigen["TRIENIOCUPO"],
            ":recupera_it" => $ConceptoOrigen["RECUPERA_IT"],
            ":porcentaje_extra" => $ConceptoOrigen["PORCENTAJE_EXTRA"],
            ":gasto173" => $ConceptoOrigen["GASTO173"],
            ":mayor_carga" => $ConceptoOrigen["MAYOR_CARGA"],
            ":mayor_carga_grc" => $ConceptoOrigen["MAYOR_CARGA_GRC"],
            ":sabados" => $ConceptoOrigen["SABADOS"],
            ":variable_irpf" => $ConceptoOrigen["VARIABLE_IRPF"],
            ":mejora_it" => $ConceptoOrigen["MEJORA_IT"],
            ":cobraenextra" => $ConceptoOrigen["COBRAENEXTRA"],
            ":conceptorpt_codigo" => $ConceptoOrigen["CONCEPTORPT_CODIGO"],
            ":conrpt_descripcion" => $ConceptoOrigen["CONRPT_DESCRIPCION"],
            ":conceptorptid" => $ConceptoOrigen["CONCEPTORPTID"],
            ":porcen_extra_ant" => $ConceptoOrigen["PORCEN_EXTRA_ANT"],
            ":exc_retencion" => $ConceptoOrigen["EXC_RETENCION"],
            ":variable_decre" => $ConceptoOrigen["VARIABLE_DECRE"],
            ":integro_mit" => $ConceptoOrigen["INTEGRO_MIT"],
            ":salario" => $ConceptoOrigen["SALARIO"],
            ":complemento" => $ConceptoOrigen["COMPLEMENTO"],
            ":at_continuada" => $ConceptoOrigen["AT_CONTINUADA"],
            ":turnicidad" => $ConceptoOrigen["TURNICIDAD"],
            ":descuenta_it" => $ConceptoOrigen["DESCUENTA_IT"],
            ":codigocre" => $ConceptoOrigen["CODIGOCRE"],
            ":enespecie" => $ConceptoOrigen["ENESPECIE"],
            ":reduccion" => $ConceptoOrigen["REDUCCION"],
            ":ceco_concepto" => $ConceptoOrigen["CECO_CONCEPTO"],
            ":it190" => $ConceptoOrigen["IT190"],
            ":rbmuface" => $ConceptoOrigen["RBMUFACE"],
            ":rbmuface2" => $ConceptoOrigen["RBMUFACE2"],
            ":descanso" => $ConceptoOrigen["DESCANSO"] == null ?: 'N'
        );

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT CONCEPTOS CODIGO= " . $ConceptoOrigen["CODIGO"] . " BASE DE DATOS UNIFICADA \n";
            return null;
        }
        $ConceptoOrigen["ID"] = $JanoUnif->lastInsertId();
        echo " CREADO CONCEPTOS ID= " . $ConceptoOrigen["ID"] . " CODIGO= (" . $ConceptoOrigen["CODIGO"] . ") DESCRIPCION= ( " . $ConceptoOrigen["DESCRIP"] . ") \n";
        return $ConceptoOrigen;
    } catch (PDOException $ex) {
        echo "**ERROR EN INSERT CONCEPTOS CODIGO= " . $ConceptoOrigen["CODIGO"] . $ex->getMessage() . " \n";
        return null;
    }
}

/**
 * 
 * @global type $JanoControl
 * @param type $Concepto
 * @return type
 */
function insertConceptoControl($CargaConcepto) {
    global $JanoControl;

    $ConceptoOrigen = selectConceptoUnif($CargaConcepto["codigo_uni"]);

    if ($ConceptoOrigen == "") {
        return null;
    }

    try {
        $sentencia = " insert into gums_conceptos "
                . " ( codigo,descrip, irpf, segsoc, extra, vacac, importe, tipo, acum, incre, huelga, devengo, clave190, tipo_concepto "
                . " , cupo_acu, cupo_cd, ret_judicial, trieniocupo, recupera_it, porcentaje_extra, gasto173, mayor_carga, mayor_carga_grc "
                . " , sabados, variable_irpf, mejora_it, cobraenextra, conceptorpt_codigo, conrpt_descripcion, conceptorptid, porcen_extra_ant "
                . " , exc_retencion, variable_decre, integro_mit, salario, complemento, at_continuada, turnicidad, descuenta_it, codigocre "
                . " , enespecie, reduccion, ceco_concepto, it190, rbmuface, rbmuface2, descanso ) values ( "
                . "  :codigo, :descrip, :irpf, :segsoc, :extra, :vacac, :importe, :tipo, :acum, :incre, :huelga, :devengo, :clave190, :tipo_concepto "
                . " , :cupo_acu, :cupo_cd, :ret_judicial, :trieniocupo, :recupera_it, :porcentaje_extra, :gasto173, :mayor_carga, :mayor_carga_grc "
                . " , :sabados, :variable_irpf, :mejora_it, :cobraenextra, :conceptorpt_codigo, :conrpt_descripcion, :conceptorptid, :porcen_extra_ant "
                . " , :exc_retencion, :variable_decre, :integro_mit, :salario, :complemento, :at_continuada, :turnicidad, :descuenta_it, :codigocre "
                . " , :enespecie, :reduccion, :ceco_concepto, :it190, :rbmuface, :rbmuface2, :descanso )";

        $query = $JanoControl->prepare($sentencia);

        $params = array(":codigo" => $ConceptoOrigen["CODIGO"],
            ":descrip" => $ConceptoOrigen["DESCRIP"],
            ":irpf" => $ConceptoOrigen["IRPF "],
            ":segsoc" => $ConceptoOrigen["SEGSOC"],
            ":extra" => $ConceptoOrigen["EXTRA"],
            ":vacac" => $ConceptoOrigen["VACAC"],
            ":importe" => $ConceptoOrigen["IMPORTE"],
            ":tipo" => $ConceptoOrigen["TIPO"],
            ":acum" => $ConceptoOrigen["ACUM"],
            ":incre" => $ConceptoOrigen["INCRE"],
            ":huelga" => $ConceptoOrigen["HUELGA"],
            ":devengo" => $ConceptoOrigen["DEVENGO"],
            ":clave190" => $ConceptoOrigen["CLAVE190"],
            ":tipo_concepto" => $ConceptoOrigen["TIPO_CONCEPTO"],
            ":cupo_acu" => $ConceptoOrigen["CUPO_ACU"],
            ":cupo_cd" => $ConceptoOrigen["CUPO_CD"],
            ":ret_judicial" => $ConceptoOrigen["RET_JUDICIAL"],
            ":trieniocupo" => $ConceptoOrigen["TRIENIOCUPO"],
            ":recupera_it" => $ConceptoOrigen["RECUPERA_IT"],
            ":porcentaje_extra" => $ConceptoOrigen["PORCENTAJE_EXTRA"],
            ":gasto173" => $ConceptoOrigen["GASTO173"],
            ":mayor_carga" => $ConceptoOrigen["MAYOR_CARGA"],
            ":mayor_carga_grc" => $ConceptoOrigen["MAYOR_CARGA_GRC"],
            ":sabados" => $ConceptoOrigen["SABADOS"],
            ":variable_irpf" => $ConceptoOrigen["VARIABLE_IRPF"],
            ":mejora_it" => $ConceptoOrigen["MEJORA_IT"] == null ?: "N",
            ":cobraenextra" => $ConceptoOrigen["COBRAENEXTRA"],
            ":conceptorpt_codigo" => $ConceptoOrigen["CONCEPTORPT_CODIGO"],
            ":conrpt_descripcion" => $ConceptoOrigen["CONRPT_DESCRIPCION"],
            ":conceptorptid" => $ConceptoOrigen["CONCEPTORPTID"],
            ":porcen_extra_ant" => $ConceptoOrigen["PORCEN_EXTRA_ANT"],
            ":exc_retencion" => $ConceptoOrigen["EXC_RETENCION"],
            ":variable_decre" => $ConceptoOrigen["VARIABLE_DECRE"] == null ?: "N",
            ":integro_mit" => $ConceptoOrigen["INTEGRO_MIT"],
            ":salario" => $ConceptoOrigen["SALARIO"],
            ":complemento" => $ConceptoOrigen["COMPLEMENTO"],
            ":at_continuada" => $ConceptoOrigen["AT_CONTINUADA"],
            ":turnicidad" => $ConceptoOrigen["TURNICIDAD"],
            ":descuenta_it" => $ConceptoOrigen["DESCUENTA_IT"],
            ":codigocre" => $ConceptoOrigen["CODIGOCRE"],
            ":enespecie" => $ConceptoOrigen["ENESPECIE"],
            ":reduccion" => $ConceptoOrigen["REDUCCION"],
            ":ceco_concepto" => $ConceptoOrigen["CECO_CONCEPTO"],
            ":it190" => $ConceptoOrigen["IT190"],
            ":rbmuface" => $ConceptoOrigen["RBMUFACE"],
            ":rbmuface2" => $ConceptoOrigen["RBMUFACE2"],
            ":descanso" => $ConceptoOrigen["DESCANSO"]
        );

        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT GUMS_CONCEPTOS CODIGO= " . $ConceptoOrigen["CODIGO"] . " \n";
            return null;
        }
        $ConceptoOrigen["ID"] = $JanoControl->lastInsertId();
        echo " CREADO GUMS_CONCEPTOS ID= " . $ConceptoOrigen["ID"] . " CODIGO= " . $ConceptoOrigen["CODIGO"] . " " . $ConceptoOrigen["DESCRIP"] . "\n";
        return $ConceptoOrigen["ID"];
    } catch (PDOException $ex) {
        echo "**ERROR EN INSERT GUMS_CONCEPTOS CODIGO= " . $ConceptoOrigen["CODIGO"] . $ex->getMessage() . " \n";
        return null;
    }
}

/**
 * main
 * 
 * @return boolean
 */
function main() {
    /**
     * Inicializamos las Tablas 
     */
    $CargaConceptoAll = selectCargaConceptos();

    echo " Registros a Carga : (" . count($CargaConceptoAll) . ") \n";
    /**
     * carga de Concepto, primero comprobamos si ya existe el registro en la base de datos Unificada y de Control 
     * si no existe creamos el registro desde el area origen  y generamos la equivalencias tanto en unificada como en la de control 
     * 
     */
    foreach ($CargaConceptoAll as $CargaConcepto) {
        echo " ==> TRATAMIENTO CONCEPTO CODIGO_UNI = (" . $CargaConcepto["codigo_uni"] . ") "
        . "DESCRIPCION= (" . $CargaConcepto["descripcion"] . ") "
        . " AREA ORIGEN= (" . $CargaConcepto["area_origen"] . ") "
        . " CODIGO_LOCAL= (" . $CargaConcepto["codigo_loc"] . ") "
        . "\n";

        /**
         * comprobamos si ya esta creada en la base de datos unificada 
         */
        $existe = selectConceptoUnif($CargaConcepto["codigo_uni"]);
        if (!$existe) {
            $ok = insertConceptoUnif($CargaConcepto);
            if (!$ok) {
                continue;
            }
        }
        /**
         * comprobamos si existe en la base de datos de control 
         */
        $concepto_id = selectConceptoByCodigo($CargaConcepto["codigo_uni"]);

        if ($concepto_id == "") {
            $concepto_id = insertConceptoControl($CargaConcepto);
        }
        $EdificioAll = selectEdificioAll();

        foreach ($EdificioAll as $Edificio) {
            $variable = "codigo_a" . $Edificio["codigo"];
            $codigo_loc = $CargaConcepto[$variable];
            if ($codigo_loc != "") {
                $ok = insertEqConceptosInte($Edificio["codigo"], $CargaConcepto["codigo_uni"], $codigo_loc);
                if (!$ok) {
                    continue;
                }
                $EqConcepto["codigo_loc"] = $codigo_loc;
                $EqConcepto["enuso"] = "S";
            } else {
                $EqConcepto["codigo_loc"] = "XXX";
                $EqConcepto["enuso"] = "X";
            }
            $EqConcepto["edificio"] = $Edificio["codigo"];
            $EqConcepto["edificio_id"] = $Edificio["id"];
            $EqConcepto["concepto_id"] = $concepto_id;
            $EqConcepto["codigo_uni"] = $CargaConcepto["codigo_uni"];

            insertEqConceptoControl($EqConcepto);
        }
    }

    return true;
}

/**
 * CUERPO PRINCIPAL DEL SCRIPT 
 */
echo " -- CARGA INICIAL TABLA: GUMS_CONCEPTOS " . "\n";

$JanoControl = jano_ctrl();
if (!$JanoControl) {
    exit(1);
}

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

if (!inicializaTablas()) {
    exit(1);
}

main();

echo " TERMINADA LA CARGA DE GUMS_CONCEPTOS " . "\n";
exit;

