<?php

include_once __DIR__ . '/../funcionesDAO.php';
include_once __DIR__ . '/asignacionCeco.php';

/**
 * @param $conexion
 * @param $cias
 * @param $ceco
 * @return bool|null
 */
function insertCecoCias($conexion, $cias, $ceco)
{
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
            echo "****ERROR EN LA INSERCIÓN DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . "\n";
            return null;
        }
        echo "==> INSERCIÓN CORRECTA DE CECOCIAS CIAS =(" . $cias, ")  CECO= (" . $ceco . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN LA INSERCIÓN DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . " ERROR= " . $ex->getMessage() . " \n";
        return null;
    }
}

/**
 * @param $conexion
 * @param $cias
 * @param $ceco
 * @return bool|null
 */
function updateCecoCias($conexion, $cias, $ceco)
{
    try {
        $sentencia = "update cecocias"
            . " set  ceco = :ceco "
            . " where cias = :cias ";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias,
            ":ceco" => $ceco];
        $ins = $query->execute($params);
        if ($ins == 0) {
            echo "****ERROR  UPDATE  DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . "\n";
            return null;
        }
        echo "==> ACTUALIZACION CORRECTA DE CECOCIAS CIAS =(" . $cias, ")  CECO= (" . $ceco . ") \n";
        return true;
    } catch (PDOException $ex) {
        echo "***PDOERROR EN LA INSERCIÓN DE CECOCIAS CIAS=" . $cias, "  CECO=" . $ceco . " ERROR= " . $ex->getMessage() . " \n";
        return null;
    }
}
/*
 * Comprobar si existe la plaza en las b.d de las areas 
 */
/**
 * @param $conexion
 * @param $cias
 * @return null
 */
function existePlaza($conexion, $cias)
{
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
        echo "**PDOERROR EN SELECT PLAZA=" . $cias . " " . $ex->getMessage() . "\n";
        return null;
    }
}

/**
 * @param $conexion
 * @param $cias
 * @return bool
 */
function existeCecoCias($conexion, $cias)
{
    try {
        $sentencia = "select cias  from cecocias  "
            . "  where cias =  :cias ";
        $query = $conexion->prepare($sentencia);
        $params = [":cias" => $cias];
        $query->execute($params);
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            //echo "-->YA EXISTE CECOCIAS PARA CIAS=" . $cias . " ** NO SE TRATA\n";
            return true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        echo "**PDOERROR EN SELECT CECOCIAS  CIAS=" . $cias . $ex->getMessage() . " \n";
        return false;
    }

    return true;
}

/**
 * FUNCION PRINCIPAL
 */
function main(){
    global $Plaza, $tipobd, $CecoCias;

    echo "==> CECOCIAS CIAS= (" . $Plaza["cias"] . ") CECO= (" . $Plaza["ceco"] . ") FECHA INICIO= (" . $CecoCias["f_inicio"] . ") EDIFICIO=" . $Plaza["edificio"] . "\n";

    $BasesDatos = array();
    $BasesDatos[] = SelectBaseDatosEdificio($tipobd, $Plaza["edificio"]);
    $BasesDatos[] = SelectBaseDatos($tipobd, 'U');

    foreach ($BasesDatos as $baseDatos) {
        $datosConexion["maquina"] = $baseDatos["maquina"];
        $datosConexion["puerto"] = $baseDatos["puerto"];
        $datosConexion["servidor"] = $baseDatos["servidor"];
        $datosConexion["esquema"] = $baseDatos["esquema"];
        $datosConexion["usuario"] = $baseDatos["usuario"];
        $datosConexion["password"] = $baseDatos["password"];
        $conexion = conexionPDO($datosConexion);
        if ($conexion) {
            if (existePlaza($conexion, $Plaza["cias"])) {
                if (!existeCecoCias($conexion, $Plaza["cias"])) {
                    insertCecoCias($conexion, $Plaza["cias"], $Plaza["ceco"]);
                } else {
                    updateCecoCias($conexion, $Plaza["cias"], $Plaza["ceco"]);
                }
            }
        }

//    $asignacion = asignacionCeco($Plaza,$CecoCias["f_inicio"]);
//    if (!$asignacion) {
//        echo "**ERROR EN LA ASIGNACIÓN AL PROFESIONAL** \n";
//        exit(1);
//    }

    }

}

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
$cecocias_id = $argv[2];

echo "==> CECOCIAS A TRATAR id =(" . $cecocias_id . ") \n";

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


$CecoCias = selectCecoCiasById($cecocias_id);
$Ceco = selectCeco($CecoCias["ceco_id"]);
$Plaza = selectPlazaById($CecoCias["plaza_id"]);

if ($Plaza == null) {
    echo "***ERROR NO EXISTE CCAP_PLAZA PARA ID= " . $CecoCias["plaza_id"];
    exit(1);
}

main();

echo "  +++++++++++ TERMINA PROCESO ACTUALIZACIÓN CECOCIAS +++++++++++++ \n";
exit(0);
