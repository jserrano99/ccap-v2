<?php

include_once __DIR__ . '/funcionesDAO.php';

function insertCeco($conexion, $Ceco) {
    try {
        $sentencia = "insert into cecos ( sociedad, division, ceco, descripcion ) values ("
                . "  :sociedad, :division, :ceco ,:descripcion )";
        $query = $conexion->prepare($sentencia);
        $params = [":sociedad" => $Ceco["sociedad"],
            ":division" => $Ceco["division"],
            ":ceco" => $Ceco["codigo"],
            ":descripcion" => $Ceco["descripcion"]];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN LA INSERCIÓN " . $Ceco["codigo"] . " " . $Ceco["descripcion"] . "\n";
            return;
        }
        echo " INSERCIÓN CORRECTA CECO= " . $Ceco["codigo"] . " " . $Ceco["descripcion"] . "\n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN INSERCIÓN CODIGO= " . $Ceco["codigo"] . " " . $ex->getMessage() . " \n";
        return false;
    }
}

function updateCeco($conexion, $Ceco) {
    try {
        $sentencia = "update cecos set "
                . " descripcion = :descripcion "
                . " where ceco = :ceco ";
        $query = $conexion->prepare($sentencia);
        $params = [":ceco" => $Ceco["codigo"],
            ":descripcion" => $Ceco["descripcion"]];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN UPDATE CODIGO=" . $Ceco["codigo"] . " \n";
            return null;
        }
        echo " CECO MODIFICADO CÓDIGO: " . $Ceco["codigo"] . " DESCRIPCIÓN= " . $Ceco["descripcion"] . " \n";
    } catch (PDOException $ex) {
        echo "***PDOERROR EN UPDATE CÓDIGO= " . $Ceco["codigo"] . " " . $ex->getMessage() . " \n";
        return false;
    }
    return true;
}

function deleteCeco($conexion, $codigo) {
    $sentencia = "delete from cecos "
            . " where ceco = :ceco ";
    try {
        $query = $conexion->prepare($sentencia);
        $params = [":ceco" => $codigo];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "**ERROR EN DELETE " . $codigo . " \n";
            return null;
        }
        echo " DELETE CORRECTO CECO=: " . $codigo . " \n";
        return true;
    } catch (PDOException $ex) {
        echo "**PDOERROR EN  DELETE EN CODIGO= " . $codigo . " " . $ex->getMessage() . " \n";
        return false;
    }
}

function existeCeco($conexion, $codigo) {
    try {
        $sentencia = "select ceco from cecos where ceco =:ceco ";
        $query = $conexion->prepare($sentencia);
        $params = [":ceco" => $codigo];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT CECO= " . $codigo . $ex->getMessage() . " \n";
        return false;
    }
}

/* * ***
 * CUERPO PRINCIPAL DEL SCRIPT
 * ** */
$resultado = 1;
echo " +++++++++++ COMIENZA PROCESO SINCRONIZACION CECO +++++++++++ \n";
$JanoControl = jano_ctrl();

if (!$JanoControl) {
    exit(1);
}

$tipo = $argv[1];
$ceco_id = $argv[2];
$actuacion = $argv[3];

if ($tipo == 'REAL') {
    echo " ENTORNO: PRODUCCIÓN \n";
    $JanoInte = conexionPDO(selectBaseDatos(2, 'I'));
    $BasesDatos = selectBaseDatosAreas(2);
    $BasesDatos[] = selectBaseDatos(2, 'U');
} else {
    echo " ENTORNO : VALIDACIÓN \n";
    $JanoInte = conexionPDO(selectBaseDatos(1, 'I'));
    $BasesDatos = selectBaseDatosAreas(1);
    $BasesDatos[] = selectBaseDatos(1, 'U');
}

$Ceco = selectCeco($ceco_id);

echo "==> CECO: SOCIEDAD= " . $Ceco["sociedad"]
 . "    DIVISION= " . $Ceco["division"]
 . "    CECO= " . $Ceco["codigo"]
 . "    DESCRIPCION= " . $Ceco["descripcion"]
 . "    ACTUACION= " . $actuacion . "\n ";

switch ($actuacion) {
    case "INSERT":
        insertEqCeco($Ceco["codigo"]);
        break;
    case "DELETE":
        deleteEqCeco($Ceco["codigo"]);
        break;
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
    //echo " ==>Proceso para : " . $alias . "\n";
    switch ($actuacion) {
        case "INSERT" :
            if (!existeCeco($conexion, $Ceco["codigo"])) {
                $error = insertCeco($conexion, $Ceco);
            }
            break;
        case "DELETE":
            if (existeCeco($conexion, $Ceco["codigo"])) {
                $error = deleteCeco($conexion, $Ceco["codigo"]);
            }
            break;
        case "UPDATE":
            if (existeCeco($conexion, $Ceco["codigo"])) {
                $error = updateCeco($conexion, $Ceco);
            }
            break;
    }
}
echo "  +++++++++++ TERMINA PROCESO SINCRONIZACIÓN CECO +++++++++++++ \n";
exit(0);
