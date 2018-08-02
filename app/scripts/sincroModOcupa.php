<?php

include_once __DIR__ . '/funcionesDAO.php';

function selectEqModOcupa($edificio, $codigo_uni) {
    global $connInte;
    try {
        $sentencia = " select * from eq_modocupa  "
                . "  where codigo_uni = :codigo_uni "
                . " and edificio = :edificio";
        $query = $connInte->prepare($sentencia);
        $params = array(":codigo_uni" => $codigo_uni,
            ":edificio" => $edificio);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["CODIGO_LOC"];
        } else {
            echo "** ERROR EN SELECT EQ_MODOCUPA CODIGO_UNI = " . $codigo_uni . " EDIFICIO= " . $edificio . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT EQ_MODOCUPA CODIGO_UNI = " . $codigo_uni . " EDIFICIO= " . $edificio . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function deleteEqModOcupa($ModOcupa) {
    global $connInte;
    try {
        $sentencia = " delete from eq_modocupa where codigo_uni = :codigo_uni";
        $query = $connInte->prepare($sentencia);
        $params = array(":codigo_uni" => $ModOcupa["codigo"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN DELETE EQ_MODOCUPA CODIGO_UNI=" . $ModOcupa["codigo"] . "\n";
        }
        echo "DELETE EQ_MODOCUPA CODIGO_UNI=" . $ModOcupa["codigo"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN DELETE EQ_MODOCUPA EDIFICIO= " . $i
        . " CODIGO_LOC= " . $ModOcupa["codigo"]
        . " CODIGO_UNI=" . $ModOcupa["codigo"]
        . " \t " . $ex->getMessage()
        . " \n";
    }
}

function insertEqModOcupa($ModOcupa) {
    global $connInte;
    for ($i = 0; $i < 12; $i++) {
        try {
            $sentencia = "insert into eq_modocupa "
                    . " (edificio, codigo_loc, codigo_uni) "
                    . " values "
                    . " (:edificio, :codigo_loc, :codigo_uni) ";
            $query = $connInte->prepare($sentencia);
            $params = array(":edificio" => $i,
                ":codigo_loc" => $ModOcupa["codigo"],
                ":codigo_uni" => $ModOcupa["codigo"]);
            $res = $query->execute($params);
            if ($res == 0) {
                echo "**ERROR EN INSERT EQ_MODOCUPA EDIFICIO= " . $i . " CODIGO_LOC= " . $ModOcupa["codigo"] . " CODIGO_UNI=" . $ModOcupa["codigo"] . "\n";
            }
            echo "INSERT EQ_MODOCUPA EDIFICIO= " . $i . " CODIGO_LOC= " . $ModOcupa["codigo"] . " CODIGO_UNI=" . $ModOcupa["codigo"] . "\n";
        } catch (PDOException $ex) {
            echo "**PDOERROR EN INSERT EQ_MODOCUPA EDIFICIO= " . $i
            . " CODIGO_LOC= " . $ModOcupa["codigo"]
            . " CODIGO_UNI=" . $ModOcupa["codigo"]
            . " \t  " . $ex->getMessage()
            . " \n";
        }
    }
}

function procesoInsert($ModOcupa) {
    global $BasesDatos, $connUnif;
    insertModOcupa($connUnif, $ModOcupa);
    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        insertModOcupa($conexion, $ModOcupa);
    }
    insertEqModOcupa($ModOcupa);
}

function procesoDelete($ModOcupa) {
    global $BasesDatos, $connUnif;
    deleteModOcupa($connUnif, $ModOcupa["codigo"]);

    foreach ($BasesDatos as $baseDatos) {
        $alias = $baseDatos["alias"];
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        $codigo = selectEqModOcupa($baseDatos["edificio"], $ModOcupa["codigo"]);
        if ($codigo) {
            deleteModOcupa($conexion, $codigo);
        }
    }
    deleteEqModOcupa($ModOcupa);
}

function insertModOcupa($conexion, $ModOcupa) {
    try {
        $sentencia = "insert into modocupa "
                . " ( codigo, descrip, fie ) values "
                . " ( :codigo, :descrip, :fie ) ";
        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $ModOcupa["codigo"],
            ":descrip" => $ModOcupa["descrip"],
            ":fie" => $ModOcupa["fie"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT MODOCUPA CODIGO= " . $ModOcupa["codigo"] . " DESCRIPCION= " . $ModOcupa["descrip"] . "\n";
            return null;
        }
        echo "INSERT MODOCUPA CODIGO= " . $ModOcupa["codigo"] . " DESCRIPCION= " . $ModOcupa["descrip"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT MODOCUPA CODIGO= " . $ModOcupa["codigo"]
        . " DESCRIPCION= " . $ModOcupa["descrip"]
        . " \t  "
        . $ex->getMessage() . "\n";
        return null;
    }
}

function deleteModOcupa($conexion, $codigo) {
    try {
        $sentencia = "delete from modocupa "
                . " where codigo = :codigo ";

        $query = $conexion->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN DELETE MODOCUPA CODIGO= " . $codigo . "\n";
            return null;
        }
        echo "DELETE MODOCUPA CODIGO= " . $codigo . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN DELETE MODOCUPA CODIGO= " . $codigo . "    " . $ex->getMessage() . "\n";
        return null;
    }
}

echo " ++++++ COMIENZA PROCESO SINCRONIZACIÓN MODOCUPA +++++++++++ \n";
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
$modocupa_id = $argv[2];
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

$ModOcupa = selectModOcupaById($modocupa_id);
if (!$ModOcupa) {
    exit(1);
}

echo " SINCRONIZACIÓN : ID=" . $ModOcupa["id"]
 . " CÓDIGO=" . $ModOcupa["codigo"]
 . " DESCRIPCIÓN= " . $ModOcupa["descrip"]
 . " ACCION =" . $accion
 . "\n";

if ($accion == 'DELETE') {
    if (!procesoDelete($ModOcupa)) {
        exit(1);
    }
}

if ($accion == 'INSERT') {
    if (!procesoInsert($ModOcupa)) {
        exit(1);
    }
}

echo " FIN SINCRONIZACIÓN " . "\n";
exit(0);
