<?php

include_once __DIR__ . '../../funcionesDAO.php';

function selectAltasById($id) {
    global $JanoControl;
    try {
        $sentencia = " select t1.*, t2.codigo as movipat,t3.codigo as modocupa, t4.codigo as modopago from gums_altas as t1 "
                . " right join gums_movipat as t2 on t2.id = t1.movipat_id "
                . " right join gums_modocupa as t3 on t3.id = t1.modocupa_id"
                . " right join gums_modopago as t4 on t4.id = t1.modopago_id"
                . " where t1.id = :id ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":id" => $id);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo "**ERROR NO EXISTE GUMS_ALTAS PARA ID= " . $id . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " ***PDOERROR EN GUMS_ALTAS PARA ID=" . $id . " ERROR=" . $ex->getMessage() . "\n";
        return null;
    }
}

function selectEqAltas($edificio, $codigo_uni) {
    global $JanoInte;
    try {
        $sentencia = " select * from eq_altas  "
                . "  where codigo_uni = :codigo_uni "
                . " and edificio = :edificio";
        $query = $JanoInte->prepare($sentencia);
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
        echo "**PDOERROR EN SELECT EQ_ALTAS CODIGO_UNI = " . $codigo_uni . " EDIFICIO= " . $edificio . " " . $ex->getMessage() . "\n";
        return null;
    }
}

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
        echo "**PDOERROR EN INSERT EQ_ALTAS EDIFICIO= " . $i
        . " CODIGO_LOC= " . $Altas["codigo"]
        . " CODIGO_UNI=" . $Altas["codigo"]
        . " \t  " . $ex->getMessage()
        . " \n";
        return null;
    }
}

function updateEqAltasControl($Altas) {
    global $JanoControl;
    try {
        $sql = " update gums_eq_altas set "
                . " codigo_loc = :codigo_loc "
                . " ,enuso = :enuso"
                . " where id = :id ";
        $query = $JanoControl->prepare($sql);
        $params = array(":codigo_loc" => $Altas["codigo"],
            ":id" => $Altas["id"],
            ":enuso" => 'S');
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN UPDATE GUMS_EQ_ALTAS ALTA_ID= " . $Altas["id"] . "\n";
            return null;
        }
        echo "-->UPDATE GUMS_EQ_ALTAS CODIGO_LOC=(" . $Altas["codigo"] . ")  ALTA_ID= (" . $Altas["id"] . ") \n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE GUMS_EQ_ALTASCODIGO_LOC=(" . $Altas["codigo"] . ")  ALTA_ID= (" . $Altas["id"]
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
        echo "-->(" . $i . ") Equivalencia Código " . $CATEG["codigo"] . " \n";
        $codigo = selectEqCateg($CATEG["id"], selectEdificio($i));
        if ($codigo) {
            echo "-->Codigo = " . $CATEG["codigo"] . "/" . $codigo . "\n";
            $conexion = conexionEdificio($i, $tipobd);
            if ($conexion) {
                updateCategAreas($CATEG, $conexion, $codigo, $i);
            }
        }
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
        $EqAltas = selectEqAltas($baseDatos["edificio"], $Altas["codigo"]);
        if ($EqAltas) {
            foreach ($EqAltas as $linea) {
                updateAltas($conexion, $Altas, $linea["CODIGO_LOC"]);
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
            echo "**ERROR EN UPDATE ALTAS CODIGO= " . $codigo . " DESCRIPCION= " . $Altas["descrip"] . "\n";
            return null;
        }
        echo "UPDATE ALTAS CODIGO= " . $codigo . " DESCRIPCION= " . $Altas["descrip"] . "\n";
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
        updateAltas($conexion, $EqAltas["codigo_loc"]);
        $EqAltas["enuso"] = 'S';
        updateEqAltasControl($EqAltas);
    }
}

if ($actuacion == 'DESACTIVAR') {
    $EqAltas = selectEqAltasById($eqaltas_id);
    $conexion = conexionEdificio($EqAltas["edificio"], $tipobd);
    $Altas["enuso"] = 'N';
    if ($conexion) {
        updateAltas($conexion, $EqAltas["codigo_loc"]);
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
