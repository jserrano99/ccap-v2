<?php

include_once __DIR__ . '/funcionesDAO.php';

function insertEqCateg($EqCateg) {
    global $JanoControl;
    try {
        $sentencia = "insert into ccap_eq_categ (edificio_id, categ_id, codigo_loc, en_uso)  "
                . " values (:edificio_id, :categ_id, :codigo_loc, :en_uso) ";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":edificio_id" => $EqCateg["edificio_id"],
            ":categ_id" => $EqCateg["categ_id"],
            ":codigo_loc" => $EqCateg["codigo_loc"],
            ":en_uso" => $EqCateg["enUso"]);
        $res = $query->execute($params);
        if ($res == 0) {
            echo "**ERROR EN INSERT CCAP_EQ_CATEG EDIFICIO: " . $EqCateg["edificio"]
            . " CATEG=" . $EqCateg["codigo_uni"]
            . " CODIGO_LOC= " . $EqCateg["codigo_loc"] . "\n";
        }
        echo "INSERT CCAP_EQ_CATEG EDIFICIO: " . $EqCateg["edificio"]
        . " CATEG=" . $EqCateg["codigo_uni"]
        . " CODIGO_LOC= " . $EqCateg["codigo_loc"]
        . " EN USO = " . $EqCateg["enUso"] . "\n";
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERT CCAP_EQ_CATEG EDIFICIO: " . $row["EDIFICIO"]
        . " CATEG=" . $codigo
        . " CODIGO_LOC= " . $row["CODIGO_LOC"] . "\n"
        . $ex->getMessage() . "\n";
    }
}


function selectCategEnUso($conexion, $codigo) {
    global $JanoControl;
    try {
        $sentencia = " select enuso from categ as t1 "
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
        echo " PDOERROR NO EN SELECT CATEG=" . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function existeCateg($codigo) {
    global $JanoControl;
    try {
        $sentencia = " select t1.id from ccap_categ as t1 "
                . " where t1.codigo = :codigo";
        $query = $JanoControl->prepare($sentencia);
        $params = array(":codigo" => $codigo);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res["id"];
        } else {
            echo "**ERROR NO EN SELECT CATEG=" . $codigo . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " PDOERROR NO EN SELECT CATEG=" . $codigo . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * 
 * Sincronización de los puntos asistenciales, partiendo de la tabla centros de la
 * base de datos unificada y la eq_centros de la base de datos intermedia 
 * ** */
echo " +++++++++++ COMIENZA PROCESO CARGA EQUIVALENCIAS CATEG +++++++++++ \n";

/**
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

if ($tipo == 'REAL') {
    echo " **** PRODUCCIÓN **** \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo " ++++ VALIDACIÓN ++++ \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}
$query = " delete from ccap_eq_categ ";
$query = $JanoControl->prepare($query);
$query->execute();

/*
 * Comprobamos que existe el código Unificado en la base de datos de control, si no existe
 * accedemos a la tabla centros de la base de datos unificada, cogemos los datos y hacemos el insert 
 * en la base de datos de control 
 */

$query = " select * from comun_edificio where area = 'S'";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);

$query = " select * from categ order by codigo";
$query = $JanoUnif->prepare($query);
$query->execute();
$CategAll = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($CategAll as $Categ) {
    $codigo = $Categ['CODIGO'];
    $existeCateg_id = existeCateg($codigo);
    foreach ($EdificioAll as $Edificio) {
        $sql = " select * from eq_categ where codigo_uni = :codigo_uni and edificio = :edificio";
        $query = $JanoInte->prepare($sql);
        $params = array(":codigo_uni" => $codigo,
            ":edificio" => $Edificio["codigo"]);
        $query->execute($params);
        $EqCategAll = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($EqCategAll) == 0) {
            $EqCateg["edificio_id"] = $Edificio["id"];
            $EqCateg["edificio"] = $Edificio["codigo"];
            $EqCateg["codigo_loc"] = "XXXX";
            $EqCateg["codigo_uni"] = $Categ["CODIGO"];
            $EqCateg["categ_id"] = $existeCateg_id;
            $EqCateg["enUso"] = "N";
            insertEqCateg($EqCateg);
        } else {
            $conexion = conexionEdificio($Edificio["codigo"],$tipobd);
            foreach ($EqCategAll as $row) {
                $EqCateg["edificio_id"] = $Edificio["id"];
                $EqCateg["edificio"] = $Edificio["codigo"];
                $EqCateg["codigo_loc"] = $row["CODIGO_LOC"];
                $EqCateg["codigo_uni"] = $Categ["CODIGO"];
                $EqCateg["categ_id"] = $existeCateg_id;
                $EqCateg["enUso"] = selectCategEnUso($conexion, $row["CODIGO_LOC"]);
                insertEqCateg($EqCateg);
            }
        }
    }
}

echo "  +++++++++++ TERMINA PROCESO SYNCRONIZACIÓN CATGEN  +++++++++++++ \n";
exit(0);
