<?php

include_once __DIR__ . '/funcionesDAO.php';
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
        echo "PDOERROR EN LA INSERCIÓN DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco ." ERROR= " . $ex->getMessage() . " \n";
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
            echo " YA EXISTE SELECT CECO =" . $ceco . "  CIAS=" . $cias . "\n";
            return true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        echo " ERROR PDO EN SELECT CECOCIAS " . $ex->getMessage() . " \n";
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

$tipo = $argv[1];
$id = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    $JanoInte = conexionPDO(SelectBaseDatos(2, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(2, 'U'));
    echo " **** PRODUCCIÓN **** \n";
} else {
    $JanoInte = conexionPDO(SelectBaseDatos(1, 'I'));
    $JanoUnif = conexionPDO(SelectBaseDatos(1, 'U'));
    echo " ++++ VALIDACIÓN ++++ \n";
}

echo "id=" . $id . "\n";
$Plaza = selectPlaza($id);

if ($Plaza == null) {
    echo " +++ TERMINA EN ERROR(1) +++ \n";
    exit(1);
}
echo "==> CECOCIAS A TRATAR CIAS= " . $Plaza["cias"] . " CECO=" . $Plaza["ceco"] . " EDIFICIO=" . $Plaza["edificio"] . "\n";

if ($Plaza["edificio"] == 0) {
    $BasesDatos = SelectBaseDatosAreas($tipo);
} else {
    $BasesDatos = array();
    $BasesDatos[] = SelectBaseDatosEdificio($tipo, $Plaza["edificio"]);
    $BasesDatos[] = SelectBaseDatos($tipo, 'U');
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
    $Plaza2 = existePlaza($conexion, $Plaza["cias"]);
    if ($Plaza2) {
        echo " ==>Proceso para : " . $alias . "\n";
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

$asignacion = asignacionCeco($Plaza);
if (!$asignacion)  {
    echo "**ERROR EN LA ASIGNACIÓN AL PROFESIONAL** \n";
    exit(1);
}

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN CECOCIAS +++++++++++++ \n";
exit(0);
