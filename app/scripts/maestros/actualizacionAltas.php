<?php

include_once __DIR__ . '../../funcionesDAO.php';

function insertEqAltas($Altas, $area) {
    global $JanoInte;
    try {
        $sentencia = "insert into eq_altas "
                . " (edificio, codigo_loc, codigo_uni) "
                . " values "
                . " (:edificio, :codigo_loc, :codigo_uni) ";
        $query = $JanoInte->prepare($sentencia);
        $params = array(":edificio" => $area,
            ":codigo_loc" => $Altas["codigo"],
            ":codigo_uni" => $Altas["codigo"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT EQ_ALTAS EDIFICIO= " . $area . " CODIGO_LOC= " . $Altas["codigo"] . " CODIGO_UNI=" . $Altas["codigo"] . "\n";
            return null;
        }
        echo "INSERT EQ_ALTAS EDIFICIO= " . $area . " CODIGO_LOC= " . $Altas["codigo"] . " CODIGO_UNI=" . $Altas["codigo"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT EQ_ALTAS EDIFICIO= " . $area
        . " CODIGO_LOC= " . $Altas["codigo"]
        . " CODIGO_UNI=" . $Altas["codigo"]
        . " \t  " . $ex->getMessage()
        . " \n";
        return null;
    }
}

function updateEqAltasControl($EqAltas) {
    global $JanoControl;
    try {
        $sql = " update gums_eq_altas set "
                . " codigo_loc = :codigo_loc "
                . " ,enuso = :enuso"
                . " where id = :id ";
        $query = $JanoControl->prepare($sql);
        $params = array(":codigo_loc" => $EqAltas["codigo_loc"],
            ":id" => $EqAltas["id"],
            ":enuso" => $EqAltas["enuso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE GUMS_EQ_ALTAS ALTA_ID= " . $EqAltas["id"] . "\n";
            return null;
        }
        echo "-->UPDATE GUMS_EQ_ALTAS CODIGO_LOC=(" . $EqAltas["codigo_loc"] . ") EDIFICIO=(" . $EqAltas["edificio"] . ") Uso= (" . $EqAltas["enuso"] . ") \n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE GUMS_EQ_ALTASCODIGO_LOC=(" . $EqAltas["codigo_loc"] . ")  ALTA_ID= (" . $Altas["id"]
        . $ex->getMessage() . "\n";
        return null;
    }
}

function procesoInsert($Altas) {
    global $tipobd;

    if (!insertAltasUnif($Altas)) {
        return null;
    }


    for ($i = 0; $i < 12; $i++) {
        $conexion = conexionEdificio($i, $tipobd);
        if ($conexion) {
            if (insertAltasAreas($conexion, $Altas, $i)) {
                insertEqAltas($Altas, $i);
            }
        }
    }
}

function procesoUpdate($Altas) {
    global $BasesDatos, $JanoUnif;
    updateAltas($JanoUnif, $Altas, $Altas["codigo"]);


    for ($i = $inicio; $i < $fin; $i++) {
        echo "--> Tratamiento Edificio : (" . $i . ") \n";
        echo " Equivalencia Código " . $Altas["codigo"] . " \n";
        $edificio_id = selectEdificio($i);
        $codigo = selectEqAltas($Altas["id"], $edificio_id);
        if ($codigo) {
            echo "-->Codigo = (" . $Altas["codigo"] . ") / (" . $codigo . ")\n";
            $conexion = conexionEdificio($i, $tipobd);
            if ($conexion) {
                updateAltasAreas($Altas, $conexion, $codigo, $edificio_id);
            }
        }
    }
}

function insertAltasUnif($Altas) {
    global $JanoUnif;
    try {
        $sentencia = " insert into altas "
                . " ( codigo, descrip, btc_mcon_codigo, btc_tipocon, subaltas_afi "
                . "  , subaltas_certi, certificar, enuso, motivoaltarptid"
                . "  ,malrpt_codigo, malrpt_descripcion, l13, rel_juridica, pagar_tramo"
                . "  ,destino, modocupa, modopago, patronal ) values "
                . " ( :codigo, :descrip, :btc_mcon_codigo, :btc_tipocon, :subaltas_afi "
                . "  ,:subaltas_certi, :certificar, :enuso, :motivoaltarptid"
                . "  ,:malrpt_codigo, :malrpt_descripcion, :l13, :rel_juridica, :pagar_tramo"
                . "  ,:destino, :modocupa, :modopago, :movipat)";

        $query = $JanoUnif->prepare($sentencia);

        $params = array(':codigo' => $Altas["codigo"]
            , ':descrip' => $Altas["descrip"]
            , ':btc_mcon_codigo' => $Altas["btc_mcon_codigo"]
            , ':btc_tipocon' => $Altas["btc_tipocon"]
            , ':subaltas_afi' => $Altas["subaltas_afi"]
            , ':subaltas_certi' => $Altas["subaltas_certi"]
            , ':certificar' => $Altas["certificar"]
            , ':enuso' => $Altas["enuso"]
            , ':motivoaltarptid' => $Altas["motivoaltarptid"]
            , ':malrpt_codigo' => $Altas["malrpt_codigo"]
            , ':malrpt_descripcion' => $Altas["malrpt_descripcion"]
            , ':l13' => $Altas["l13"]
            , ':rel_juridica' => $Altas["rel_juridica"]
            , ':pagar_tramo' => $Altas["pagar_tramo"]
            , ':destino' => $Altas["destino"]
            , ':modocupa' => $Altas["modocupa"]
            , ':modopago' => $Altas["modopago"]
            , ':movipat' => $Altas["movipat"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT ALTAS CODIGO= " . $Altas["codigo"] . " DESCRIPCION= " . $Altas["descrip"] . " BASE DE DATOS UNIFICADA \n";
            return null;
        }
        echo "INSERT ALTAS CODIGO= " . $Altas["codigo"] . " DESCRIPCION= " . $Altas["descrip"] . " BASE DE DATOS UNIFICADA \n";
        return true;
    } catch (PDOException $ex) {
        echo "***(1)PDOERROR EN INSERT ALTAS CODIGO= " . $Altas["codigo"]
        . " DESCRIPCION= " . $Altas["descrip"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

function insertAltasAreas($conexion, $Altas, $edificio) {

    $edificio_id = selectEdificio($edificio);

    $modocupa = selectEqModOcupa($Altas["modocupa_id"], $edificio_id);
    $movipat = selectEqMoviPat($Altas["movipat_id"], $edificio_id);
    $modopago = selectEqModoPago($Altas["modopago_id"], $edificio_id);

    echo " EQUIVALENCIAS UNIF/LOCAL \n";
    echo " Modocupa = " . $Altas["modocupa"] . "/" . $modocupa . "\n";
    echo " Movipat = " . $Altas["movipat"] . "/" . $movipat . "\n";
    echo " ModoPago = " . $Altas["modopago"] . "/" . $modopago . "\n";

    try {
        $sentencia = " insert into altas "
                . " ( codigo, descrip, btc_mcon_codigo, btc_tipocon, subaltas_afi "
                . "  , subaltas_certi, certificar, enuso, motivoaltarptid"
                . "  ,malrpt_codigo, malrpt_descripcion, l13, rel_juridica, pagar_tramo"
                . "  ,destino, modocupa, modopago, patronal ) values "
                . " ( :codigo, :descrip, :btc_mcon_codigo, :btc_tipocon, :subaltas_afi "
                . "  ,:subaltas_certi, :certificar, :enuso, :motivoaltarptid"
                . "  ,:malrpt_codigo, :malrpt_descripcion, :l13, :rel_juridica, :pagar_tramo"
                . "  ,:destino, :modocupa, :modopago, :movipat)";

        $query = $conexion->prepare($sentencia);

        $params = array(':codigo' => $Altas["codigo"]
            , ':descrip' => $Altas["descripcion"]
            , ':btc_mcon_codigo' => $Altas["btc_mcon_codigo"]
            , ':btc_tipocon' => $Altas["btc_tipocon"]
            , ':subaltas_afi' => $Altas["subaltas_afi"]
            , ':subaltas_certi' => $Altas["subaltas_certi"]
            , ':certificar' => $Altas["certificar"]
            , ':enuso' => $Altas["enuso"]
            , ':motivoaltarptid' => $Altas["motivoaltarptid"]
            , ':malrpt_codigo' => $Altas["malrpt_codigo"]
            , ':malrpt_descripcion' => $Altas["malrpt_descripcion"]
            , ':l13' => $Altas["l13"]
            , ':rel_juridica' => $Altas["rel_juridica"]
            , ':pagar_tramo' => $Altas["pagar_tramo"]
            , ':destino' => $Altas["destino"]
            , ':modocupa' => $modocupa
            , ':modopago' => $modopago
            , ':movipat' => $movipat);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT ALTAS CODIGO= " . $Altas["codigo"] . " DESCRIPCION= " . $Altas["descrip"] . " \n";
            return null;
        }
        echo " INSERT ALTAS CODIGO= " . $Altas["codigo"] . " DESCRIPCION= " . $Altas["descrip"] . " \n";
        return true;
    } catch (PDOException $ex) {
        echo "***(2)PDOERROR EN INSERT ALTAS CODIGO= " . $Altas["codigo"]
        . " DESCRIPCION= " . $Altas["descrip"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}
/**
 * 
 * @param type $conexion
 * @param type $Altas
 * @param type $codigo
 * @return boolean
 */
function updateAltas($conexion, $Altas, $codigo) {
    try {
        $sentencia = " update altas set  "
                . "  descrip = :descrip "
                . ", btc_mcon_codigo = :btc_mcon_codigo "
                . ", btc_tipocon = :btc_tipocon "
                . ", subaltas_afi = :subaltas_afi  "
                . ", subaltas_certi =  :subaltas_certi "
                . ", certificar = :certificar "
                . ", enuso = :enuso "
                . ", motivoaltarptid = :motivoaltarptid "
                . ", malrpt_codigo = :malrpt_codigo "
                . ", malrpt_descripcion = :malrpt_descripcion "
                . ", l13 = :l13 "
                . ", rel_juridica = :rel_juridica "
                . ", pagar_tramo = :pagar_tramo "
                . ", destino = :destino "
                . ", modocupa = :modocupa "
                . ", modopago = :modopago "
                . ", patronal = :movipat "
                . " where codigo = :codigo";
        $query = $conexion->prepare($sentencia);
        $params = array(':codigo' => $codigo
            , ':descrip' => $Altas["descrip"]
            , ':btc_mcon_codigo' => $Altas["btc_mcon_codigo"]
            , ':btc_tipocon' => $Altas["btc_tipocon"]
            , ':subaltas_afi' => $Altas["subaltas_afi"]
            , ':subaltas_certi' => $Altas["subaltas_certi"]
            , ':certificar' => $Altas["certificar"]
            , ':enuso' => $Altas["enuso"]
            , ':motivoaltarptid' => $Altas["motivoaltarptid"]
            , ':malrpt_codigo' => $Altas["malrpt_codigo"]
            , ':malrpt_descripcion' => $Altas["malrpt_descripcion"]
            , ':l13' => $Altas["l13"]
            , ':rel_juridica' => $Altas["rel_juridica"]
            , ':pagar_tramo' => $Altas["pagar_tramo"]
            , ':destino' => $Altas["destino"]
            , ':modocupa' => $Altas["modocupa"]
            , ':modopago' => $Altas["modopago"]
            , ':movipat' => $Altas["movipat"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE ALTAS CODIGO= " . $codigo . " DESCRIPCION= " . $Altas["descripcion"] . "\n";
            return null;
        }
        echo "UPDATE ALTAS CODIGO= " . $codigo . " DESCRIPCION= " . $Altas["descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN UPDATE ALTAS CODIGO= " . $codigo
        . " DESCRIPCION= " . $Altas["descrip"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

echo " ++++++ COMIENZA PROCESO SINCRONIZACIÓN MOTIVOS DE ALTA (ALTAS) +++++++++++ \n";
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
$altas_id = $argv[2];
$actuacion = $argv[3];
$eqaltas_id = $argv[4];

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


$Altas = selectAltasById($altas_id);

if (!$Altas) {
    exit(1);
}

echo " SINCRONIZACIÓN ALTAS : ID=" . $Altas["id"]
 . " CÓDIGO=" . $Altas["codigo"]
 . " DESCRIPCIÓN= " . $Altas["descripcion"]
 . " ACTUACION= " . $actuacion
 . " EQALTAS-ID= " . $eqaltas_id
 . "\n";

if ($actuacion == 'INSERT') {
    if (!procesoInsert($Altas)) {
        echo "  +++++++++++ TERMINA PROCESO INSERT EN ERROR +++++++++++++ \n";
        exit(1);
    }
}

if ($actuacion == 'UPDATE') {
    if (!procesoUpdate($Altas)) {

        exit(1);
    }
}

if ($actuacion == 'ACTIVAR') {
    $EqAltas = selectEqAltasById($eqaltas_id);
    $conexion = conexionEdificio($EqAltas["edificio"], $tipobd);
    $Altas["enuso"] = 'S';
    if ($conexion) {
        updateAltas($conexion, $Altas, $EqAltas["codigo_loc"]);
        $EqAltas["enuso"] = 'S';
        updateEqAltasControl($EqAltas);
    }
}

if ($actuacion == 'DESACTIVAR') {
    $EqAltas = selectEqAltasById($eqaltas_id);
    $conexion = conexionEdificio($EqAltas["edificio"], $tipobd);
    $Altas["enuso"] = 'N';
    if ($conexion) {
        updateAltas($conexion,$Altas, $EqAltas["codigo_loc"]);
        $EqAltas["enuso"] = 'N';
        updateEqAltasControl($EqAltas);
    }
}

if ($actuacion == 'CREAR') {
    $EqAltas = selectEqAltasById($eqaltas_id);
    $conexion = conexionEdificio($EqAltas["edificio"], $tipobd);
    $Altas["codigo"] = $EqAltas["codigo_loc"];
    if ($conexion) {
        if (insertAltasAreas($ALTAS, $conexion, $EqAltas["edificio"])) {
            $EqAltas["enuso"] = 'S';
            updateEqAltasControl($EqAltas);
        }
    }
}




echo " FIN SINCRONIZACIÓN " . "\n";
exit(0);
