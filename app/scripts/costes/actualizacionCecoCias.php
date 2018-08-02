<?php

include_once __DIR__ . '/../funcionesDAO.php';
include_once __DIR__ . '/asignacionCeco.php';

function insertCecoCias($conexion, $cias, $ceco) {
    try {
        $sentencia = "insert into cecocias ( "
                . "  cias "
                . " ,ceco "
                . " ) values ("
                . "  :cias "
                . " ,:ceco "
                . " )";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "ERROR EN LA INSERCIÓN DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . "\n";
            return null;
        }
        echo "INSERCIÓN CORRECTA DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "PDOERROR EN LA INSERCIÓN DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . " ERROR= " . $ex->getMessage() . " \n";
        return null;
    }
}

function DeleteCecoCias($conexion, $CecoCias) {
    try {
        $sentencia = "delete from cecocias  "
                . "  where cias =  :cias "
                . "   and  ceco =  :ceco ";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $CecoCias["cias"],
            ":ceco" => $CecoCias["ceco"]];
        $ins = $query->execute($params);
        echo " Borrado Correcto: " . $ins . " \n";
    } catch (PDOException $ex) {
        echo " ERROR PDO EN BORRADO " . $ex->getMessage() . " \n";
        return false;
    }

    return true;
}

/*
 * Comprobar si existe la plaza en las b.d de las areas 
 */

function existePlaza($conexion, $cias) {
    try {
        $sentencia = " select cias from plazas where cias = :cias  ";
        $query = $conexion->prepare($sentencia);
        $params = array(":cias" => $cias);
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $res;
        } else {
            echo " NO EXISTE SELECT PLAZA=" . $cias . "\n";
            return null;
        }
    } catch (PDOException $ex) {
        echo " ERROR PDO EN SELECT PLAZA=" . $cias . " " . $ex->getMessage() . "\n";
        return null;
    }
}

function existeCecoCias($conexion, $cias, $ceco) {
    try {
        $sentencia = "select cias, ceco from cecocias  "
                . "  where cias =  :cias "
                . "   and  ceco =  :ceco ";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            echo "--YA EXISTE CECOCIAS PARA CECO =" . $ceco . "  CIAS=" . $cias . " ** NO SE TRATA\n";
            return true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT CECOCIAS CECO= " . $ceco . "  CIAS=" . $cias . $ex->getMessage() . " \n";
        return false;
    }

    return true;
}

/*
 * definimos las conexiones a las bases de datos intermedia jano_inte, unificada unif_01 Y CONTROL DE LOG JANO_CTRL 
 * a nivel global para poder usarlas en todo el proceso
 */

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO ACTUALIZACIÓN CECOCIAS +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$modo = $argv[1];
$plaza_id = $argv[2];
$actuacion = $argv[3];

if ($modo == 'REAL') {
    echo "==>ENTORNO: PRODUCCIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    $tipobd = 2;
} else {
    echo "==>ENTORNO: VALIDACIÓN \n";
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    $tipobd = 1;
}

$Plaza = selectPlazaById($plaza_id);

if ($Plaza == null) {
    echo "***ERROR NO EXISTE CCAP_PLAZA PARA ID= " . $plaza_id;
    echo " +++ TERMINA EN ERROR(1) +++ \n";
    exit(1);
}
echo "==> CECOCIAS A TRATAR CIAS= " . $Plaza["cias"] . " CECO=" . $Plaza["ceco"] . " EDIFICIO=" . $Plaza["edificio"] . "\n";

$query = " select * from comun_edificio where area = 'S' ";
$query = $JanoControl->prepare($query);
$query->execute();
$EdificioAll = $query->fetchAll(PDO::FETCH_ASSOC);


if ($Plaza["edificio"] == 0) {
    $BasesDatos = SelectBaseDatosAreas($tipo);
} else {
    $BasesDatos = array();
    $BasesDatos[] = SelectBaseDatosEdificio($tipobd, $Plaza["edificio"]);
    $BasesDatos[] = SelectBaseDatos($tipobd, 'U');
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
    if ($conexion) {
        if (existePlaza($conexion, $Plaza["cias"])) {
            switch ($actuacion) {
                case "INSERT" :
                    if (!existeCecoCias($conexion, $Plaza["cias"], $Plaza["ceco"])) {
                        $error = insertCecoCias($conexion, $Plaza["cias"], $Plaza["ceco"]);
                    }
                    break;
                case "DELETE":
                    if (existeCecoCias($conexion, $Plaza["cias"], $Plaza["ceco"])) {
                        $error = DeleteCecoCias($conexion, $Plaza["cias"], $Plaza["ceco"]);
                    }
                    break;
            }
        }
    }
}

$asignacion = asignacionCeco($Plaza);
if (!$asignacion) {
    echo "**ERROR EN LA ASIGNACIÓN AL PROFESIONAL** \n";
    exit(1);
}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN CECOCIAS +++++++++++++ \n";
exit(0);
